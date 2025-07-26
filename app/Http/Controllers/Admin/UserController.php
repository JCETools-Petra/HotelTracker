<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('property')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::orderBy('name')->get();
        
        // ## PERUBAHAN 1: Tambahkan 'online_ecommerce' ke daftar peran ##
        $roles = [
            'admin' => 'Admin',
            'owner' => 'Owner',
            'pengguna_properti' => 'Pengguna Properti',
            'sales' => 'Sales',
            'online_ecommerce' => 'Online Ecommerce', // <-- DITAMBAHKAN
        ];

        // Kirim variabel $roles ke view
        return view('admin.users.create', compact('properties', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-data');

        // ## PERUBAHAN 2: Tambahkan 'online_ecommerce' ke validasi ##
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', \Illuminate\Validation\Rule::in(['admin', 'owner', 'pengguna_properti', 'sales', 'online_ecommerce'])],
            'property_id' => ['nullable', 'required_if:role,pengguna_properti,sales,online_ecommerce', 'exists:properties,id'],
        ]);

        $data = $request->only('name', 'email', 'role', 'property_id');
        $data['password'] = Hash::make($request->password);

        // Pastikan property_id null jika peran tidak membutuhkannya
        if ($request->role === 'admin' || $request->role === 'owner') {
            $data['property_id'] = null;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $properties = Property::orderBy('name')->get();

        // ## PERUBAHAN 3: Tambahkan 'online_ecommerce' ke daftar peran ##
        $roles = [
            'admin' => 'Admin',
            'owner' => 'Owner',
            'pengguna_properti' => 'Pengguna Properti',
            'sales' => 'Sales',
            'online_ecommerce' => 'Online Ecommerce', // <-- DITAMBAHKAN
        ];

        // Kirim variabel $roles ke view
        return view('admin.users.edit', compact('user', 'properties', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('manage-data');

        // ## PERUBAHAN 4: Tambahkan 'online_ecommerce' ke validasi ##
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', \Illuminate\Validation\Rule::in(['admin', 'owner', 'pengguna_properti', 'sales', 'online_ecommerce'])],
            'property_id' => ['nullable', 'required_if:role,pengguna_properti,sales,online_ecommerce', 'exists:properties,id'],
        ]);

        $data = $request->only('name', 'email', 'role', 'property_id');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Pastikan property_id null jika peran tidak membutuhkannya
        if ($request->role === 'admin' || $request->role === 'owner') {
            $data['property_id'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('manage-data');
        if ($user->id === 1) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin tidak dapat dihapus.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dipindahkan ke sampah.');
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashedIndex()
    {
        $users = User::onlyTrashed()->with('property')->latest()->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.users.trashed')->with('success', 'Pengguna berhasil dipulihkan.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.users.trashed')->with('success', 'Pengguna berhasil dihapus permanen.');
    }
}