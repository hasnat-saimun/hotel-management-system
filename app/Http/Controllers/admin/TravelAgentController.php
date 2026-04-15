<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TravelAgent;
use Illuminate\Http\Request;

class TravelAgentController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelAgent::query()->orderBy('name');

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $agents = $query->paginate(25);
        return view('admin.travel_agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.travel_agents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'address' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['commission_percentage'] = $data['commission_percentage'] ?? 0;

        TravelAgent::create($data);
        return redirect()->route('admin.travel-agents.index')->with('success', 'Travel agent created');
    }

    public function edit($id)
    {
        $agent = TravelAgent::findOrFail($id);
        return view('admin.travel_agents.edit', compact('agent'));
    }

    public function update(Request $request, $id)
    {
        $agent = TravelAgent::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'address' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['commission_percentage'] = $data['commission_percentage'] ?? 0;

        $agent->update($data);
        return redirect()->route('admin.travel-agents.index')->with('success', 'Travel agent updated');
    }

    public function destroy($id)
    {
        $agent = TravelAgent::findOrFail($id);
        $agent->delete();
        return redirect()->route('admin.travel-agents.index')->with('success', 'Travel agent deleted');
    }
}
