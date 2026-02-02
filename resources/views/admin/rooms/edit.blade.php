@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Edit Room {{ $room->room_number }}</h3>
    </div>
    <div class="kt-card-content p-4">
        @if($errors->any())
            <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.rooms.update', $room->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-secondary-foreground">Room Number</label>
                <input class="kt-input w-full" name="room_number" value="{{ old('room_number', $room->room_number) }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Room Type</label>
                <select name="room_type_id" class="kt-input w-full">
                    <option value="">-- Select --</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}" {{ old('room_type_id', $room->room_type_id)==$t->id ? 'selected':'' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Floor</label>
                <select name="floor_id" class="kt-input w-full">
                    <option value="">-- Select --</option>
                    @foreach($floors as $f)
                        <option value="{{ $f->id }}" {{ old('floor_id', $room->floor_id)==$f->id ? 'selected':'' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Status</label>
                <select name="status" class="kt-input w-full">
                    <option value="available" {{ old('status', $room->status)=='available' ? 'selected':'' }}>Available</option>
                    <option value="occupied" {{ old('status', $room->status)=='occupied' ? 'selected':'' }}>Occupied</option>
                    <option value="reserved" {{ old('status', $room->status)=='reserved' ? 'selected':'' }}>Reserved</option>
                    <option value="dirty" {{ old('status', $room->status)=='dirty' ? 'selected':'' }}>Dirty</option>
                    <option value="clean" {{ old('status', $room->status)=='clean' ? 'selected':'' }}>Clean</option>
                    <option value="maintenance" {{ old('status', $room->status)=='maintenance' ? 'selected':'' }}>Maintenance</option>
                    <option value="out_of_service" {{ old('status', $room->status)=='out_of_service' ? 'selected':'' }}>Out of Service</option>
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Notes</label>
                <textarea class="kt-input w-full" name="notes">{{ old('notes', $room->notes) }}</textarea>
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Amenities</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($amenities as $a)
                        @php $oldAmenities = old('amenities', $room->amenities->pluck('id')->toArray()); @endphp
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="amenities[]" value="{{ $a->id }}" @if(in_array($a->id, (array)$oldAmenities)) checked @endif> {{ $a->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Active</label>
                <div>
                    <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }} /> Enabled</label>
                </div>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                <a class="kt-btn" href="{{ route('admin.rooms.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
