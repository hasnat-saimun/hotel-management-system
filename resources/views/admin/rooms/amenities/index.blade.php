@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Amenities</h3>
            <div class="text-sm text-secondary-foreground">Manage amenities</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.rooms.amenities.index') }}" id="amenities-search-form" class="flex items-center gap-2">
                <input id="amenities-search-input" type="text" name="q" class="kt-input" placeholder="Search amenity name" value="{{ request('q') }}" />
            </form>
            <div>
                <a href="{{ route('admin.rooms.amenities.create') }}" class="kt-btn kt-btn-primary">Add Amenity</a>
            </div>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <div class="mb-3 flex items-center gap-2">
            <form id="amenities-bulk-form" method="POST" action="{{ route('admin.rooms.amenities.bulkDestroy') }}">
                @csrf
            </form>
            <button id="amenities-delete-selected" class="kt-btn kt-btn-destructive kt-btn-sm" style="display:none;opacity:0;transition:opacity 300ms;">Delete Selected</button>
        </div>
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2"><input type="checkbox" id="amenities-select-all" /></th>
                    <th class="p-2">SL</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Active</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($amenities as $a)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2"><input type="checkbox" class="row-checkbox" value="{{ $a->id }}"></td>
                    <td class="p-2">{{ $amenities->firstItem() ? $amenities->firstItem() + $loop->index : $loop->iteration }}</td>
                    <td class="p-2">{{ $a->name }}</td>
                    <td class="p-2">{{ $a->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="p-2">
                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.rooms.amenities.edit', $a->id) }}">Edit</a>
                        <form action="{{ route('admin.rooms.amenities.destroy', $a->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this amenity?');">
                            @csrf
                            @method('DELETE')
                            <button class="kt-btn kt-btn-danger kt-btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-secondary-foreground">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $amenities->appends(request()->except('page'))->links() }}
        </div>

    @push('scripts')
    <script>
    (function(){
        var form = document.getElementById('amenities-search-form');
        var input = document.getElementById('amenities-search-input');
        if (!form || !input) return;
        var timeout = null;
        input.addEventListener('input', function(){
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });
    })();
    </script>
    @endpush

    @push('scripts')
    <script>
    (function(){
        var deleteBtn = document.getElementById('amenities-delete-selected');
        var bulkForm = document.getElementById('amenities-bulk-form');
        var selectAll = document.getElementById('amenities-select-all');
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
            if (!confirm('Delete selected amenities?')) return;
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
