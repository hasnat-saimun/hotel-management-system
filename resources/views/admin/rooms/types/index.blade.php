@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Room Types</h3>
            <div class="text-sm text-secondary-foreground">Manage room type definitions (capacity, base price, amenities)</div>
        </div>
        <div>
            <a href="{{ route('admin.rooms.types.create') }}" class="kt-btn kt-btn-primary">Add Type</a>
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
                @foreach($types as $type)
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
