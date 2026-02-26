@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Edit Amenity {{ $amenity->name }}</h3>
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
        <form method="POST" action="{{ route('admin.rooms.amenities.update', $amenity->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Name</label>
                <input class="kt-input w-full" name="name" required value="{{ old('name', $amenity->name) }}" />
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Active</label>
                <div>
                    <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $amenity->is_active) ? 'checked' : '' }} /> Enabled</label>
                </div>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                <a class="kt-btn" href="{{ route('admin.rooms.amenities.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
