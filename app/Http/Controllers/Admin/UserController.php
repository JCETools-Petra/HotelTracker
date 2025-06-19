<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna. (Bisa diakses admin & owner)
     */
    public function index()
    {
        $users = User::with('property')
                     ->orderBy('name', 'asc')
                     ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru. (HANYA ADMIN)
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa membuat pengguna baru.');
        }

        $properties = Property::orderBy('name')->get();
        
        // Definisikan peran yang bisa dipilih oleh admin
        $roles = [
            'admin' => 'Admin',
            'owner' => 'Owner',
            'pengguna_properti' => 'Pengguna Properti'
        ];

        return view('admin.users.create', compact('properties', 'roles'));
    }

    /**
     * Menyimpan pengguna baru ke database. (HANYA ADMIN)
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa membuat pengguna baru.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'owner', 'pengguna_properti'])],
            // Properti hanya wajib jika perannya adalah pengguna properti
            'property_id' => ['nullable', 'required_if:role,pengguna_properti', 'exists:properties,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Set property_id ke null jika peran bukan pengguna_properti
            'property_id' => $request->role === 'pengguna_properti' ? $request->property_id : null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pengguna. (HANYA ADMIN)
     */
    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa mengedit pengguna.');
        }

        $properties = Property::orderBy('name')->get();
        
        $roles = [
            'admin' => 'Admin',
            'owner' => 'Owner',
            'pengguna_properti' => 'Pengguna Properti'
        ];

        return view('admin.users.edit', compact('user', 'properties', 'roles'));
    }

    /**
     * Memperbarui pengguna di database. (HANYA ADMIN)
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa memperbarui pengguna.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'owner', 'pengguna_properti'])],
            'property_id' => ['nullable', 'required_if:role,pengguna_properti', 'exists:properties,id'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->property_id = $request->role === 'pengguna_properti' ? $request->property_id : null;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Melakukan soft delete pada pengguna. (HANYA ADMIN)
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa menghapus pengguna.');
        }
        
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna "' . $user->name . '" berhasil dinonaktifkan.');
    }

    /**
     * Menampilkan daftar pengguna yang sudah di-soft delete. (Bisa diakses admin & owner)
     */
    public function trashedIndex()
    {
        $trashedUsers = User::onlyTrashed()
                            ->with('property')
                            ->orderBy('deleted_at', 'desc')
                            ->paginate(15);

        return view('admin.users.trashed', compact('trashedUsers'));
    }

    /**
     * Memulihkan pengguna yang di-soft delete. (HANYA ADMIN)
     */
    public function restore($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa memulihkan pengguna.');
        }
        
        $user = User::onlyTrashed()->findOrFail($id);
        
        if (User::where('email', $user->email)->whereNull('deleted_at')->exists()) {
            return redirect()->route('admin.users.trashed')->with('error', 'Tidak dapat memulihkan pengguna. Alamat email "' . $user->email . '" sudah digunakan oleh pengguna aktif lain.');
        }
        
        $user->restore();

        return redirect()->route('admin.users.trashed')->with('success', 'Pengguna "' . $user->name . '" berhasil dipulihkan.');
    }

    /**
     * Menghapus pengguna secara permanen dari database. (HANYA ADMIN)
     */
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin yang bisa menghapus permanen.');
        }
        
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users.trashed')->with('success', 'Pengguna berhasil dihapus secara permanen.');
    }
}