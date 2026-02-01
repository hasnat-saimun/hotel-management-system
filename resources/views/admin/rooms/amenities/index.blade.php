@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Amenities</h3>
            <div class="text-sm text-secondary-foreground">Master list of amenities</div>
        </div>
        <div>
            <a href="{{ route('admin.rooms.amenities.create') }}" class="kt-btn kt-btn-primary">Add Amenity</a>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <ul class="space-y-2">
            @foreach($amenities as $a)
                <li class="flex items-center justify-between border p-2">
                    <div class="flex items-center gap-3"><span class="inline-block w-6">{!! $a->icon ?? '' !!}</span> <span>{{ $a->name }}</span></div>
                    <div class="flex gap-2">
                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.rooms.amenities.edit', $a->id) }}">Edit</a>
                        <form action="{{ route('admin.rooms.amenities.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Delete this amenity?');">
                            @csrf
                            @method('DELETE')
                            <button class="kt-btn kt-btn-danger kt-btn-sm">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
