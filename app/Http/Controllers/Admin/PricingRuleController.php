<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    /**
     * Menampilkan form untuk mengedit atau membuat aturan harga.
     */
    public function edit(Property $property)
    {
        // Mengambil aturan harga yang ada, atau membuat yang baru jika belum ada.
        // Ini memastikan halaman tidak akan error saat pertama kali dibuka.
        $rule = $property->pricingRule()->firstOrCreate(
            ['property_id' => $property->id], // Kunci untuk mencari
            [ // Data default jika baru dibuat
                'publish_rate' => 0, 
                'bottom_rate' => 0, 
                'tier_limit' => 1, 
                'percentage_increase' => 0
            ]
        );

        return view('admin.pricing_rules.edit', compact('property', 'rule'));
    }

    /**
     * Menyimpan perubahan aturan harga ke database.
     */
    public function update(Request $request, Property $property)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'publish_rate' => 'required|numeric|min:0',
            'bottom_rate' => 'required|numeric|min:0|lte:publish_rate', // Bottom rate tidak boleh > publish rate
            'tier_limit' => 'required|integer|min:1',
            'percentage_increase' => 'required|numeric|min:0|max:100',
        ]);

        // Update data di database
        $property->pricingRule()->update($validated);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Aturan harga berhasil diperbarui!');
    }
}