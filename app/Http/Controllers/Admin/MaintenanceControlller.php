<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTicket;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $tickets = MaintenanceTicket::with(['room', 'reporter', 'assignee'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->room_id, fn($q) => $q->where('room_id', $request->room_id))
            ->latest()
            ->paginate(20);

        $rooms = Room::orderBy('number')->get();
        $stats = [
            'pending'     => MaintenanceTicket::where('status', 'pending')->count(),
            'in_progress' => MaintenanceTicket::where('status', 'in_progress')->count(),
            'completed'   => MaintenanceTicket::where('status', 'completed')->count(),
            'urgent'      => MaintenanceTicket::where('priority', 'urgent')->whereIn('status', ['pending', 'in_progress'])->count(),
        ];

        return view('maintenance.index', compact('tickets', 'rooms', 'stats'));
    }

    public function create()
    {
        $rooms = Room::orderBy('number')->get();
        $technicians = User::role(['manager', 'receptionist'])->get();
        return view('maintenance.create', compact('rooms', 'technicians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'title'       => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'priority'    => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = MaintenanceTicket::create([
            ...$request->all(),
            'reported_by' => auth()->id(),
            'status'      => 'pending',
        ]);

        // Mettre la chambre en maintenance si urgent
        if ($request->priority === 'urgent') {
            Room::find($request->room_id)->update(['status' => 'maintenance']);
        }

        return redirect()->route('maintenance.show', $ticket)->with('success', 'Ticket créé.');
    }

    public function show(MaintenanceTicket $maintenance)
    {
        $maintenance->load(['room', 'reporter', 'assignee']);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceTicket $maintenance)
    {
        $rooms = Room::orderBy('number')->get();
        $technicians = User::role(['manager', 'receptionist'])->get();
        return view('maintenance.edit', compact('maintenance', 'rooms', 'technicians'));
    }

    public function update(Request $request, MaintenanceTicket $maintenance)
    {
        $request->validate([
            'status'           => 'required|in:pending,in_progress,completed',
            'priority'         => 'required|in:low,medium,high,urgent',
            'assigned_to'      => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string|max:2000',
        ]);

        $data = $request->all();
        if ($request->status === 'completed') {
            $data['resolved_at'] = now();
            // Remettre la chambre disponible
            $maintenance->room->update(['status' => 'available']);
        }

        $maintenance->update($data);
        return redirect()->route('maintenance.show', $maintenance)->with('success', 'Ticket mis à jour.');
    }

    public function destroy(MaintenanceTicket $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Ticket supprimé.');
    }
}
