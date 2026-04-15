@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Edit Loyalty Level</h3>
            <div class="text-sm text-secondary-foreground">Update level</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.loyalties.index') }}">Back</a>
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

        <form method="POST" action="{{ route('admin.loyalties.update', $loyalty->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm text-secondary-foreground required-label">Level name</label>
                <input type="text" class="kt-input w-full" name="level_name" value="{{ old('level_name', $loyalty->level_name) }}" required />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Discount percentage</label>
                <input type="number" step="0.01" min="0" max="100" class="kt-input w-full" name="discount_percentage" value="{{ old('discount_percentage', $loyalty->discount_percentage) }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Points required</label>
                <input type="number" min="0" class="kt-input w-full" name="points_required" value="{{ old('points_required', $loyalty->points_required) }}" />
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Update</button>
                <a class="kt-btn" href="{{ route('admin.loyalties.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
