<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->orderBy('name')->paginate(15);
        return view('roomtypes.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('roomtypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:room_types,name',
            'capacity'   => 'required|integer|min:1|max:20',
            'base_price' => 'required|numeric|min:0',
            'description'=> 'nullable|string|max:1000',
            'amenities'  => 'nullable|array',
        ]);

        RoomType::create([
            'name'        => $request->name,
            'capacity'    => $request->capacity,
            'base_price'  => $request->base_price,
            'description' => $request->description,
            'amenities'   => $request->amenities ?? [],
        ]);

        return redirect()->route('room-types.index')->with('success', "Type de chambre créé.");
    }

    public function edit(RoomType $roomType)
    {
        return view('roomtypes.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:room_types,name,' . $roomType->id,
            'capacity'   => 'required|integer|min:1|max:20',
            'base_price' => 'required|numeric|min:0',
            'description'=> 'nullable|string|max:1000',
        ]);

        $roomType->update($request->all());
        return redirect()->route('room-types.index')->with('success', "Type de chambre mis à jour.");
    }

    public function destroy(RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->withErrors(['error' => 'Ce type est utilisé par des chambres existantes.']);
        }
        $roomType->delete();
        return redirect()->route('room-types.index')->with('success', "Type supprimé.");
    }
}
