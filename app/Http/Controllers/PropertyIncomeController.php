<?php

namespace App\Http\Controllers;

use App\Models\DailyIncome;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Untuk manipulasi tanggal dan waktu
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertyIncomesExport;

class PropertyIncomeController extends Controller
{
    /**
     * Menampilkan dashboard untuk pengguna properti.
     * Menampilkan properti pengguna dan data pendapatan hari ini jika ada.
     */
    public function dashboard()
    {
        $user = Auth::user();
        // Seharusnya tidak terjadi jika rute dilindungi middleware 'auth', tapi baik untuk defensive coding
        if (!$user) {
            return redirect()->route('login');
        }

        $property = $user->property; // Mengambil properti terkait melalui relasi di model User

        if (!$property) {
            // Jika pengguna dengan peran properti tidak memiliki property_id yang valid,
            // atau properti dengan id tersebut tidak ditemukan.
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun atau properti tidak ditemukan.');
        }

        // Data untuk dashboard pengguna properti, misal entri hari ini
        $todayIncome = DailyIncome::where('property_id', $property->id)
                                    ->whereDate('date', Carbon::today())
                                    ->first();

        return view('property.dashboard', compact('property', 'todayIncome'));
    }

    /**
     * Menampilkan daftar riwayat pendapatan harian untuk properti pengguna.
     * Mendukung filter berdasarkan rentang tanggal.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        // Ambil input filter tanggal dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DailyIncome::where('property_id', $property->id);

        if ($startDate) {
            try {
                // Terapkan filter tanggal mulai jika ada dan valid
                $query->whereDate('date', '>=', Carbon::parse($startDate));
            } catch (\Exception $e) {
                // Abaikan jika format tanggal tidak valid, atau bisa juga tambahkan pesan error
                // Log::warning('Format tanggal mulai tidak valid: ' . $startDate);
            }
        }
        if ($endDate) {
            try {
                // Terapkan filter tanggal selesai jika ada dan valid
                $query->whereDate('date', '<=', Carbon::parse($endDate));
            } catch (\Exception $e) {
                // Abaikan jika format tanggal tidak valid
                // Log::warning('Format tanggal selesai tidak valid: ' . $endDate);
            }
        }

        // Ambil data pendapatan dengan paginasi dan urutkan berdasarkan tanggal terbaru
        // withQueryString() untuk mempertahankan parameter filter (start_date, end_date) di link paginasi
        $incomes = $query->orderBy('date', 'desc')
                        ->paginate(10) // Misalnya 10 entri per halaman
                        ->withQueryString();

        return view('property.income.index', compact('incomes', 'property', 'startDate', 'endDate'));
    }

    /**
     * Menampilkan form untuk membuat data pendapatan harian baru.
     * Pengguna bisa memilih tanggal yang diinginkan.
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
     * Validasi akan memastikan tanggal unik per properti.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Tidak dapat menyimpan data, Anda tidak terkait dengan properti.');
        }

        // Tambahkan validasi untuk semua field baru
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

        $incomeData = $validatedData;
        $incomeData['property_id'] = $property->id;
        $incomeData['user_id'] = $user->id;

        DailyIncome::create($incomeData);

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

    /**
     * Menampilkan form untuk mengedit data pendapatan harian yang sudah ada.
     */
    public function edit(DailyIncome $dailyIncome)
    {
        $user = Auth::user();

        // PERBAIKAN LOGIKA OTORISASI
        if ($user->role !== 'admin' && $user->property_id !== $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk mengedit data ini.');
        }

        // Untuk admin, properti diambil dari data income, untuk user, dari user itu sendiri
        $property = ($user->role === 'admin') ? $dailyIncome->property : $user->property;
        
        return view('property.income.edit', compact('dailyIncome', 'property'));
    }

    /**
     * Memperbarui data pendapatan harian yang sudah ada di database.
     */
    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $user = Auth::user();

        // PERBAIKAN LOGIKA OTORISASI
        if ($user->role !== 'admin' && $user->property_id !== $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk memperbarui data ini.');
        }

        // Ambil ID properti dari data income yang akan diupdate, bukan dari user (untuk admin)
        $propertyIdForValidation = $dailyIncome->property_id;

        // Tambahkan validasi untuk semua field baru
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

        $dailyIncome->update($validatedData);
        
        // Arahkan admin kembali ke halaman detail properti yang relevan setelah update
        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

    /**
     * Menghapus data pendapatan harian dari database.
     */
    public function destroy(DailyIncome $dailyIncome) // Menggunakan Route Model Binding
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // PERBAIKAN LOGIKA OTORISASI
        if ($user->role !== 'admin' && $user->property_id !== $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk menghapus data ini.');
        }

        $originalDate = $dailyIncome->date; // Simpan tanggal sebelum dihapus untuk pesan
        $dailyIncome->delete();

        // Jika admin, kembalikan ke halaman sebelumnya (kemungkinan halaman detail properti)
        if ($user->role === 'admin') {
            return back()->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
    }

    public function exportIncomesExcel(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date'); // Ambil dari query parameter
        $endDate = $request->query('end_date');     // Ambil dari query parameter

        $fileName = 'laporan_pendapatan_' . $user->property->name . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName);
    }

    /**
     * Menangani permintaan ekspor data pendapatan ke CSV.
     */
    public function exportIncomesCsv(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date'); // Ambil dari query parameter
        $endDate = $request->query('end_date');     // Ambil dari query parameter

        $fileName = 'laporan_pendapatan_' . $user->property->name . '_' . Carbon::now()->format('Ymd_His') . '.csv';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}