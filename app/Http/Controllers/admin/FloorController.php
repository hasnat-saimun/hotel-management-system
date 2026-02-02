<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Floor;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $query = Floor::orderBy('level_number');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")->orWhere('level_number', 'like', "%{$q}%");
        }

        $floors = $query->paginate(25);
        return view('admin.floors.index', compact('floors'));
    }

    public function create()
    {
        return view('admin.floors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'level_number' => 'nullable|integer',
        ]);

        Floor::create($data);
        return redirect()->route('admin.rooms.floors.index')->with('success', 'Floor created');
    }

    public function edit($id)
    {
        $floor = Floor::findOrFail($id);
        return view('admin.floors.edit', compact('floor'));
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'level_number' => 'nullable|integer',
        ]);

        $floor->update($data);
        return redirect()->route('admin.rooms.floors.index')->with('success', 'Floor updated');
    }

    public function destroy($id)
    {
        $floor = Floor::findOrFail($id);
        $floor->delete();
        return redirect()->route('admin.rooms.floors.index')->with('success', 'Floor deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:floors,id'
        ]);

        $count = Floor::whereIn('id', $data['ids'])->delete();
        return redirect()->route('admin.rooms.floors.index')->with('success', "$count floors deleted");
    }
}
