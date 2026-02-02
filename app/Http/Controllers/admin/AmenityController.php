<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;

class AmenityController extends Controller
{
    public function index(Request $request)
    {
        $query = Amenity::orderBy('name');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%");
        }

        $amenities = $query->paginate(25);
        return view('admin.rooms.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('admin.rooms.amenities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        Amenity::create($data);
        return redirect()->route('admin.rooms.amenities.index')->with('success', 'Amenity created');
    }

    public function edit($id)
    {
        $amenity = Amenity::findOrFail($id);
        return view('admin.rooms.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;
        $amenity->update($data);
        return redirect()->route('admin.rooms.amenities.index')->with('success', 'Amenity updated');
    }

    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->rooms()->detach();
        $amenity->delete();
        return redirect()->route('admin.rooms.amenities.index')->with('success', 'Amenity deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:amenities,id'
        ]);

        $amenities = Amenity::whereIn('id', $data['ids'])->get();
        foreach ($amenities as $a) { $a->rooms()->detach(); }
        $count = Amenity::whereIn('id', $data['ids'])->delete();
        return redirect()->route('admin.rooms.amenities.index')->with('success', "$count amenities deleted");
    }
}
