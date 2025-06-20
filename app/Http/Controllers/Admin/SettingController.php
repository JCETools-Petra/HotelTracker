<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua setting dan ubah menjadi array asosiatif (key => value)
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_size' => 'nullable|integer|min:20|max:200',
        ]);

        // Proses upload logo jika ada file baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            $oldLogo = Setting::where('key', 'logo_path')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }
            // Simpan logo baru dan dapatkan path-nya
            $path = $request->file('logo')->store('branding', 'public');
            // Simpan path ke database
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }
        
        // Simpan ukuran logo jika diisi
        if ($request->filled('logo_size')) {
            Setting::updateOrCreate(['key' => 'logo_size'], ['value' => $request->logo_size]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}