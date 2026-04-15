<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $query = Loyalty::query()->orderBy('points_required');

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('level_name', 'like', "%{$q}%");
        }

        $loyalties = $query->paginate(25);
        return view('admin.loyalties.index', compact('loyalties'));
    }

    public function create()
    {
        return view('admin.loyalties.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'level_name' => ['required', 'string', 'max:191', 'unique:loyalties,level_name'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'points_required' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['discount_percentage'] = $data['discount_percentage'] ?? 0;
        $data['points_required'] = $data['points_required'] ?? 0;

        Loyalty::create($data);
        return redirect()->route('admin.loyalties.index')->with('success', 'Loyalty level created');
    }

    public function edit($id)
    {
        $loyalty = Loyalty::findOrFail($id);
        return view('admin.loyalties.edit', compact('loyalty'));
    }

    public function update(Request $request, $id)
    {
        $loyalty = Loyalty::findOrFail($id);

        $data = $request->validate([
            'level_name' => ['required', 'string', 'max:191', Rule::unique('loyalties', 'level_name')->ignore($loyalty->id)],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'points_required' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['discount_percentage'] = $data['discount_percentage'] ?? 0;
        $data['points_required'] = $data['points_required'] ?? 0;

        $loyalty->update($data);
        return redirect()->route('admin.loyalties.index')->with('success', 'Loyalty level updated');
    }

    public function destroy($id)
    {
        $loyalty = Loyalty::findOrFail($id);
        $loyalty->delete();
        return redirect()->route('admin.loyalties.index')->with('success', 'Loyalty level deleted');
    }
}
