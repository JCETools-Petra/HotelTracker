<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar ruangan untuk sebuah properti.
     */
    public function index(Property $property)
    {
        $rooms = $property->rooms()->orderBy('name')->get();
        return view('admin.rooms.index', compact('property', 'rooms'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function create(Property $property)
    {
        return view('admin.rooms.create', compact('property'));
    }

    /**
     * Menyimpan ruangan baru ke database.
     */
    public function store(Request $request, Property $property)
    {
<<<<<<< HEAD
        $this->authorize('manage-data');
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $property->rooms()->create($validated);

        return redirect()->route('admin.properties.rooms.index', $property)
                         ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit ruangan.
     */
    public function edit(Room $room)
    {
        $property = $room->property;
        return view('admin.rooms.edit', compact('room', 'property'));
    }

    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('admin.properties.rooms.index', $room->property)
                         ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus ruangan dari database.
     */
    public function destroy(Room $room)
    {
        $property = $room->property;
        $room->delete();

        return redirect()->route('admin.properties.rooms.index', $property)
                         ->with('success', 'Ruangan berhasil dihapus.');
    }
}
