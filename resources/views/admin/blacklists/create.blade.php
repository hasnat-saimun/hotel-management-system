@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Add to Blacklist</h3>
            <div class="text-sm text-secondary-foreground">Block a guest</div>
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

        <form method="POST" action="{{ route('admin.blacklists.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Guest</label>
                <select class="kt-input w-full" name="guest_id" required>
                    <option value="">Select guest</option>
                    @foreach($guests as $g)
                        <option value="{{ $g->id }}" @selected((string)old('guest_id') === (string)$g->id)>
                            {{ $g->first_name }} {{ $g->last_name }} {{ $g->phone ? '(' . $g->phone . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Reason</label>
                <textarea class="kt-input w-full" name="reason" rows="3" required>{{ old('reason') }}</textarea>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Blocked until</label>
                <input type="date" class="kt-input w-full" name="blocked_until" value="{{ old('blocked_until') }}" />
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                <a class="kt-btn" href="{{ route('admin.blacklists.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
