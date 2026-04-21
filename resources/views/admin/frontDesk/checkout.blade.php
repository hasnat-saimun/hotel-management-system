@extends('admin.layouts.app')

@section('content')
    @php
        $reservation = $stay->reservation;
        $guest = $reservation?->guest;
        $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
        $guestName = $guestName !== '' ? $guestName : '-';

        $room = $stay->room;
        $roomNumber = $room?->room_number ?? '-';
        $floorName = $room?->floor?->name;

        $roomNumbers = $reservation?->reservationRooms
            ? $reservation->reservationRooms->pluck('room.room_number')->filter()->values()
            : collect();

        $reservationRoom = $reservation?->reservationRooms
            ? $reservation->reservationRooms->firstWhere('room_id', $stay->room_id)
            : null;

        $roomType = $reservationRoom?->roomType ?? ($room?->roomType ?? null);
        $roomTypeName = $roomType?->name ?? '-';
        $capacityAdults = $roomType?->capacity_adults;
        $capacityChildren = $roomType?->capacity_children;
        $roomPrice = $reservationRoom?->nightly_rate ?? ($reservation?->rate ?? ($roomType?->base_price ?? null));

        $channelLabel = $reservation?->channel ? ucfirst($reservation->channel) : '-';

        $rawReservationStatus = strtolower((string) ($reservation?->status ?? ''));
        if ($rawReservationStatus === 'cancelled') {
            $reservationStatusLabel = 'Cancelled';
            $reservationStatusBadgeClass = 'kt-badge-outline kt-badge-destructive';
        } elseif ($rawReservationStatus === 'confirmed') {
            $reservationStatusLabel = 'Confirmed';
            $reservationStatusBadgeClass = 'kt-badge-outline kt-badge-success';
        } elseif ($rawReservationStatus === 'booked') {
            $reservationStatusLabel = 'Booked';
            $reservationStatusBadgeClass = 'kt-badge-outline kt-badge-info';
        } else {
            $reservationStatusLabel = $reservation?->status ? ucfirst($reservation->status) : '-';
            $reservationStatusBadgeClass = 'kt-badge-outline kt-badge-info';
        }

        $stayStatusLabel = (($stay->status ?? null) === 'in_house') ? 'In-House' : ucfirst((string) ($stay->status ?? '-'));

        $checkInDisplay = $reservation?->check_in_date
            ? \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y')
            : ($stay->check_in_time ? $stay->check_in_time->format('M d, Y') : '-');

        $expectedOutDisplay = $reservation?->check_out_date
            ? \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y')
            : '-';

        $nights = 0;
        if ($reservation?->check_in_date && $reservation?->check_out_date) {
            try {
                $nights = $reservation->check_in_date->diffInDays($reservation->check_out_date);
            } catch (Throwable $e) {
                $nights = 0;
            }
        }
    @endphp

    <div class="grid gap-5">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <div>
                    <h3 class="kt-card-title">Check-Out</h3>
                    <div class="text-sm text-secondary-foreground">Finalize departure and mark room dirty</div>
                </div>
                <div class="flex gap-2">
                    <a class="kt-btn" href="{{ route('admin.front-desk.departures') }}">Back to departures</a>
                </div>
            </div>
            <div class="kt-card-content p-4">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Guest</div>
                        <div class="mt-1 text-base font-semibold text-mono break-words">{{ $guestName }}</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Address</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->address ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Email</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->email ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Phone</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->phone ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">ID Type</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono">{{ $guest?->id_type ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">ID Number</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->id_number ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Floor / Room</div>
                        <div class="mt-1 text-base font-semibold text-mono break-words">
                            @if($floorName)
                                <span class="text-sm font-normal text-secondary-foreground">{{ $floorName }}</span>
                            @endif
                            <span class="text-sm font-normal text-secondary-foreground"> • </span>
                            {{ $roomNumber }}
                        </div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Room Type</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $roomTypeName }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Capacity</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono">
                                        {{ $capacityAdults !== null ? $capacityAdults : '-' }} adults
                                        <span class="text-secondary-foreground font-normal">•</span>
                                        {{ $capacityChildren !== null ? $capacityChildren : '-' }} children
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Guests</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono">
                                        {{ (int) ($reservation?->adults ?? 1) }} adults
                                        <span class="text-secondary-foreground font-normal">•</span>
                                        {{ (int) ($reservation?->children ?? 0) }} children
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Price</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono">
                                        {{ $roomPrice !== null ? number_format((float) $roomPrice, 2) : '-' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Nights</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono">{{ $nights }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Room(s)</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">
                                        {{ $roomNumbers->isEmpty() ? '-' : $roomNumbers->join(', ') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Channel / Status</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Channel</dt>
                                    <dd><span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">{{ $channelLabel }}</span></dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Reservation</dt>
                                    <dd><span class="kt-badge kt-badge-sm {{ $reservationStatusBadgeClass }}">{{ $reservationStatusLabel }}</span></dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Stay</dt>
                                    <dd><span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">{{ $stayStatusLabel }}</span></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Stay Dates</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Check-in</dt>
                                    <dd class="text-sm font-medium text-mono">{{ $checkInDisplay }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Expected check-out</dt>
                                    <dd class="text-sm font-medium text-mono">{{ $expectedOutDisplay }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Outstanding Balance</dt>
                                    <dd class="text-sm font-medium text-mono">--</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 justify-end">
                    <form method="POST" action="{{ route('admin.front-desk.departures.check-out.store', $stay->id) }}">
                        @csrf
                        <input type="hidden" name="confirm" value="1" />
                        <button type="submit" class="kt-btn kt-btn-primary">Confirm Check-Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
