<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraService;

class ExtraServiceController extends Controller
{
    public function index()
    {
        $services = ExtraService::orderBy('name')->get();
        return view('admin.rooms.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.rooms.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        ExtraService::create($data);
        return redirect()->route('admin.rooms.services.index')->with('success', 'Service created');
    }

    public function edit($id)
    {
        $service = ExtraService::findOrFail($id);
        return view('admin.rooms.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = ExtraService::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($data);
        return redirect()->route('admin.rooms.services.index')->with('success', 'Service updated');
    }

    public function destroy($id)
    {
        $service = ExtraService::findOrFail($id);
        $service->delete();
        return redirect()->route('admin.rooms.services.index')->with('success', 'Service deleted');
    }
}
