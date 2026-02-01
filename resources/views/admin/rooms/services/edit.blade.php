@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Edit Service {{ $service->name }}</h3>
    </div>
    <div class="kt-card-content p-4">
        <form method="POST" action="{{ route('admin.rooms.services.update', $service->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-secondary-foreground">Name</label>
                <input class="kt-input w-full" name="name" value="{{ old('name', $service->name) }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Price</label>
                <input class="kt-input w-full" name="price" value="{{ old('price', $service->price) }}" />
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Description</label>
                <textarea class="kt-input w-full" name="description">{{ old('description', $service->description) }}</textarea>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                <a class="kt-btn" href="{{ route('admin.rooms.services.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
