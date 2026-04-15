<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        $query = Blacklist::query()->with(['guest'])->orderByDesc('created_at');

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->whereHas('guest', function ($guestQuery) use ($q) {
                $guestQuery
                    ->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $blacklists = $query->paginate(25);
        return view('admin.blacklists.index', compact('blacklists'));
    }

    public function create()
    {
        $guests = Guest::query()->orderBy('first_name')->orderBy('last_name')->limit(500)->get();
        return view('admin.blacklists.create', compact('guests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'reason' => ['required', 'string', 'max:5000'],
            'blocked_until' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($data) {
            $entry = Blacklist::withTrashed()->firstOrNew(['guest_id' => $data['guest_id']]);
            if ($entry->trashed()) {
                $entry->restore();
            }
            $entry->reason = $data['reason'];
            $entry->blocked_until = $data['blocked_until'] ?? null;
            $entry->save();

            $guest = Guest::findOrFail($data['guest_id']);
            $guest->blacklisted = true;
            $guest->save();
        });

        return redirect()->route('admin.blacklists.index')->with('success', 'Guest blacklisted');
    }

    public function edit($id)
    {
        $blacklist = Blacklist::query()->with(['guest'])->findOrFail($id);
        $guests = Guest::query()->orderBy('first_name')->orderBy('last_name')->limit(500)->get();
        return view('admin.blacklists.edit', compact('blacklist', 'guests'));
    }

    public function update(Request $request, $id)
    {
        $blacklist = Blacklist::query()->findOrFail($id);

        $data = $request->validate([
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'reason' => ['required', 'string', 'max:5000'],
            'blocked_until' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($blacklist, $data) {
            $blacklist->guest_id = $data['guest_id'];
            $blacklist->reason = $data['reason'];
            $blacklist->blocked_until = $data['blocked_until'] ?? null;
            $blacklist->save();

            $guest = Guest::findOrFail($data['guest_id']);
            $guest->blacklisted = true;
            $guest->save();
        });

        return redirect()->route('admin.blacklists.index')->with('success', 'Blacklist updated');
    }

    public function destroy($id)
    {
        $blacklist = Blacklist::query()->with(['guest'])->findOrFail($id);

        DB::transaction(function () use ($blacklist) {
            $guestId = $blacklist->guest_id;
            $blacklist->delete();

            $guest = Guest::find($guestId);
            if ($guest) {
                $guest->blacklisted = false;
                $guest->save();
            }
        });

        return redirect()->route('admin.blacklists.index')->with('success', 'Blacklist removed');
    }
}
