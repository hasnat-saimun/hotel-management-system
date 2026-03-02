@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Walk-in Booking</h3>
        <div class="text-sm text-secondary-foreground">Quick create reservation for walk-in guests</div>
    </div>
    <div class="kt-card-content p-4">
        <form method="POST" action="{{ route('admin.reservations.walkin.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground">Guest name</label>
                <input class="kt-input w-full" name="guest_name" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Select Room(s)</label>
                    <input type="checkbox" id="select-all-rooms" />
                    <label for="select-all-rooms" class="text-sm">Select all</label>

                <div class="room-multiselect relative">
                    <div class="border rounded p-2 flex flex-wrap items-center gap-2" id="rooms-multiselect-box">
                        <div id="selected-rooms" class="flex flex-wrap gap-2"></div>
                        <input id="rooms-search-input" type="text" class="kt-input flex-1 min-w-[150px]" placeholder="Type to search rooms...">
                    </div>
                    <ul id="rooms-options" class="absolute z-50 bg-white border rounded mt-1 w-full max-h-48 overflow-auto hidden"></ul>
                </div>

                <div id="rooms-hidden-inputs">
                    @if(is_array(old('room_ids')))
                        @foreach(old('room_ids') as $rid)
                            <input type="hidden" name="room_ids[]" value="{{ $rid }}" />
                        @endforeach
                    @endif
                </div>

                <input type="hidden" name="room_number" value="" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Check-in</label>
                <input type="date" class="kt-input w-full" name="check_in_date" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Check-out</label>
                <input type="date" class="kt-input w-full" name="check_out_date" />
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="kt-btn kt-btn-primary">Create</button>
                <a class="kt-btn" href="{{ route('admin.reservations.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// JavaScript for room multiselect with search
</script>
@endpush
