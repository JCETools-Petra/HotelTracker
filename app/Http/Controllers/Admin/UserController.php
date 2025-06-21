<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property; // <-- TAMBAHKAN IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil user dengan data propertinya jika ada
        $users = User::with('property')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // PERUBAHAN: Mengambil semua properti untuk ditampilkan di dropdown
        $properties = Property::orderBy('name')->get();
        return view('admin.users.create', compact('properties'));
    }

    public function store(Request $request)
    {
        // PERUBAHAN: Menambahkan validasi untuk property_id
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // property_id wajib diisi jika rolenya adalah pengguna_properti
            'property_id' => ['nullable', 'required_if:role,pengguna_properti', 'exists:properties,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            // PERUBAHAN: Menyimpan property_id
            'property_id' => $request->role == 'pengguna_properti' ? $request->property_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        // PERUBAHAN: Mengambil semua properti untuk form edit
        $properties = Property::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'properties'));
    }

    public function update(Request $request, User $user)
    {
        // PERUBAHAN: Menambahkan validasi untuk property_id
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'property_id' => ['nullable', 'required_if:role,pengguna_properti', 'exists:properties,id'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            // PERUBAHAN: Mengupdate property_id
            'property_id' => $request->role == 'pengguna_properti' ? $request->property_id : null,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->with('property')->latest()->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.users.trashed')->with('success', 'User berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.users.trashed')->with('success', 'User berhasil dihapus permanen.');
    }
}
