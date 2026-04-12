@extends('admin.layouts.app')
@section('title', 'Create Room Block')

@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Create Room Block</h3>
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

        <form method="POST" action="{{ route('admin.room-blocks.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf

            <div>
                <label class="text-sm text-secondary-foreground required-label">Group Name</label>
                <input class="kt-input w-full" name="group_name" required value="{{ old('group_name') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground required-label">Status</label>
                <select class="kt-input w-full" name="status" required>
                    @foreach(['tentative','confirmed','cancelled'] as $st)
                        <option value="{{ $st }}" {{ old('status','tentative')===$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground required-label">Start Date</label>
                <input type="date" class="kt-input w-full" name="start_date" required value="{{ old('start_date') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground required-label">End Date</label>
                <input type="date" class="kt-input w-full" name="end_date" required value="{{ old('end_date') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Release Deadline (auto-expire)</label>
                <input type="datetime-local" class="kt-input w-full" name="release_at" value="{{ old('release_at') }}" />
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Notes</label>
                <textarea class="kt-input w-full" name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>

            <div class="lg:col-span-2">
                <div class="p-3 rounded bg-muted/30">
                    <div class="font-medium mb-2">Assign Rooms</div>
                    <div class="text-sm text-muted-foreground mb-3">Choose ONE mode: select specific rooms OR auto-assign by room type.</div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-secondary-foreground">Specific Rooms (optional)</label>
                            <select name="room_ids[]" multiple class="kt-input w-full" size="10">
                                @foreach($rooms as $r)
                                    <option value="{{ $r->id }}" {{ in_array($r->id, (array) old('room_ids', [])) ? 'selected':'' }}>
                                        {{ $r->room_number }} ({{ $r->roomType->name ?? 'Type' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-secondary-foreground">Auto-Assign by Room Type</label>
                            <div class="grid grid-cols-1 gap-2">
                                <select name="room_type_id" class="kt-input w-full">
                                    <option value="">-- Select Room Type --</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t->id }}" {{ old('room_type_id')==$t->id ? 'selected':'' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <input class="kt-input w-full" type="number" min="1" name="total_rooms" placeholder="Total rooms" value="{{ old('total_rooms') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Create Block</button>
                <a class="kt-btn" href="{{ route('admin.room-blocks.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
