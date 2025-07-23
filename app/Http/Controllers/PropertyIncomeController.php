<?php

namespace App\Http\Controllers;

use App\Models\DailyIncome;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertyIncomesExport;
use Illuminate\Support\Str;

class PropertyIncomeController extends Controller
{
    // ... (metode dashboard dan index tidak berubah)

    /**
     * Menampilkan dashboard untuk pengguna properti.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun atau properti tidak ditemukan.');
        }

        $todayIncome = DailyIncome::where('property_id', $property->id)
            ->whereDate('date', Carbon::today())
            ->first();

        return view('property.dashboard', compact('property', 'todayIncome'));
    }

    /**
     * Menampilkan daftar riwayat pendapatan harian.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DailyIncome::where('property_id', $property->id);

        if ($startDate) {
            try {
                $query->whereDate('date', '>=', Carbon::parse($startDate));
            } catch (\Exception $e) {}
        }
        if ($endDate) {
            try {
                $query->whereDate('date', '<=', Carbon::parse($endDate));
            } catch (\Exception $e) {}
        }

        $incomes = $query->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('property.income.index', compact('incomes', 'property', 'startDate', 'endDate'));
    }

    /**
     * Menampilkan form untuk membuat data pendapatan harian baru.
     */
    public function create()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        return view('property.income.create', [
            'property' => $property,
            'date' => old('date', Carbon::today()->toDateString())
        ]);
    }

    // app/Http/Controllers/PropertyIncomeController.php

    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;
        if (!$property) {
            abort(403);
        }
    
        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,' . $property->id,
            'offline_rooms' => 'required|integer|min:0',
            'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0',
            'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0',
            'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0',
            'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0',
            'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0',
            'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0',
            'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0',
            'afiliasi_room_income' => 'required|numeric|min:0',
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah pernah dicatat.',
        ]);
    
        // ======================= AWAL PERBAIKAN KALKULASI =======================
    
        // 2. Kalkulasi semua nilai total dengan memastikan tipe data adalah numerik
        $total_rooms_sold =
            (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] +
            (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] +
            (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
    
        $total_rooms_revenue =
            (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] +
            (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] +
            (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
    
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
    
        // Ini adalah baris kunci: menjumlahkan SEMUA komponen pendapatan
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
    
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
        // 3. Siapkan data untuk disimpan
        $incomeData = array_merge($validatedData, [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
    
        // ======================= AKHIR PERBAIKAN KALKULASI =======================
    
        DailyIncome::create($incomeData);
    
        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

    public function edit(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk mengedit data ini.');
        }
        $property = $user->property;
        return view('property.income.edit', compact('dailyIncome', 'property'));
    }
    
    // app/Http/Controllers/PropertyIncomeController.php

    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk memperbarui data ini.');
        }
    
        // Validasi input
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $dailyIncome->property_id,
            'offline_rooms' => 'required|integer|min:0', 'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0', 'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0', 'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0', 'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0', 'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0', 'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0', 'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0', 'afiliasi_room_income' => 'required|numeric|min:0',
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah ada.',
        ]);
    
        // ======================= AWAL PERBAIKAN KALKULASI =======================
    
        // Kalkulasi ulang dengan memastikan tipe data numerik
        $property = $dailyIncome->property;
        $total_rooms_sold =
            (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] +
            (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] +
            (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
    
        $total_rooms_revenue =
            (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] +
            (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] +
            (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
    
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
        
        // Total revenue kalkulasi
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
    
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
        
        // ======================= AKHIR PERBAIKAN KALKULASI =======================
    
        $dailyIncome->update($updateData);
    
        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }
    
        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

    // ... (metode destroy dan export tidak berubah)
    /**
     * Menghapus data pendapatan harian dari database.
     */
    public function destroy(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk menghapus data ini.');
        }

        $originalDate = $dailyIncome->date;
        $dailyIncome->delete();

        if ($user->role === 'admin') {
            return back()->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
    }

    /**
     * Mengekspor data pendapatan ke Excel.
     */
    public function exportIncomesExcel(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName);
    }

    /**
     * Mengekspor data pendapatan ke CSV.
     */
    public function exportIncomesCsv(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.csv';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}