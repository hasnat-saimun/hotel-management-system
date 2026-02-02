@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Room Types</h3>
            <div class="text-sm text-secondary-foreground">Manage room type definitions (capacity, base price, amenities)</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.rooms.types.index') }}" id="types-search-form" class="flex items-center gap-2">
                <input id="types-search-input" type="text" name="q" class="kt-input" placeholder="Search types..." value="{{ request('q') }}" />
            </form>
            <div>
                <a href="{{ route('admin.rooms.types.create') }}" class="kt-btn kt-btn-primary">Add Type</a>
            </div>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <div class="mb-3 flex items-center gap-2">
            <form id="types-bulk-form" method="POST" action="{{ route('admin.rooms.types.bulkDestroy') }}">
                @csrf
            </form>
            <button id="types-delete-selected" class="kt-btn kt-btn-destructive kt-btn-sm" style="display:none;opacity:0;transition:opacity 300ms;">Delete Selected</button>
        </div>
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2"><input type="checkbox" id="types-select-all" /></th>
                    <th class="p-2">SL</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Slug</th>
                    <th class="p-2">Capacity (Adults/Children)</th>
                    <th class="p-2">Base Price</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Active</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2"><input type="checkbox" class="row-checkbox" value="{{ $type->id }}"></td>
                    <td class="p-2">{{ $types->firstItem() ? $types->firstItem() + $loop->index : $loop->iteration }}</td>
                    <td class="p-2">{{ $type->name }}</td>
                    <td class="p-2">{{ $type->slug }}</td>
                    <td class="p-2">A: {{ $type->capacity_adults }} - C: {{ $type->capacity_children }}</td>
                    <td class="p-2">{{ number_format($type->base_price,2) }}</td>
                    <td class="p-2">{{ Str::limit($type->description, 60) }}</td>
                    <td class="p-2">{{ $type->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="p-2">
                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.rooms.types.edit', $type->id) }}">Edit</a>
                        <form action="{{ route('admin.rooms.types.destroy', $type->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this room type?');">
                            @csrf
                            @method('DELETE')
                            <button class="kt-btn kt-btn-danger kt-btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-6 text-center text-secondary-foreground">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $types->appends(request()->except('page'))->links() }}
        </div>
    @push('scripts')
    <script>
    (function(){
        var form = document.getElementById('types-search-form');
        var input = document.getElementById('types-search-input');
        if (!form || !input) return;
        var timeout = null;
        input.addEventListener('input', function(){
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });
    })();
    </script>
    @endpush
        </table>
    </div>
@push('scripts')
<script>
(function(){
    var deleteBtn = document.getElementById('types-delete-selected');
    var bulkForm = document.getElementById('types-bulk-form');
    var selectAll = document.getElementById('types-select-all');
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
        if (!confirm('Delete selected room types?')) return;
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
@endsection
