@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Add Room Type</h3>
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
        <form method="POST" action="{{ route('admin.rooms.types.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground required-label">Name</label>
                <input class="kt-input w-full" name="name" required id="room-type-name" value="{{ old('name') }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground required-label">Capacity</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" class="kt-input w-full" name="capacity_adults" value="{{ old('capacity_adults') }}" required placeholder="Adults" />
                    <input type="number" class="kt-input w-full" name="capacity_children" value="{{ old('capacity_children') }}" placeholder="Children" />
                </div>
            </div>
            <div>
                <label class="text-sm text-secondary-foreground required-label">Base Price</label>
                <input class="kt-input w-full" name="base_price" value="{{ old('base_price',0) }}" required />
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Description (comma separated)</label>
                <textarea class="kt-input w-full" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Active</label>
                <div>
                    <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active',1) ? 'checked' : '' }} /> Enabled</label>
                </div>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Create</button>
                <a class="kt-btn" href="{{ route('admin.rooms.types.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
