@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Add Room Type</h3>
    </div>
    <div class="kt-card-content p-4">
        <form method="POST" action="{{ route('admin.rooms.types.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground">Name</label>
                <input class="kt-input w-full" name="name" value="{{ old('name') }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Capacity</label>
                <input type="number" class="kt-input w-full" name="capacity" value="{{ old('capacity',1) }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Base Price</label>
                <input class="kt-input w-full" name="base_price" value="{{ old('base_price') }}" />
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Amenities (comma separated)</label>
                <input class="kt-input w-full" name="amenities" value="{{ old('amenities') }}" />
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Create</button>
                <a class="kt-btn" href="{{ route('admin.rooms.types.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
