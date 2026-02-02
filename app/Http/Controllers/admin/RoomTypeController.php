<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
use Illuminate\Support\Str;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::orderBy('name');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
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
            'capacity_adults' => 'nullable|integer|min:0',
            'capacity_children' => 'nullable|integer|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // ensure slug
        $slug = $data['slug'] ?? null;
        if (empty($slug)) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $counter = 1;
            while (RoomType::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $counter;
                $counter++;
            }
        }
        $data['slug'] = $slug;
        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : true;

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
            'capacity_adults' => 'nullable|integer|min:0',
            'capacity_children' => 'nullable|integer|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $counter = 1;
            while (RoomType::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $base . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }
        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;

        $type->update($data);
        return redirect()->route('admin.rooms.types.index')->with('success', 'Room type updated');
    }

    public function destroy($id)
    {
        $type = RoomType::findOrFail($id);
        $type->delete();
        return redirect()->route('admin.rooms.types.index')->with('success', 'Room type deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:room_types,id'
        ]);

        $count = RoomType::whereIn('id', $data['ids'])->delete();
        return redirect()->route('admin.rooms.types.index')->with('success', "$count room types deleted");
    }
}
