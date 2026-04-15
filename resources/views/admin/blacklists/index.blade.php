@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Blacklist / Watchlist</h3>
            <div class="text-sm text-secondary-foreground">Blocked guests</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.blacklists.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" class="kt-input" placeholder="Search guest" value="{{ request('q') }}" />
                <button class="kt-btn" type="submit">Search</button>
            </form>
            <a href="{{ route('admin.blacklists.create') }}" class="kt-btn kt-btn-primary">Add to Blacklist</a>
        </div>
    </div>

    <div class="kt-card-content p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">SL</th>
                    <th class="p-2">Guest</th>
                    <th class="p-2">Reason</th>
                    <th class="p-2">Blocked until</th>
                    <th class="p-2">Created</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blacklists as $b)
                    <tr class="border-t hover:bg-muted/10">
                        <td class="p-2">{{ $blacklists->firstItem() ? $blacklists->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td class="p-2">
                            <div class="font-medium">{{ $b->guest?->first_name }} {{ $b->guest?->last_name }}</div>
                            <div class="text-xs text-secondary-foreground">{{ $b->guest?->phone ?? '' }}</div>
                        </td>
                        <td class="p-2">{{ \Illuminate\Support\Str::limit($b->reason, 80) }}</td>
                        <td class="p-2">{{ $b->blocked_until ? $b->blocked_until->format('Y-m-d') : '-' }}</td>
                        <td class="p-2">{{ $b->created_at ? $b->created_at->format('Y-m-d') : '-' }}</td>
                        <td class="p-2">
                            <a class="kt-btn kt-btn-sm" href="{{ route('admin.blacklists.edit', $b->id) }}">Edit</a>
                            <form action="{{ route('admin.blacklists.destroy', $b->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove from blacklist?');">
                                @csrf
                                @method('DELETE')
                                <button class="kt-btn kt-btn-danger kt-btn-sm" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-secondary-foreground">No entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $blacklists->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
