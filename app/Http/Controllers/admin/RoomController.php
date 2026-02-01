<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Amenity;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('amenities')->orderBy('number');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($w) use ($q) {
                $w->where('number', 'like', "%{$q}%")
                  ->orWhere('type', 'like', "%{$q}%")
                  ->orWhere('floor', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $rooms = $query->paginate(25);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $types = RoomType::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.rooms.create', compact('types', 'amenities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number' => 'required|string|max:50|unique:rooms,number',
            'type' => 'nullable|string|max:191',
            'floor' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);

        $room = Room::create($data);
        // always sync amenities (will attach none if not provided)
        $room->amenities()->sync($data['amenities'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Room created');
    }

    public function edit($id)
    {
        $room = Room::with('amenities')->findOrFail($id);
        $types = RoomType::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.rooms.edit', compact('room','types','amenities'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'number' => 'required|string|max:50|unique:rooms,number,'.$room->id,
            'type' => 'nullable|string|max:191',
            'floor' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);

        $room->update($data);
        $room->amenities()->sync($data['amenities'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->amenities()->detach();
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted');
    }
}
