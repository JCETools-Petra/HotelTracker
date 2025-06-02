<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User di-import
use App\Models\Property; // Akan kita butuhkan untuk form create/edit nanti
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Untuk hashing password
use Illuminate\Validation\Rules; 
use Illuminate\Validation\Rule; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pengguna, urutkan berdasarkan nama atau tanggal dibuat
        // Eager load relasi 'property' untuk menampilkan nama properti jika ada
        $users = User::with('property') // 'property' adalah nama metode relasi di model User
                     ->orderBy('name', 'asc')
                     ->paginate(15); // Tampilkan 15 pengguna per halaman

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua properti untuk ditampilkan di dropdown pilihan
        // Hanya properti yang belum memiliki pengguna terkait (jika aturannya 1 properti = 1 pengguna)
        // atau semua properti jika 1 properti bisa dikelola banyak pengguna (sesuaikan logika ini jika perlu)
        
        // Untuk saat ini, kita ambil semua properti.
        // Jika Anda ingin hanya menampilkan properti yang BELUM memiliki pengguna_properti,
        // Anda perlu query yang lebih kompleks untuk mengecek relasi.
        // Contoh sederhana:
        // $assignedPropertyIds = User::where('role', 'pengguna_properti')->whereNotNull('property_id')->pluck('property_id');
        // $properties = Property::whereNotIn('id', $assignedPropertyIds)->orderBy('name')->get();
        // Namun, jika satu properti bisa memiliki beberapa user, atau user bisa di-reassign, tampilkan semua.
        
        $properties = Property::orderBy('name')->get();

        if ($properties->isEmpty()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak ada properti tersedia untuk dikaitkan dengan pengguna baru. Harap tambahkan properti terlebih dahulu.');
        }

        return view('admin.users.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class], // Pastikan email unik
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // 'confirmed' akan memeriksa password_confirmation
            'property_id' => ['required', 'integer', 'exists:properties,id'], // Pastikan property_id valid
        ], [
            'name.required' => 'Nama pengguna harus diisi.',
            'email.required' => 'Alamat email harus diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'property_id.required' => 'Properti yang dikelola harus dipilih.',
            'property_id.exists' => 'Properti yang dipilih tidak valid.',
        ]);

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna_properti', // Set peran secara otomatis
            'property_id' => $request->property_id,
            'email_verified_at' => now(), // Opsional: langsung set email terverifikasi
        ]);

        // Opsional: Kirim email notifikasi ke pengguna baru dengan detail login mereka
        // Mail::to($user->email)->send(new NewUserWelcomeEmail($user, $request->password));

        return redirect()->route('admin.users.index')->with('success', 'Pengguna properti baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Kita tidak menggunakan 'show' untuk user individual, jadi bisa dikosongkan atau dihapus jika di-except di route.
     */
    // public function show(User $user)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route Model Binding untuk $user
    {
        // Ambil semua properti untuk ditampilkan di dropdown pilihan
        // Ini diperlukan jika admin ingin mengubah properti yang dikelola oleh pengguna_properti
        $properties = Property::orderBy('name')->get();

        // Peran yang bisa diedit (misalnya, admin tidak bisa mengubah peran pengguna lain menjadi admin via form ini)
        // Atau biarkan, tapi berhati-hati dengan logika update.
        // Untuk saat ini, kita fokus pada edit detail dan properti pengguna_properti.

        return view('admin.users.edit', compact('user', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            // Email harus unik, tapi abaikan email pengguna saat ini jika tidak diubah
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Password bersifat opsional, tapi jika diisi, harus dikonfirmasi dan memenuhi standar
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            // property_id diperlukan jika peran adalah pengguna_properti
            // Jika peran pengguna adalah 'admin', property_id bisa null atau tidak divalidasi sebagai 'required'
            'property_id' => [
                Rule::requiredIf(function () use ($user, $request) {
                    // Jika peran yang di-submit (atau peran user saat ini jika tidak ada input peran) adalah pengguna_properti
                    // Asumsi kita tidak mengubah peran via form ini untuk kesederhanaan
                    return $user->role === 'pengguna_properti';
                }),
                'nullable', // Boleh null jika bukan pengguna_properti
                'integer',
                'exists:properties,id' // Harus ada di tabel properties jika diisi
            ],
        ];

        // Pesan validasi kustom
        $messages = [
            'name.required' => 'Nama pengguna harus diisi.',
            'email.required' => 'Alamat email harus diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh pengguna lain.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'property_id.required' => 'Pengguna properti harus dikaitkan dengan sebuah properti.',
            'property_id.exists' => 'Properti yang dipilih tidak valid.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Update data dasar pengguna
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Update password hanya jika field password diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Update property_id jika pengguna adalah 'pengguna_properti'
        // Jika peran bisa diubah, logika ini perlu disesuaikan
        if ($user->role === 'pengguna_properti') {
            $user->property_id = $request->property_id; // atau $validatedData['property_id'] jika selalu ada
        } else {
            // Untuk peran lain seperti admin, property_id biasanya null
            $user->property_id = null;
        }
        // Jika ada perubahan peran, Anda perlu menangani $user->role = $request->role; di sini

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 1. Proteksi agar admin tidak bisa menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // 2. Opsional: Proteksi agar tidak bisa menghapus admin lain jika hanya ada satu admin tersisa
        //    (Ini memerlukan logika tambahan jika Anda ingin implementasi yang lebih ketat)
        // if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
        //     return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus satu-satunya akun admin yang tersisa.');
        // }

        // 3. Lakukan soft delete
        // Karena model User menggunakan trait SoftDeletes, pemanggilan delete() akan melakukan soft delete.
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna "' . $user->name . '" berhasil dinonaktifkan (dihapus sementara).');
    }

    public function trashedIndex()
    {
        // Ambil hanya pengguna yang sudah di-soft delete
        $trashedUsers = User::onlyTrashed()
                            ->with('property') // Tetap eager load properti jika ada
                            ->orderBy('deleted_at', 'desc')
                            ->paginate(15);

        return view('admin.users.trashed', compact('trashedUsers'));
    }

    /**
     * Restore a soft-deleted user.
     * @param string $id ID pengguna yang akan dipulihkan
     */
    public function restore($id) // Kita menggunakan $id karena route model binding standar tidak menemukan soft-deleted
    {
        $user = User::onlyTrashed()->findOrFail($id); // Cari hanya di antara yang soft-deleted

        // Periksa apakah email pengguna yang akan dipulihkan sudah digunakan oleh pengguna aktif lain
        // Ini penting jika ada pengguna baru dibuat dengan email yang sama setelah pengguna lama di-soft delete
        if (User::where('email', $user->email)->whereNull('deleted_at')->exists()) {
            return redirect()->route('admin.users.trashed')->with('error', 'Tidak dapat memulihkan pengguna. Alamat email "' . $user->email . '" sudah digunakan oleh pengguna aktif lain.');
        }
        
        $user->restore();

        return redirect()->route('admin.users.trashed')->with('success', 'Pengguna "' . $user->name . '" berhasil dipulihkan dan diaktifkan kembali.');
    }

}