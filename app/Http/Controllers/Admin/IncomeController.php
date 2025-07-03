<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyIncome;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IncomeController extends Controller
{
    /**
     * Menampilkan form untuk membuat data pendapatan baru.
     */
    public function create(Property $property)
    {
        return view('admin.incomes.create', [
            'property' => $property,
            'date' => now()->toDateString(), // Kirim tanggal hari ini ke view
        ]);
    }

    /**
     * Menyimpan data pendapatan baru ke database.
     */
    public function store(Request $request, Property $property)
    {
        // Validasi semua field, termasuk jumlah kamar
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $property->id)],
            // Validasi Jumlah Kamar
            'offline_rooms' => 'nullable|integer|min:0',
            'online_rooms' => 'nullable|integer|min:0',
            'ta_rooms' => 'nullable|integer|min:0',
            'gov_rooms' => 'nullable|integer|min:0',
            'corp_rooms' => 'nullable|integer|min:0',
            'compliment_rooms' => 'nullable|integer|min:0',
            'house_use_rooms' => 'nullable|integer|min:0',
            // Validasi Pendapatan
            'offline_room_income' => 'nullable|numeric|min:0',
            'online_room_income' => 'nullable|numeric|min:0',
            'ta_income' => 'nullable|numeric|min:0',
            'gov_income' => 'nullable|numeric|min:0',
            'corp_income' => 'nullable|numeric|min:0',
            'compliment_income' => 'nullable|numeric|min:0',
            'house_use_income' => 'nullable|numeric|min:0',
            'mice_income' => 'nullable|numeric|min:0', // MICE sekarang bisa diinput manual oleh admin
            'breakfast_income' => 'nullable|numeric|min:0',
            'lunch_income' => 'nullable|numeric|min:0',
            'dinner_income' => 'nullable|numeric|min:0',
            'others_income' => 'nullable|numeric|min:0',
        ]);

        $validatedData['property_id'] = $property->id;
        $validatedData['user_id'] = auth()->id();

        DailyIncome::create($validatedData);

        return redirect()->route('admin.properties.show', $property)->with('success', 'Data pendapatan untuk tanggal ' . $request->date . ' berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pendapatan.
     */
    public function edit(DailyIncome $income)
    {
        $income->load('property');
        return view('admin.incomes.edit', [
            'income' => $income,
            'date' => \Carbon\Carbon::parse($income->date)->toDateString(), // Kirim tanggal yang ada ke view
        ]);
    }

    /**
     * Memperbarui data pendapatan di database.
     */
    public function update(Request $request, DailyIncome $income)
    {
        // Validasi semua field, termasuk jumlah kamar
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $income->property_id)->ignore($income->id)],
             // Validasi Jumlah Kamar
            'offline_rooms' => 'nullable|integer|min:0',
            'online_rooms' => 'nullable|integer|min:0',
            'ta_rooms' => 'nullable|integer|min:0',
            'gov_rooms' => 'nullable|integer|min:0',
            'corp_rooms' => 'nullable|integer|min:0',
            'compliment_rooms' => 'nullable|integer|min:0',
            'house_use_rooms' => 'nullable|integer|min:0',
            // Validasi Pendapatan
            'offline_room_income' => 'nullable|numeric|min:0',
            'online_room_income' => 'nullable|numeric|min:0',
            'ta_income' => 'nullable|numeric|min:0',
            'gov_income' => 'nullable|numeric|min:0',
            'corp_income' => 'nullable|numeric|min:0',
            'compliment_income' => 'nullable|numeric|min:0',
            'house_use_income' => 'nullable|numeric|min:0',
            'mice_income' => 'nullable|numeric|min:0',
            'breakfast_income' => 'nullable|numeric|min:0',
            'lunch_income' => 'nullable|numeric|min:0',
            'dinner_income' => 'nullable|numeric|min:0',
            'others_income' => 'nullable|numeric|min:0',
        ]);

        $income->update($validatedData);

        return redirect()->route('admin.properties.show', $income->property_id)->with('success', 'Data pendapatan untuk tanggal ' . $request->date . ' berhasil diperbarui.');
    }

    /**
     * Menghapus data pendapatan.
     */
    public function destroy(DailyIncome $income)
    {
        $property = $income->property;
        $income->delete();
        
        return redirect()->route('admin.properties.show', $property)
                         ->with('success', 'Data pendapatan berhasil dihapus.');
    }
}