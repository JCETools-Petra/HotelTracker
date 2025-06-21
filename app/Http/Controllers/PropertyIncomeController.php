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
    /**
     * Menampilkan dashboard untuk pengguna properti.
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

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

    /**
     * Menyimpan data pendapatan harian baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Tidak dapat menyimpan data, Anda tidak terkait dengan properti.');
        }

        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,'.$property->id,
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
            'mice_income' => 'required|numeric|min:0',
            'fnb_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah pernah dicatat.',
        ]);

        // 2. Kalkulasi nilai total berdasarkan input
        $total_rooms_sold =
            $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] +
            $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] +
            $validatedData['house_use_rooms'];

        $total_rooms_revenue =
            $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] +
            $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] +
            $validatedData['house_use_income'] + $validatedData['mice_income'];

        $total_revenue = $total_rooms_revenue + $validatedData['fnb_income'] + $validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // 3. Siapkan data yang akan dimasukkan ke database (hanya kolom yang ada)
        $incomeData = [
            'date' => $validatedData['date'],
            'property_id' => $property->id,
            'user_id' => $user->id,
            
            // Kategori pendapatan
            'offline_room_income' => $validatedData['offline_room_income'],
            'online_room_income' => $validatedData['online_room_income'],
            'ta_income' => $validatedData['ta_income'],
            'gov_income' => $validatedData['gov_income'],
            'corp_income' => $validatedData['corp_income'],
            'compliment_income' => $validatedData['compliment_income'],
            'house_use_income' => $validatedData['house_use_income'],
            'mice_income' => $validatedData['mice_income'],
            'fnb_income' => $validatedData['fnb_income'],
            'others_income' => $validatedData['others_income'],

            // Kolom hasil kalkulasi
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $validatedData['fnb_income'],
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ];

        // 4. Simpan data ke database
        DailyIncome::create($incomeData);

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

    /**
     * Menampilkan form untuk mengedit data pendapatan harian.
     */
    public function edit(DailyIncome $dailyIncome)
    {
        $user = Auth::user();

        // PERBAIKAN: Gunakan perbandingan '!=' bukan '!==' untuk fleksibilitas tipe data
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk mengedit data ini.');
        }

        $property = ($user->role === 'admin') ? $dailyIncome->property : $user->property;
        
        return view('property.income.edit', compact('dailyIncome', 'property'));
    }

    /**
     * Memperbarui data pendapatan harian di database.
     */
    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $user = Auth::user();

        // PERBAIKAN: Gunakan perbandingan '!=' bukan '!=='
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk memperbarui data ini.');
        }

        $propertyIdForValidation = $dailyIncome->property_id;

        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $propertyIdForValidation,
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
            'mice_income' => 'required|numeric|min:0',
            'fnb_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah ada.',
        ]);

        // 2. Kalkulasi ulang nilai total berdasarkan input yang diperbarui
        $total_rooms_sold =
            $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] +
            $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] +
            $validatedData['house_use_rooms'];

        $total_rooms_revenue =
            $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] +
            $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] +
            $validatedData['house_use_income'] + $validatedData['mice_income'];
        
        $property = $dailyIncome->property;
        $total_revenue = $total_rooms_revenue + $validatedData['fnb_income'] + $validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // 3. Siapkan data yang akan diperbarui
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $validatedData['fnb_income'],
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);

        // 4. Update data di database
        $dailyIncome->update($updateData);
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

    /**
     * Menghapus data pendapatan harian dari database.
     */
    public function destroy(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // PERBAIKAN: Gunakan perbandingan '!=' bukan '!=='
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
