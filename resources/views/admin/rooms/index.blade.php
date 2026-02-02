@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Rooms</h3>
            <div class="text-sm text-secondary-foreground">Manage room inventory</div>
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
                    <option value="dirty" {{ request('status')=='dirty' ? 'selected':'' }}>Dirty</option>
                    <option value="clean" {{ request('status')=='clean' ? 'selected':'' }}>Clean</option>
                </select>
            </form>
            <div>
                <a href="{{ route('admin.rooms.create') }}" class="kt-btn kt-btn-primary">Add Room</a>
            </div>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <div class="mb-3 flex items-center gap-2">
            <form id="rooms-bulk-form" method="POST" action="{{ route('admin.rooms.bulkDestroy') }}">
                @csrf
            </form>
            <button id="rooms-delete-selected" class="kt-btn kt-btn-destructive kt-btn-sm" style="display:none;opacity:0;transition:opacity 300ms;">Delete Selected</button>
        </div>
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2"><input type="checkbox" id="rooms-select-all" /></th>
                    <th class="p-2">SL</th>
                    <th class="p-2">Room #</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Floor</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Active</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2"><input type="checkbox" class="row-checkbox" value="{{ $room->id }}"></td>
                    <td class="p-2">{{ $rooms->firstItem() ? $rooms->firstItem() + $loop->index : $loop->iteration }}</td>
                    <td class="p-2">{{ $room->room_number }}</td>
                    <td class="p-2">{{ $room->roomType?->name ?? '-' }}</td>
                    <td class="p-2">{{ $room->floor?->name ?? '-' }}</td>
                    <td class="p-2">
                        <span class="kt-badge {{ $room->status == 'available' ? 'kt-badge-success' : 'kt-badge-warning' }}">{{ ucfirst($room->status) }}</span>
                    </td>
                    <td class="p-2">{{ $room->is_active ? 'Yes' : 'No' }}</td>
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
                    <td colspan="8" class="p-6 text-center text-secondary-foreground">No data found.</td>
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
        var timeout = null;
        input.addEventListener('input', function(){
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });
        var selects = form.querySelectorAll('select');
        selects.forEach(function(s){ s.addEventListener('change', function(){ form.submit(); }); });
    })();
    </script>
    @endpush

    @push('scripts')
    <script>
    (function(){
        var deleteBtn = document.getElementById('rooms-delete-selected');
        var bulkForm = document.getElementById('rooms-bulk-form');
        var selectAll = document.getElementById('rooms-select-all');
        if (!deleteBtn || !bulkForm) return;
        function update(){
            var all = document.querySelectorAll('.row-checkbox');
            var checked = document.querySelectorAll('.row-checkbox:checked');
            if (selectAll) selectAll.checked = all.length > 0 && checked.length === all.length;
            if (checked.length === 0) {
                deleteBtn.style.opacity = 0;
                setTimeout(function(){ deleteBtn.style.display = 'none'; }, 300);
            } else {
                deleteBtn.style.display = 'inline-block';
                setTimeout(function(){ deleteBtn.style.opacity = 1; }, 10);
            }
        }
        document.addEventListener('change', function(e){
            if (e.target && e.target.classList && e.target.classList.contains('row-checkbox')) update();
        });
        if (selectAll){
            selectAll.addEventListener('change', function(){
                var checked = this.checked;
                document.querySelectorAll('.row-checkbox').forEach(function(c){ c.checked = checked; });
                update();
            });
        }
        deleteBtn.addEventListener('click', function(){
            var checked = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(function(c){ return c.value; });
            if (!checked.length) return;
            if (!confirm('Delete selected rooms?')) return;
            while (bulkForm.firstChild) bulkForm.removeChild(bulkForm.firstChild);
            var csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; bulkForm.appendChild(csrf);
            checked.forEach(function(id){ var i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; bulkForm.appendChild(i); });
            bulkForm.submit();
        });
        update();
    })();
    </script>
    @endpush

    </div>
</div>
@endsection
