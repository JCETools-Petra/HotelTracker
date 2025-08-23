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
<<<<<<< HEAD
    // app/Http/Controllers/Admin/IncomeController.php

=======
<<<<<<< HEAD
    // app/Http/Controllers/Admin/IncomeController.php

    public function store(Request $request, Property $property)
    {
        $this->authorize('manage-data');

        // TAMBAHKAN VALIDASI UNTUK AFILIASI
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $property->id)],
=======
>>>>>>> origin/master
    public function store(Request $request, Property $property)
    {
        $this->authorize('manage-data');

        // TAMBAHKAN VALIDASI UNTUK AFILIASI
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $property->id)],
<<<<<<< HEAD
=======
            // Validasi Jumlah Kamar
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
            'offline_rooms' => 'nullable|integer|min:0',
            'online_rooms' => 'nullable|integer|min:0',
            'ta_rooms' => 'nullable|integer|min:0',
            'gov_rooms' => 'nullable|integer|min:0',
            'corp_rooms' => 'nullable|integer|min:0',
            'compliment_rooms' => 'nullable|integer|min:0',
            'house_use_rooms' => 'nullable|integer|min:0',
<<<<<<< HEAD
            'afiliasi_rooms' => 'nullable|integer|min:0', // <-- TAMBAHKAN INI

=======
<<<<<<< HEAD
            'afiliasi_rooms' => 'nullable|integer|min:0', // <-- TAMBAHKAN INI

=======
            // Validasi Pendapatan
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
            'offline_room_income' => 'nullable|numeric|min:0',
            'online_room_income' => 'nullable|numeric|min:0',
            'ta_income' => 'nullable|numeric|min:0',
            'gov_income' => 'nullable|numeric|min:0',
            'corp_income' => 'nullable|numeric|min:0',
            'compliment_income' => 'nullable|numeric|min:0',
            'house_use_income' => 'nullable|numeric|min:0',
<<<<<<< HEAD
            'afiliasi_room_income' => 'nullable|numeric|min:0', // <-- TAMBAHKAN INI

            'mice_income' => 'nullable|numeric|min:0', 
=======
<<<<<<< HEAD
            'afiliasi_room_income' => 'nullable|numeric|min:0', // <-- TAMBAHKAN INI

            'mice_room_income' => 'nullable|numeric|min:0', 
=======
            'mice_income' => 'nullable|numeric|min:0', // MICE sekarang bisa diinput manual oleh admin
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
            'breakfast_income' => 'nullable|numeric|min:0',
            'lunch_income' => 'nullable|numeric|min:0',
            'dinner_income' => 'nullable|numeric|min:0',
            'others_income' => 'nullable|numeric|min:0',
        ]);

<<<<<<< HEAD
        // Hapus 'mice_income' dari array agar tidak coba disimpan
        $miceIncomeFromForm = $validatedData['mice_income'] ?? 0;
        unset($validatedData['mice_income']);

        // Tambahkan property_id dan user_id
=======
<<<<<<< HEAD
        // Hapus 'mice_room_income' dari array agar tidak coba disimpan
        $miceIncomeFromForm = $validatedData['mice_room_income'] ?? 0;
        unset($validatedData['mice_room_income']);

        // Tambahkan property_id dan user_id
        $validatedData['property_id'] = $property->id;
        $validatedData['user_id'] = auth()->id();

        // Buat record baru (sudah termasuk afiliasi)
        $income = DailyIncome::create($validatedData);

        // Hitung ulang total setelah data disimpan
        $income->recalculateTotals($miceIncomeFromForm);
        $income->save();

        return redirect()->route('admin.properties.show', $property)->with('success', 'Data pendapatan berhasil ditambahkan.');
=======
>>>>>>> origin/master
        $validatedData['property_id'] = $property->id;
        $validatedData['user_id'] = auth()->id();

        // Buat record baru (sudah termasuk afiliasi)
        $income = DailyIncome::create($validatedData);

