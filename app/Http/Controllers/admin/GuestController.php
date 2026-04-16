<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Company;
use App\Models\Guest;
use App\Models\Loyalty;
use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query()->with(['company', 'travelAgent', 'loyalty', 'blacklist']);

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->filled('travel_agent_id')) {
            $query->where('travel_agent_id', $request->input('travel_agent_id'));
        }

        if ($request->filled('loyalty_id')) {
            $query->where('loyalty_id', $request->input('loyalty_id'));
        }

        if ($request->filled('blacklisted')) {
            $isBlacklisted = (string) $request->input('blacklisted') === '1';
            $query->where('blacklisted', $isBlacklisted);
        }

        $guests = $query
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(25);

        $companies = Company::query()->orderBy('name')->get();
        $travelAgents = TravelAgent::query()->orderBy('name')->get();
        $loyalties = Loyalty::query()->orderBy('points_required')->get();

        return view('admin.guests.index', compact('guests', 'companies', 'travelAgents', 'loyalties'));
    }

    public function create()
    {
        $companies = Company::query()->orderBy('name')->get();
        $travelAgents = TravelAgent::query()->orderBy('name')->get();
        $loyalties = Loyalty::query()->orderBy('points_required')->get();

        return view('admin.guests.create', compact('companies', 'travelAgents', 'loyalties'));
    }

    public function store(Request $request)
    {
        $data = $this->validateGuest($request);

        return DB::transaction(function () use ($data) {
            $guest = Guest::create($data);
            $this->syncBlacklist($guest, $data);

            return redirect()->route('admin.guests.index')->with('success', 'Guest created');
        });
    }

    public function edit($id)
    {
        $guest = Guest::query()->with(['blacklist'])->findOrFail($id);
        $companies = Company::query()->orderBy('name')->get();
        $travelAgents = TravelAgent::query()->orderBy('name')->get();
        $loyalties = Loyalty::query()->orderBy('points_required')->get();

        return view('admin.guests.edit', compact('guest', 'companies', 'travelAgents', 'loyalties'));
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::query()->with(['blacklist'])->findOrFail($id);
        $data = $this->validateGuest($request, $guest->id);

        return DB::transaction(function () use ($guest, $data) {
            $guest->update($data);
            $this->syncBlacklist($guest, $data);

            return redirect()->route('admin.guests.index')->with('success', 'Guest updated');
        });
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return redirect()->route('admin.guests.index')->with('success', 'Guest deleted');
    }

    public function apiSearch(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $guests = Guest::query()
            ->with(['company'])
            ->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->orderBy('first_name')
            ->limit(10)
            ->get();

        $payload = $guests->map(function (Guest $g) {
            return [
                'id' => $g->id,
                'first_name' => $g->first_name,
                'last_name' => $g->last_name,
                'full_name' => trim(($g->first_name ?? '') . ' ' . ($g->last_name ?? '')),
                'email' => $g->email,
                'phone' => $g->phone,
                'address' => $g->address,
                'company' => $g->company ? ['id' => $g->company->id, 'name' => $g->company->name] : null,
                'blacklisted' => (bool) $g->blacklisted,
            ];
        });

        return response()->json($payload);
    }

    public function storeAjax(Request $request)
    {
        $data = $this->validateGuest($request);

        $guest = DB::transaction(function () use ($data) {
            $guest = Guest::create($data);
            $this->syncBlacklist($guest, $data);
            return $guest;
        });

        return response()->json([
            'success' => true,
            'guest' => [
                'id' => $guest->id,
                'first_name' => $guest->first_name,
                'last_name' => $guest->last_name,
                'full_name' => trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? '')),
                'email' => $guest->email,
                'phone' => $guest->phone,
                'id_type' => $guest->id_type,
                'id_number' => $guest->id_number,
            ],
        ]);
    }

    private function validateGuest(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('guests', 'email')->ignore($ignoreId)->whereNotNull('email'),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:2000'],
            'nationality' => ['nullable', 'string', 'max:191'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'id_type' => ['nullable', Rule::in(['passport', 'driver_license', 'national_id', 'other'])],
            'id_number' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'travel_agent_id' => ['nullable', 'integer', 'exists:travel_agents,id'],
            'loyalty_id' => ['nullable', 'integer', 'exists:loyalties,id'],
            'vip' => ['nullable', 'boolean'],
            'blacklisted' => ['nullable', 'boolean'],
            'blacklist_reason' => ['nullable', 'required_if:blacklisted,1', 'string', 'max:5000'],
            'blacklist_blocked_until' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        // Require at least phone or email
        $email = trim((string) ($data['email'] ?? ''));
        $phone = trim((string) ($data['phone'] ?? ''));
        if ($email === '' && $phone === '') {
            $request->validate([
                'phone' => ['required'],
            ], [
                'phone.required' => 'Please provide at least Email or Phone.',
            ]);
        }

        // Duplicate protection (includes soft-deleted records)
        if ($email !== '') {
            $exists = Guest::withTrashed()
                ->where('email', $email)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists();
            if ($exists) {
                $request->validate([
                    'email' => ['unique:guests,email'],
                ], [
                    'email.unique' => 'A guest with this email already exists.',
                ]);
            }
        }

        if ($phone !== '') {
            $exists = Guest::withTrashed()
                ->where('phone', $phone)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists();
            if ($exists) {
                $request->validate([
                    'phone' => ['unique:guests,phone'],
                ], [
                    'phone.unique' => 'A guest with this phone already exists.',
                ]);
            }
        }

        $data['vip'] = (bool) ($data['vip'] ?? false);
        $data['blacklisted'] = (bool) ($data['blacklisted'] ?? false);

        return $data;
    }

    private function syncBlacklist(Guest $guest, array $data): void
    {
        $shouldBlacklist = (bool) ($data['blacklisted'] ?? false);
        $reason = trim((string) ($data['blacklist_reason'] ?? ''));
        $blockedUntil = $data['blacklist_blocked_until'] ?? null;

        if ($shouldBlacklist) {
            // Validation should already enforce this.
            if ($reason === '') {
                return;
            }

            $entry = Blacklist::withTrashed()->firstOrNew(['guest_id' => $guest->id]);
            if ($entry->trashed()) {
                $entry->restore();
            }
            $entry->reason = $reason;
            $entry->blocked_until = $blockedUntil;
            $entry->save();

            if (!$guest->blacklisted) {
                $guest->blacklisted = true;
                $guest->save();
            }

            return;
        }

        // Not blacklisted
        if ($guest->relationLoaded('blacklist')) {
            $existing = $guest->blacklist;
        } else {
            $existing = Blacklist::query()->where('guest_id', $guest->id)->first();
        }

        if ($existing) {
            $existing->delete();
        }

        if ($guest->blacklisted) {
            $guest->blacklisted = false;
            $guest->save();
        }
    }
}
