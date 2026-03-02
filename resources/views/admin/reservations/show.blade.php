@extends('admin.layouts.app')
@section('content')
<div class="grid gap-5">
    <div class="kt-card">
        <div class="kt-card-header flex items-center justify-between">
            <div>
                <h3 class="kt-card-title">Reservation Details</h3>
                <div class="text-sm text-secondary-foreground">Guest, room(s), dates, payments and notes</div>
            </div>
            <div class="flex gap-2">
                <a class="kt-btn" href="{{ route('admin.reservations.index') }}">Back to list</a>
            </div>
        </div>
        <div class="kt-card-content p-4">
            @if($reservation)
            <div class="grid lg:grid-cols-3 gap-4">
                <div class="kt-card p-3">
                    <p class="text-sm text-secondary-foreground">Guest</p>
                    <p class="font-medium">{{ $reservation->guest->first_name ?? '-' }} {{ $reservation->guest->last_name ?? '-' }}</p>
                </div>
                <div class="kt-card p-3">
                    <p class="text-sm text-secondary-foreground">Room</p>
                    <p class="font-medium">{{ $reservation->rooms->first()->room_number ?? '-' }}</p>
                </div>
                <div class="kt-card p-3">
                    <p class="text-sm text-secondary-foreground">Status</p>
                    <p class="font-medium">
                         @php
                                    $status = strtolower($reservation->status ?? 'pending');
                                @endphp
                                @if($status == 'confirmed')
                                <p> Confirmed</p>
                                @elseif($status == 'pending')
                                    <p>Pending</p>
                                @elseif($status == 'checked-in' || $status == 'checkedin' || $status == 'checked_in')
                                    <p>Checked-in</p>
                                    @elseif($status == 'checked-out' || $status == 'checkedout' || $status == 'checked_out')
                                    <p>Checked-out</p>
                                @elseif($status == 'cancelled')
                                    <p>Cancelled</p>
                                
                                @elseif($status == 'no-show' || $status == 'noshow')
                                    <p>No-show</p>
                                @elseif($status == 'booked' )
                                    <p>Booked</p>
                                @else
                                    {{ ucfirst($reservation->status ?? 'Pending') }}
                                @endif
                    </p>
                </div>
            </div>

            <div class="mt-4 kt-card p-3">
                <p class="text-sm text-secondary-foreground">Dates</p>
                <p>{{ $reservation->check_in_date ?? '-' }} → {{ $reservation->check_out_date ?? '-' }}</p>
            </div>

            <div class="mt-4 flex gap-2">
                 @php       
                    $checkOutDateTime = \Carbon\Carbon::parse($reservation->check_out_date);
                    $currentDateTime = \Carbon\Carbon::now();
                    $status = strtolower($reservation->status );
                @endphp
                @if($currentDateTime->greaterThanOrEqualTo($checkOutDateTime ) && $status == 'checked-in' || $status == 'checkedin' || $status == 'checked_in')
                <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.checkin', $reservation->id) }}">Check-in</a>
                <a class="kt-btn kt-btn-destructive" onclick="return confirm('Are you sure you want to check out this reservation?')" href="{{ route('admin.reservations.checkout', $reservation->id) }}">Check-out</a>
                @else
                <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.checkin', $reservation->id) }}">Check-in</a>
                @endif

            </div>
            @else
            <div>No reservation found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
