@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Add Room</h3>
    </div>
    <div class="kt-card-content p-4">
        <form method="POST" action="{{ route('admin.rooms.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground">Room number</label>
                <input class="kt-input w-full" name="number" value="{{ old('number') }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Type</label>
                <select id="room_type_select" name="type" class="kt-input w-full">
                    <option value="">--</option>
                    @foreach($types as $t)
                        <option value="{{ $t->name }}" data-capacity="{{ $t->capacity }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Floor</label>
                <input class="kt-input w-full" name="floor" value="{{ old('floor') }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Capacity</label>
                <input id="room_capacity_input" type="number" class="kt-input w-full" name="capacity" value="{{ old('capacity', 1) }}" readonly />
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Amenities</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($amenities as $a)
                        @php $oldAmenities = old('amenities', []); @endphp
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="amenities[]" value="{{ $a->id }}" @if(in_array($a->id, (array)$oldAmenities)) checked @endif> {{ $a->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Create</button>
                <a class="kt-btn" href="{{ route('admin.rooms.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const typeSelect = document.getElementById('room_type_select');
        const capacityInput = document.getElementById('room_capacity_input');
        if (!typeSelect || !capacityInput) return;

        typeSelect.addEventListener('change', function(){
            const selected = typeSelect.options[typeSelect.selectedIndex];
            const cap = selected.getAttribute('data-capacity');
            if (cap !== null && cap !== '') {
                capacityInput.value = parseInt(cap, 10) || 1;
            }
        });
    });
    </script>
    @endpush
