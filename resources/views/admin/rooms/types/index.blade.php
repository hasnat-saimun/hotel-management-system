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
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">Name</th>
                    <th class="p-2">Capacity</th>
                    <th class="p-2">Base Price</th>
                    <th class="p-2">Amenities</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2">{{ $type->name }}</td>
                    <td class="p-2">{{ $type->capacity }}</td>
                    <td class="p-2">{{ number_format($type->base_price,2) }}</td>
                    <td class="p-2">{{ $type->amenities }}</td>
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
                    <td colspan="5" class="p-6 text-center text-secondary-foreground">No data found.</td>
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
</div>
@endsection
