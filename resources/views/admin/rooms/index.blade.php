@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Rooms</h3>
            <div class="text-sm text-secondary-foreground">Manage room inventory and statuses</div>
        </div>
        <div class="flex items-center gap-2">
            <form id="rooms-filter-form" method="GET" action="{{ route('admin.rooms.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="kt-input" placeholder="Search room #, type or floor">
                <select name="status" class="kt-select">
                    <option value="">All statuses</option>
                    <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status')=='occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status')=='maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </form>
            <a href="{{ route('admin.rooms.create') }}" class="kt-btn kt-btn-primary">Add Room</a>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">Room #</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Floor</th>
                    <th class="p-2">Capacity</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2">{{ $room->number }}</td>
                    <td class="p-2">{{ $room->type }}</td>
                    <td class="p-2">{{ $room->floor }}</td>
                    <td class="p-2">{{ $room->capacity }}</td>
                    <td class="p-2">
                        <span class="kt-badge {{ $room->status == 'available' ? 'kt-badge-success' : 'kt-badge-warning' }}">{{ ucfirst($room->status) }}</span>
                    </td>
                    <td class="p-2">
                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.rooms.edit', $room->id) }}">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this room?');">
                            @csrf
                            @method('DELETE')
                            <button class="kt-btn kt-btn-danger kt-btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $rooms->appends(request()->except('page'))->links() }}
        </div>
    @push('scripts')
    <script>
    (function(){
        var form = document.getElementById('rooms-filter-form');
        if (!form) return;
        var input = form.querySelector('input[name="q"]');
        var select = form.querySelector('select[name="status"]');
        var timeout = null;

        if (input) {
            input.addEventListener('input', function(){
                if (timeout) clearTimeout(timeout);
                timeout = setTimeout(function(){ form.submit(); }, 450);
            });
        }
        if (select) {
            select.addEventListener('change', function(){ form.submit(); });
        }
    })();
    </script>
    @endpush
    </div>
</div>
@endsection
