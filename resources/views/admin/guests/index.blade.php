@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Guests</h3>
            <div class="text-sm text-secondary-foreground">Guest Profiles & CRM</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.guests.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" class="kt-input" placeholder="Search name, phone, email" value="{{ request('q') }}" />
                <button class="kt-btn" type="submit">Search</button>
            </form>
            <a href="{{ route('admin.guests.create') }}" class="kt-btn kt-btn-primary">Add Guest</a>
        </div>
    </div>

    <div class="kt-card-content p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.guests.index') }}" class="mb-4 grid gap-3 grid-cols-1 lg:grid-cols-5">
            <input type="hidden" name="q" value="{{ request('q') }}" />

            <div>
                <label class="text-sm text-secondary-foreground">Company</label>
                <select name="company_id" class="kt-input w-full">
                    <option value="">All</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" @selected((string)request('company_id') === (string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Travel Agent</label>
                <select name="travel_agent_id" class="kt-input w-full">
                    <option value="">All</option>
                    @foreach($travelAgents as $a)
                        <option value="{{ $a->id }}" @selected((string)request('travel_agent_id') === (string)$a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Loyalty</label>
                <select name="loyalty_id" class="kt-input w-full">
                    <option value="">All</option>
                    @foreach($loyalties as $l)
                        <option value="{{ $l->id }}" @selected((string)request('loyalty_id') === (string)$l->id)>{{ $l->level_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Blacklist</label>
                <select name="blacklisted" class="kt-input w-full">
                    <option value="">All</option>
                    <option value="1" @selected(request('blacklisted') === '1')>Blacklisted</option>
                    <option value="0" @selected(request('blacklisted') === '0')>Not blacklisted</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Apply</button>
                <a class="kt-btn" href="{{ route('admin.guests.index') }}">Reset</a>
            </div>
        </form>

        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">SL</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Company</th>
                    <th class="p-2">Agent</th>
                    <th class="p-2">Loyalty</th>
                    <th class="p-2">Blacklist</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $g)
                    <tr class="border-t hover:bg-muted/10">
                        <td class="p-2">{{ $guests->firstItem() ? $guests->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td class="p-2">
                            <div class="font-medium">{{ $g->first_name }} {{ $g->last_name }}</div>
                            <div class="text-xs text-secondary-foreground">#{{ $g->id }}</div>
                        </td>
                        <td class="p-2">{{ $g->email ?? '-' }}</td>
                        <td class="p-2">{{ $g->phone ?? '-' }}</td>
                        <td class="p-2">{{ $g->company?->name ?? '-' }}</td>
                        <td class="p-2">{{ $g->travelAgent?->name ?? '-' }}</td>
                        <td class="p-2">{{ $g->loyalty?->level_name ?? '-' }}</td>
                        <td class="p-2">
                            @if($g->blacklisted)
                                <span class="kt-badge kt-badge-sm kt-badge-danger">Blacklisted</span>
                            @else
                                <span class="kt-badge kt-badge-sm kt-badge-outline">No</span>
                            @endif
                        </td>
                        <td class="p-2">
                            <a class="kt-btn kt-btn-sm" href="{{ route('admin.guests.edit', $g->id) }}">Edit</a>
                            <form action="{{ route('admin.guests.destroy', $g->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this guest?');">
                                @csrf
                                @method('DELETE')
                                <button class="kt-btn kt-btn-danger kt-btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-secondary-foreground">No guests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $guests->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
