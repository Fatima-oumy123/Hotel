<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::with('roomType')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->room_type_id, fn($q) => $q->where('room_type_id', $request->room_type_id))
            ->when($request->search, fn($q) => $q->where('number', 'like', "%{$request->search}%"))
            ->orderBy('number')
            ->paginate(20);

        $roomTypes = RoomType::all();
        $stats = [
            'total'       => Room::count(),
            'available'   => Room::where('status', 'available')->count(),
            'occupied'    => Room::where('status', 'occupied')->count(),
            'reserved'    => Room::where('status', 'reserved')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        return view('rooms.index', compact('rooms', 'roomTypes', 'stats'));
    }

    public function create()
    {
        $roomTypes = RoomType::orderBy('name')->get();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number'       => 'required|string|max:10|unique:rooms,number',
            'room_type_id' => 'required|exists:room_types,id',
            'floor'        => 'required|integer|min:0|max:50',
            'status'       => 'required|in:available,reserved,occupied,maintenance',
            'notes'        => 'nullable|string|max:500',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', "Chambre {$request->number} créée avec succès.");
    }

    public function show(Room $room)
    {
        $room->load(['roomType', 'reservations' => fn($q) => $q->latest()->take(10), 'maintenanceTickets' => fn($q) => $q->latest()->take(5)]);
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::orderBy('name')->get();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'number'       => 'required|string|max:10|unique:rooms,number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'floor'        => 'required|integer|min:0|max:50',
            'status'       => 'required|in:available,reserved,occupied,maintenance',
            'notes'        => 'nullable|string|max:500',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', "Chambre {$room->number} mise à jour.");
    }

    public function destroy(Room $room)
    {
        if ($room->reservations()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return back()->withErrors(['error' => 'Impossible de supprimer une chambre avec des réservations actives.']);
        }
        $room->delete();
        return redirect()->route('rooms.index')->with('success', "Chambre supprimée.");
    }

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate(['status' => 'required|in:available,reserved,occupied,maintenance']);
        $room->update(['status' => $request->status]);
        return response()->json(['success' => true, 'status' => $room->status]);
    }
}
