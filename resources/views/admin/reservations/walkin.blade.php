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
                <label class="text-sm text-secondary-foreground">Room</label>
                <input class="kt-input w-full" name="room_number" />
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
