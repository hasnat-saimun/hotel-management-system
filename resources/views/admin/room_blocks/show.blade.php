@extends('admin.layouts.app')
@section('title', 'Manage Room Block')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <h3 class="kt-card-title">Block #{{ $block->id }} — {{ $block->group_name }}</h3>
                <div class="flex gap-2">
                    @if(($block->status ?? null) === 'confirmed' && empty($block->released_at))
                        <a class="kt-btn kt-btn-primary" href="{{ route('admin.room-blocks.convert', $block->id) }}">Create Reservations</a>
                    @endif
                    <form method="POST" action="{{ route('admin.room-blocks.release', $block->id) }}">
                        @csrf
                        <button class="kt-btn" type="submit" onclick="return confirm('Release this block inventory?')">Release</button>
                    </form>
                </div>
            </div>
            <div class="kt-card-content p-4">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div class="p-3 rounded bg-muted/30">
                        <div class="text-sm text-muted-foreground">Dates</div>
                        <div class="font-medium">{{ $block->start_date->toDateString() }} → {{ $block->end_date->toDateString() }}</div>
                    </div>
                    <div class="p-3 rounded bg-muted/30">
                        <div class="text-sm text-muted-foreground">Status</div>
                        <div class="font-medium">{{ ucfirst($block->status) }} @if($block->released_at) (Released) @endif</div>
                    </div>
                </div>

                <div class="p-3 rounded bg-muted/30 mb-4">
                    <div class="text-sm text-muted-foreground">Rooms blocked</div>
                    <div class="font-medium">{{ $block->roomBlockRooms->where('status','blocked')->count() }} blocked, {{ $block->roomBlockRooms->where('status','converted')->count() }} converted</div>
                </div>

                <div class="font-medium mb-2">Assigned Rooms</div>

                <form method="POST" action="{{ route('admin.room-blocks.unassign-rooms', $block->id) }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="kt-table w-full">
                            <thead>
                                <tr>
                                    <th class="p-2"></th>
                                    <th class="text-left p-2">Room</th>
                                    <th class="text-left p-2">Type</th>
                                    <th class="text-left p-2">Guest (planned)</th>
                                    <th class="text-left p-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($block->roomBlockRooms as $r)
                                    <tr class="border-t">
                                        <td class="p-2">
                                            @if($r->status !== 'converted')
                                                <input type="checkbox" name="room_block_room_ids[]" value="{{ $r->id }}">
                                            @endif
                                        </td>
                                        <td class="p-2">{{ $r->room->room_number ?? '—' }}</td>
                                        <td class="p-2">{{ $r->room->roomType->name ?? '—' }}</td>
                                        <td class="p-2">
                                            @if($r->assignedGuest)
                                                {{ $r->assignedGuest->first_name }} {{ $r->assignedGuest->last_name }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="p-2">{{ ucfirst($r->status) }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="p-3 text-center" colspan="5">No rooms assigned yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <button class="kt-btn" type="submit" onclick="return confirm('Unassign selected rooms?')">Unassign Selected</button>
                        <a class="kt-btn" href="{{ route('admin.room-blocks.index') }}">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="kt-card mb-4">
            <div class="kt-card-header">
                <h3 class="kt-card-title">Update Block</h3>
            </div>
            <div class="kt-card-content p-4">
                <form method="POST" action="{{ route('admin.room-blocks.update', $block->id) }}" class="grid gap-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Group Name</label>
                        <input class="kt-input w-full" name="group_name" required value="{{ old('group_name', $block->group_name) }}" />
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Status</label>
                        <select class="kt-input w-full" name="status" required>
                            @foreach(['tentative','confirmed','cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $block->status)===$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground">Release Deadline</label>
                        <input type="datetime-local" class="kt-input w-full" name="release_at" value="{{ old('release_at', optional($block->release_at)->format('Y-m-d\TH:i')) }}" />
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground">Notes</label>
                        <textarea class="kt-input w-full" name="notes" rows="3">{{ old('notes', $block->notes) }}</textarea>
                    </div>
                    <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                </form>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title">Assign More Rooms</h3>
            </div>
            <div class="kt-card-content p-4">
                <form method="POST" action="{{ route('admin.room-blocks.assign-rooms', $block->id) }}" class="grid gap-3">
                    @csrf
                    <div>
                        <label class="text-sm text-secondary-foreground">Available rooms for these dates</label>
                        <select name="room_ids[]" multiple class="kt-input w-full" size="10">
                            @foreach($availableRooms as $r)
                                <option value="{{ $r->id }}">{{ $r->room_number }} ({{ $r->roomType->name ?? 'Type' }})</option>
                            @endforeach
                        </select>
                        <div class="text-xs text-muted-foreground mt-1">Only rooms not booked and not blocked by other active blocks are shown.</div>
                    </div>
                    <button class="kt-btn" type="submit">Assign Selected</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
