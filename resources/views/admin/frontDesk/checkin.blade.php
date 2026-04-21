@extends('admin.layouts.app')

@section('content')
    @php
        $guestName = trim(($reservation->guest?->first_name ?? '') . ' ' . ($reservation->guest?->last_name ?? ''));
        $roomNumbers = $reservation->reservationRooms
            ? $reservation->reservationRooms->pluck('room.room_number')->filter()->values()
            : collect();
        $isCheckedIn = $reservation->reservationRooms
            && $reservation->reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');
        $nights = 0;
        if ($reservation->check_in_date && $reservation->check_out_date) {
            try {
                $nights = $reservation->check_in_date->diffInDays($reservation->check_out_date);
            } catch (Throwable $e) {
                $nights = 0;
            }
        }
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-col">
            <h1 class="text-xl font-medium">Confirm Check-In</h1>
            <div class="text-sm text-secondary-foreground">Front Desk · Arrivals</div>
        </div>
        <div class="flex items-center gap-2">
            <a class="kt-btn" href="{{ route('admin.front-desk.arrivals') }}">Back to Arrivals</a>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-header">
            <h3 class="kt-card-title">Reservation</h3>
        </div>
        <div class="kt-card-content p-5 grid gap-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-secondary-foreground">Guest</div>
                    <div class="text-sm font-medium text-foreground">{{ $guestName !== '' ? $guestName : '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-foreground">Phone</div>
                    <div class="text-sm font-medium text-foreground">{{ $reservation->guest?->phone ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-foreground">Room</div>
                    <div class="text-sm font-medium text-foreground">{{ $roomNumbers->isEmpty() ? '-' : $roomNumbers->join(', ') }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-foreground">Check-in date</div>
                    <div class="text-sm font-medium text-foreground">{{ $reservation->check_in_date?->format('M d, Y') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-foreground">Nights</div>
                    <div class="text-sm font-medium text-foreground">{{ $nights }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-foreground">Status</div>
                    <div class="text-sm font-medium text-foreground">{{ ucfirst($reservation->status ?? '-') }}</div>
                </div>
            </div>

            <div class="border-t border-border pt-4 flex items-center justify-end gap-2">
                @if($isCheckedIn)
                    <button class="kt-btn kt-btn-outline" type="button" disabled>Already Checked In</button>
                @else
                    <form method="POST" action="{{ route('admin.front-desk.arrivals.check-in.store', $reservation->id) }}">
                        @csrf
                        <input type="hidden" name="confirm" value="1" />
                        <button type="submit" class="kt-btn kt-btn-primary">Confirm Check-In</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