<<<<<<< HEAD
        // Hitung ulang total setelah data disimpan
        $income->recalculateTotals($miceIncomeFromForm);
        $income->save();

        return redirect()->route('admin.properties.show', $property)->with('success', 'Data pendapatan berhasil ditambahkan.');
=======
        return redirect()->route('admin.properties.show', $property)->with('success', 'Data pendapatan untuk tanggal ' . $request->date . ' berhasil ditambahkan.');
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
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
<<<<<<< HEAD
        // 1. Validasi semua input, termasuk MICE yang hanya ada di form Admin
=======
<<<<<<< HEAD
        // 1. Validasi semua input, termasuk MICE yang hanya ada di form Admin
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $income->property_id)->ignore($income->id)],
            'offline_rooms' => 'nullable|integer|min:0',
            'offline_room_income' => 'nullable|numeric|min:0',
            'online_rooms' => 'nullable|integer|min:0',
            'online_room_income' => 'nullable|numeric|min:0',
            'ta_rooms' => 'nullable|integer|min:0',
            'ta_income' => 'nullable|numeric|min:0',
            'gov_rooms' => 'nullable|integer|min:0',
            'gov_income' => 'nullable|numeric|min:0',
            'corp_rooms' => 'nullable|integer|min:0',
            'corp_income' => 'nullable|numeric|min:0',
            'compliment_rooms' => 'nullable|integer|min:0',
            'compliment_income' => 'nullable|numeric|min:0',
            'house_use_rooms' => 'nullable|integer|min:0',
            'house_use_income' => 'nullable|numeric|min:0',
            'afiliasi_rooms' => 'nullable|integer|min:0',
            'afiliasi_room_income' => 'nullable|numeric|min:0',
            'mice_room_income' => 'nullable|numeric|min:0', // Admin bisa input MICE
=======
        // Validasi semua field, termasuk jumlah kamar
>>>>>>> origin/master
        $validatedData = $request->validate([
            'date' => ['required', 'date', Rule::unique('daily_incomes')->where('property_id', $income->property_id)->ignore($income->id)],
            'offline_rooms' => 'nullable|integer|min:0',
            'offline_room_income' => 'nullable|numeric|min:0',
            'online_rooms' => 'nullable|integer|min:0',
            'online_room_income' => 'nullable|numeric|min:0',
            'ta_rooms' => 'nullable|integer|min:0',
            'ta_income' => 'nullable|numeric|min:0',
            'gov_rooms' => 'nullable|integer|min:0',
            'gov_income' => 'nullable|numeric|min:0',
            'corp_rooms' => 'nullable|integer|min:0',
            'corp_income' => 'nullable|numeric|min:0',
            'compliment_rooms' => 'nullable|integer|min:0',
            'compliment_income' => 'nullable|numeric|min:0',
            'house_use_rooms' => 'nullable|integer|min:0',
            'house_use_income' => 'nullable|numeric|min:0',
<<<<<<< HEAD
            'afiliasi_rooms' => 'nullable|integer|min:0',
            'afiliasi_room_income' => 'nullable|numeric|min:0',
            'mice_income' => 'nullable|numeric|min:0', // Admin bisa input MICE
=======
            'mice_income' => 'nullable|numeric|min:0',
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
            'breakfast_income' => 'nullable|numeric|min:0',
            'lunch_income' => 'nullable|numeric|min:0',
            'dinner_income' => 'nullable|numeric|min:0',
            'others_income' => 'nullable|numeric|min:0',
        ]);

