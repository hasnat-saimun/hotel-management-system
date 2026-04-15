@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Loyalty Program</h3>
            <div class="text-sm text-secondary-foreground">Levels & discounts</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.loyalties.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" class="kt-input" placeholder="Search level" value="{{ request('q') }}" />
                <button class="kt-btn" type="submit">Search</button>
            </form>
            <a href="{{ route('admin.loyalties.create') }}" class="kt-btn kt-btn-primary">Add Level</a>
        </div>
    </div>

    <div class="kt-card-content p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">SL</th>
                    <th class="p-2">Level</th>
                    <th class="p-2">Discount %</th>
                    <th class="p-2">Points required</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loyalties as $l)
                    <tr class="border-t hover:bg-muted/10">
                        <td class="p-2">{{ $loyalties->firstItem() ? $loyalties->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td class="p-2">{{ $l->level_name }}</td>
                        <td class="p-2">{{ number_format((float)($l->discount_percentage ?? 0), 2) }}</td>
                        <td class="p-2">{{ (int)($l->points_required ?? 0) }}</td>
                        <td class="p-2">
                            <a class="kt-btn kt-btn-sm" href="{{ route('admin.loyalties.edit', $l->id) }}">Edit</a>
                            <form action="{{ route('admin.loyalties.destroy', $l->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this loyalty level?');">
                                @csrf
                                @method('DELETE')
                                <button class="kt-btn kt-btn-danger kt-btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-secondary-foreground">No loyalty levels found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $loyalties->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
