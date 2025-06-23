<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // Pastikan Cache di-import

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
        // ======================= AWAL BLOK YANG DIUBAH =======================
        
        // 1. Tambahkan validasi untuk semua input
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_size' => 'nullable|integer|min:20|max:200',
            'sidebar_logo_size' => 'nullable|integer|min:20|max:200',
        ]);

        // 2. Tambahkan logika untuk menyimpan Nama Aplikasi
        if ($request->filled('app_name')) {
            Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        }

        // Proses upload logo jika ada file baru
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::where('key', 'logo_path')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }
        
        // Simpan ukuran logo login jika diisi
        if ($request->filled('logo_size')) {
            Setting::updateOrCreate(['key' => 'logo_size'], ['value' => $request->logo_size]);
        }

        // Simpan ukuran logo sidebar jika diisi
        if ($request->filled('sidebar_logo_size')) {
            Setting::updateOrCreate(['key' => 'sidebar_logo_size'], ['value' => $request->sidebar_logo_size]);
        }
        // ======================= AKHIR BLOK YANG DIUBAH ======================

        // Hapus cache agar perubahan langsung terlihat di seluruh aplikasi
        Cache::forget('app_settings');

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}