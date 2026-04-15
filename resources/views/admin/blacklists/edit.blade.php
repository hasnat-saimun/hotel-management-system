@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Edit Blacklist Entry</h3>
            <div class="text-sm text-secondary-foreground">Update blocked guest</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.blacklists.index') }}">Back</a>
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

        <form method="POST" action="{{ route('admin.blacklists.update', $blacklist->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Guest</label>
                <select class="kt-input w-full" name="guest_id" required>
                    @php($selected = old('guest_id', $blacklist->guest_id))
                    <option value="">Select guest</option>
                    @foreach($guests as $g)
                        <option value="{{ $g->id }}" @selected((string)$selected === (string)$g->id)>
                            {{ $g->first_name }} {{ $g->last_name }} {{ $g->phone ? '(' . $g->phone . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Reason</label>
                <textarea class="kt-input w-full" name="reason" rows="3" required>{{ old('reason', $blacklist->reason) }}</textarea>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Blocked until</label>
                <input type="date" class="kt-input w-full" name="blocked_until" value="{{ old('blocked_until', optional($blacklist->blocked_until)->format('Y-m-d')) }}" />
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Update</button>
                <a class="kt-btn" href="{{ route('admin.blacklists.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
