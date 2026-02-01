@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Rooms</h3>
            <div class="text-sm text-secondary-foreground">Manage room inventory and statuses</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.rooms.index') }}" id="rooms-search-form" class="flex items-center gap-2">
                <input id="rooms-search-input" type="text" name="q" class="kt-input" placeholder="Search room #, type or floor" value="{{ request('q') }}" />
                <select class="kt-select" name="status">
                    <option value="">All statuses</option>
                    <option value="available" {{ request('status')=='available' ? 'selected':'' }}>Available</option>
                    <option value="occupied" {{ request('status')=='occupied' ? 'selected':'' }}>Occupied</option>
                    <option value="maintenance" {{ request('status')=='maintenance' ? 'selected':'' }}>Maintenance</option>
                    <option value="reserved" {{ request('status')=='reserved' ? 'selected':'' }}>Reserved</option>
                </select>
            </form>
            <div>
                <a href="{{ route('admin.rooms.create') }}" class="kt-btn kt-btn-primary">Add Room</a>
            </div>
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
                @forelse($rooms as $room)
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
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-secondary-foreground">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $rooms->appends(request()->except('page'))->links() }}
        </div>
    @push('scripts')
    <script>
    (function(){
        var form = document.getElementById('rooms-search-form');
        var input = document.getElementById('rooms-search-input');
        if (!form || !input) return;

        // make input visually active if it has value
        function setActiveState(){
            if (input.value && input.value.trim() !== ''){
                input.classList.add('active');
            } else {
                input.classList.remove('active');
            }
        }

        // initial state
        setActiveState();

        // focus input so it's ready for typing
        input.addEventListener('focus', function(){ input.classList.add('active'); });
        input.addEventListener('blur', function(){ setActiveState(); });

        var timeout = null;
        input.addEventListener('input', function(){
            setActiveState();
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });

        // submit when selects change (status filter)
        var selects = form.querySelectorAll('select');
        selects.forEach(function(s){ s.addEventListener('change', function(){ form.submit(); }); });
    })();
    </script>
    @endpush
    </div>
</div>
@endsection
