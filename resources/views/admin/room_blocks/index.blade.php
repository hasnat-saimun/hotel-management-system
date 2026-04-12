@extends('admin.layouts.app')
@section('title', 'Room Blocks')

@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <h3 class="kt-card-title">Room Blocks</h3>
        <a class="kt-btn kt-btn-primary" href="{{ route('admin.room-blocks.create') }}">Create Block</a>
    </div>
    <div class="kt-card-content p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="kt-table w-full">
                <thead>
                    <tr>
                        <th class="text-left p-2">#</th>
                        <th class="text-left p-2">Group</th>
                        <th class="text-left p-2">Dates</th>
                        <th class="text-left p-2">Rooms</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">Release</th>
                        <th class="text-right p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blocks as $b)
                        <tr class="border-t">
                            <td class="p-2">{{ $b->id }}</td>
                            <td class="p-2">{{ $b->group_name }}</td>
                            <td class="p-2">{{ optional($b->start_date)->toDateString() }} → {{ optional($b->end_date)->toDateString() }}</td>
                            <td class="p-2">{{ $b->room_block_rooms_count ?? $b->total_rooms }}</td>
                            <td class="p-2">{{ ucfirst($b->status) }}</td>
                            <td class="p-2">
                                @if($b->released_at)
                                    Released
                                @elseif($b->release_at)
                                    {{ $b->release_at->toDayDateTimeString() }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-2 text-right">
                                <a class="kt-btn" href="{{ route('admin.room-blocks.show', $b->id) }}">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-3 text-center" colspan="7">No room blocks yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
