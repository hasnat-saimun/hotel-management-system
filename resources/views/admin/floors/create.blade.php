@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Add Floor</h3>
    </div>
    <div class="kt-card-content p-4">
        @if($errors->any())
            <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                <ul class="list-disc ps-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.rooms.floors.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground required-label">Name</label>
                <input class="kt-input w-full" name="name" required value="{{ old('name') }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground required-label">Level Number</label>
                <input class="kt-input w-full" name="level_number" required value="{{ old('level_number') }}" />
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="kt-btn kt-btn-primary">Create</button>
                <a class="kt-btn" href="{{ route('admin.rooms.floors.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