<<<<<<< HEAD
        // ======================= AWAL PERUBAHAN =======================

        // Ambil nilai MICE dari form untuk kalkulasi, tapi jangan disimpan
        $miceIncomeFromForm = $validatedData['mice_income'] ?? 0;
        // Hapus 'mice_income' dari array agar tidak coba disimpan ke database
        unset($validatedData['mice_income']);

        // ======================= AKHIR PERUBAHAN =======================

        // 2. Kalkulasi ulang total
        $property = $income->property;
        $total_rooms_sold =
            ($validatedData['offline_rooms'] ?? 0) + ($validatedData['online_rooms'] ?? 0) + ($validatedData['ta_rooms'] ?? 0) +
            ($validatedData['gov_rooms'] ?? 0) + ($validatedData['corp_rooms'] ?? 0) + ($validatedData['compliment_rooms'] ?? 0) +
            ($validatedData['house_use_rooms'] ?? 0) + ($validatedData['afiliasi_rooms'] ?? 0);

        $total_rooms_revenue =
            ($validatedData['offline_room_income'] ?? 0) + ($validatedData['online_room_income'] ?? 0) + ($validatedData['ta_income'] ?? 0) +
            ($validatedData['gov_income'] ?? 0) + ($validatedData['corp_income'] ?? 0) + ($validatedData['compliment_income'] ?? 0) +
            ($validatedData['house_use_income'] ?? 0) + ($validatedData['afiliasi_room_income'] ?? 0);

        $total_fb_revenue = ($validatedData['breakfast_income'] ?? 0) + ($validatedData['lunch_income'] ?? 0) + ($validatedData['dinner_income'] ?? 0);
        
        // Gunakan nilai MICE dari form dalam perhitungan total revenue
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + $miceIncomeFromForm + ($validatedData['others_income'] ?? 0);
        
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // Data yang akan diupdate ke tabel daily_incomes (sudah tanpa mice_income)
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);

        $income->update($updateData);

        return redirect()->route('admin.properties.show', $income->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
=======
<<<<<<< HEAD
        // ======================= AWAL PERUBAHAN =======================

        // Ambil nilai MICE dari form untuk kalkulasi, tapi jangan disimpan
        $miceIncomeFromForm = $validatedData['mice_room_income'] ?? 0;
        // Hapus 'mice_room_income' dari array agar tidak coba disimpan ke database
        unset($validatedData['mice_room_income']);

        // ======================= AKHIR PERUBAHAN =======================

        // 2. Kalkulasi ulang total
        $property = $income->property;
        $total_rooms_sold =
            ($validatedData['offline_rooms'] ?? 0) + ($validatedData['online_rooms'] ?? 0) + ($validatedData['ta_rooms'] ?? 0) +
            ($validatedData['gov_rooms'] ?? 0) + ($validatedData['corp_rooms'] ?? 0) + ($validatedData['compliment_rooms'] ?? 0) +
            ($validatedData['house_use_rooms'] ?? 0) + ($validatedData['afiliasi_rooms'] ?? 0);

        $total_rooms_revenue =
            ($validatedData['offline_room_income'] ?? 0) + ($validatedData['online_room_income'] ?? 0) + ($validatedData['ta_income'] ?? 0) +
            ($validatedData['gov_income'] ?? 0) + ($validatedData['corp_income'] ?? 0) + ($validatedData['compliment_income'] ?? 0) +
            ($validatedData['house_use_income'] ?? 0) + ($validatedData['afiliasi_room_income'] ?? 0);

        $total_fb_revenue = ($validatedData['breakfast_income'] ?? 0) + ($validatedData['lunch_income'] ?? 0) + ($validatedData['dinner_income'] ?? 0);
        
        // Gunakan nilai MICE dari form dalam perhitungan total revenue
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + $miceIncomeFromForm + ($validatedData['others_income'] ?? 0);
        
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // Data yang akan diupdate ke tabel daily_incomes (sudah tanpa mice_room_income)
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);

        $income->update($updateData);

        return redirect()->route('admin.properties.show', $income->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
=======
        $income->update($validatedData);

        return redirect()->route('admin.properties.show', $income->property_id)->with('success', 'Data pendapatan untuk tanggal ' . $request->date . ' berhasil diperbarui.');
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
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