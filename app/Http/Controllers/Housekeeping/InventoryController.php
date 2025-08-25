<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\HotelRoom;
use App\Models\HkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index()
    {
        $propertyId = Auth::user()->property_id;
        $rooms = HotelRoom::where('property_id', $propertyId)
            ->whereHas('roomType', function ($query) {
                $query->where('type', 'hotel');
            })
            ->with('roomType')
            ->get();

        $todayAssignmentsCount = HkAssignment::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->count();
        
        $canAssign = $todayAssignmentsCount < 2;

        return view('housekeeping.inventory.index', compact('rooms', 'canAssign'));
    }

    public function selectRoom(Request $request)
    {
        $request->validate(['room_id' => 'required|exists:hotel_rooms,id']);
        return redirect()->route('housekeeping.inventory.assign', $request->room_id);
    }

    public function assign(HotelRoom $room)
    {
        if (Auth::user()->property_id !== $room->property_id) {
            abort(403, 'Anda tidak diizinkan untuk mengakses kamar ini.');
        }

        $todayAssignmentsCount = HkAssignment::where('user_id', Auth::id())
                                              ->whereDate('created_at', Carbon::today())
                                              ->count();
        
        if ($todayAssignmentsCount >= 2) {
            return redirect()->route('housekeeping.inventory.index')
                             ->with('error', 'Anda sudah mencapai batas maksimum 2x input hari ini.');
        }

        $inventories = Inventory::where('category', 'ROOM AMENITIES')
                                ->orderBy('name')
                                ->get();
        
        $currentAmenities = $room->amenities()->get()->keyBy('id');

        return view('housekeeping.inventory.assign', compact('room', 'inventories', 'currentAmenities'));
    }

    public function updateInventory(Request $request, HotelRoom $room)
    {
        if (Auth::user()->property_id !== $room->property_id) {
            abort(403, 'Anda tidak diizinkan untuk memperbarui kamar ini.');
        }

        $request->validate([
            'amenities' => 'required|array',
            'amenities.*.quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $room) {
                $amenitiesToSync = [];
                $inputAmenities = $request->input('amenities', []);
                $currentAmenities = $room->amenities()->get()->keyBy('id');

                foreach ($inputAmenities as $inventoryId => $data) {
                    $quantity = (int) $data['quantity'];
                    $currentQuantity = $currentAmenities->get($inventoryId)->pivot->quantity ?? 0;
                    $quantityDifference = $quantity - $currentQuantity;
                    
                    if ($quantityDifference > 0) {
                        // Jika jumlah bertambah, kurangi stok
                        $inventoryItem = Inventory::findOrFail($inventoryId);
                        if ($inventoryItem->quantity < $quantityDifference) {
                            throw ValidationException::withMessages([
                                'amenities.' . $inventoryId . '.quantity' => "Stok untuk {$inventoryItem->name} tidak mencukupi. Sisa stok: {$inventoryItem->quantity}",
                            ]);
                        }
                        $inventoryItem->decrement('quantity', $quantityDifference);
                    } elseif ($quantityDifference < 0) {
                        // Jika jumlah berkurang, kembalikan stok
                        $inventoryItem = Inventory::findOrFail($inventoryId);
                        $inventoryItem->increment('quantity', abs($quantityDifference));
                    }
                    
                    if ($quantity > 0) {
                        $amenitiesToSync[$inventoryId] = ['quantity' => $quantity];
                    }
                }
                $room->amenities()->sync($amenitiesToSync);

                HkAssignment::create([
                    'user_id' => Auth::id(),
                    'room_id' => $room->id,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('housekeeping.inventory.index')->with('success', 'Inventaris untuk kamar ' . $room->room_number . ' berhasil diperbarui.');
    }

    public function history()
    {
        $userId = Auth::id();

        // Mengambil semua riwayat penugasan untuk user yang login,
        // dengan eager loading data kamar dan amenities yang ditugaskan.
        $history = HkAssignment::where('user_id', $userId)
                                ->with(['room' => function ($query) {
                                    // Ambil data kamar dan amenities yang ditugaskan
                                    $query->with('amenities');
                                }])
                                ->latest()
                                ->get();

        return view('housekeeping.history.index', compact('history'));
    }
}