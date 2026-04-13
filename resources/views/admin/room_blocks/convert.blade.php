@extends('admin.layouts.app')
@section('title', 'Convert Block to Reservations')

@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Convert Block #{{ $block->id }} — {{ $block->group_name }}</h3>
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
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        @if(($block->status ?? null) !== 'confirmed' || !empty($block->released_at))
            <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                This block must be <strong>confirmed</strong> and not released before converting to reservations.
            </div>
        @endif

        <div class="mb-4 p-3 rounded bg-muted/30">
            <div class="font-medium">Dates</div>
            <div class="text-sm">{{ $block->start_date->toDateString() }} → {{ $block->end_date->toDateString() }}</div>
        </div>

        <form method="POST" action="{{ route('admin.room-blocks.convert.store', $block->id) }}" class="grid gap-4">
            @csrf

            <div class="p-3 rounded bg-muted/30">
                <div class="font-medium mb-2">Bulk Guest Import (optional)</div>
                <div class="text-sm text-muted-foreground mb-2">Format per line: <span class="font-mono">first_name,last_name,email,phone</span>. Imported guests will be applied sequentially to unassigned rooms.</div>
                <textarea class="kt-input w-full" name="bulk_guest_csv" rows="4" placeholder="John,Doe,john@example.com,555-1234">{{ old('bulk_guest_csv') }}</textarea>
            </div>

            <div>
                <div class="font-medium mb-2">Rooms to Convert</div>
                <div class="overflow-x-auto">
                    <table class="kt-table w-full">
                        <thead>
                            <tr>
                                <th class="p-2">Convert</th>
                                <th class="text-left p-2">Room</th>
                                <th class="text-left p-2">Type</th>
                                <th class="text-left p-2">Existing Guest</th>
                                <th class="text-left p-2">Or Create Guest</th>
                                <th class="text-left p-2">Rate Plan</th>
                                <th class="text-left p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($block->roomBlockRooms as $r)
                                <tr class="border-t align-top">
                                    <td class="p-2">
                                        @if($r->status !== 'converted')
                                            <input type="checkbox" name="room_block_room_ids[]" value="{{ $r->id }}" {{ in_array($r->id, (array) old('room_block_room_ids', [])) ? 'checked':'' }}>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="p-2">{{ $r->room->room_number ?? '—' }}</td>
                                    <td class="p-2">{{ $r->room->roomType->name ?? '—' }}</td>
                                    <td class="p-2">
                                        <select class="kt-input w-full" name="assignments[{{ $r->id }}][guest_id]">
                                            <option value="">-- Select --</option>
                                            @foreach($guests as $g)
                                                <option value="{{ $g->id }}" {{ old('assignments.'.$r->id.'.guest_id', $r->assigned_guest_id)==$g->id ? 'selected':'' }}>
                                                    {{ $g->first_name }} {{ $g->last_name }} ({{ $g->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <div class="grid gap-2">
                                            <input class="kt-input w-full" name="assignments[{{ $r->id }}][guest][first_name]" placeholder="First name" value="{{ old('assignments.'.$r->id.'.guest.first_name') }}" />
                                            <input class="kt-input w-full" name="assignments[{{ $r->id }}][guest][last_name]" placeholder="Last name" value="{{ old('assignments.'.$r->id.'.guest.last_name') }}" />
                                            <input class="kt-input w-full" name="assignments[{{ $r->id }}][guest][email]" placeholder="Email" value="{{ old('assignments.'.$r->id.'.guest.email') }}" />
                                            <input class="kt-input w-full" name="assignments[{{ $r->id }}][guest][phone]" placeholder="Phone" value="{{ old('assignments.'.$r->id.'.guest.phone') }}" />
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <input class="kt-input w-full" name="assignments[{{ $r->id }}][rate_plan_named]" placeholder="Group Rate" value="{{ old('assignments.'.$r->id.'.rate_plan_named') }}" />
                                    </td>
                                    <td class="p-2">{{ ucfirst($r->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit" {{ (($block->status ?? null) !== 'confirmed' || !empty($block->released_at)) ? 'disabled' : '' }}>Convert Selected</button>
                <a class="kt-btn" href="{{ route('admin.room-blocks.show', $block->id) }}">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
