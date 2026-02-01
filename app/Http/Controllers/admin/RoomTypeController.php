<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::orderBy('name');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('amenities', 'like', "%{$q}%");
        }

        $types = $query->paginate(25);
        return view('admin.rooms.types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.rooms.types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'nullable|integer|min:1',
            'base_price' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|string',
        ]);

        RoomType::create($data);
        return redirect()->route('admin.rooms.types.index')->with('success', 'Room type created');
    }

    public function edit($id)
    {
        $type = RoomType::findOrFail($id);
        return view('admin.rooms.types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = RoomType::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'nullable|integer|min:1',
            'base_price' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|string',
        ]);

        $type->update($data);
        return redirect()->route('admin.rooms.types.index')->with('success', 'Room type updated');
    }

    public function destroy($id)
    {
        $type = RoomType::findOrFail($id);
        $type->delete();
        return redirect()->route('admin.rooms.types.index')->with('success', 'Room type deleted');
    }
}
