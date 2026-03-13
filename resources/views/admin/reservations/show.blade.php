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
            @php
                $guest = $reservation->guest;
                $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
                $guestName = $guestName !== '' ? $guestName : '-';

                $room = $reservation->rooms->first();
                $floorName = $room?->floor?->name;
                $roomNumber = $room?->room_number ?? '-';

                $reservationRoom = $reservation->reservationRooms->first();
                $roomType = $reservationRoom?->roomType ?? ($room?->roomType ?? null);

                $rawStatus = strtolower($reservation->status ?? 'pending');
                if ($rawStatus === 'confirmed') {
                    $statusLabel = 'Confirmed';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-success';
                } elseif ($rawStatus === 'pending') {
                    $statusLabel = 'Pending';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-info';
                } elseif (in_array($rawStatus, ['checked-in', 'checkedin', 'checked_in'], true)) {
                    $statusLabel = 'Checked-in';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-primary';
                } elseif (in_array($rawStatus, ['checked-out', 'checkedout', 'checked_out'], true)) {
                    $statusLabel = 'Checked-out';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-secondary';
                } elseif ($rawStatus === 'cancelled') {
                    $statusLabel = 'Cancelled';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-destructive';
                } elseif (in_array($rawStatus, ['no-show', 'noshow'], true)) {
                    $statusLabel = 'No-show';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-warning';
                } elseif ($rawStatus === 'booked') {
                    $statusLabel = 'Booked';
                    $statusBadgeClass = 'kt-badge-outline kt-badge-info';
                } else {
                    $statusLabel = ucfirst($reservation->status ?? 'Pending');
                    $statusBadgeClass = 'kt-badge-outline kt-badge-info';
                }

                $channelLabel = $reservation->channel ? ucfirst($reservation->channel) : '-';
                $checkInDisplay = $reservation->check_in_date ? \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') : '-';
                $checkOutDisplay = $reservation->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') : '-';

                $roomTypeName = $roomType?->name ?? '-';
                $capacityAdults = $roomType?->capacity_adults;
                $capacityChildren = $roomType?->capacity_children;
                $roomPrice = $reservationRoom?->nightly_rate ?? ($reservation->rate ?? ($roomType?->base_price ?? null));
            @endphp

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
                            <span class="text-sm font-normal text-secondary-foreground"> {{ $floorName }}</span>
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
                                <dt class="text-xs text-secondary-foreground">Price</dt>
                                <dd class="col-span-2 text-sm font-medium text-mono">
                                    {{ $roomPrice !== null ? number_format((float) $roomPrice, 2) : '-' }}
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
                                <dt class="text-xs text-secondary-foreground">Status</dt>
                                <dd><span class="kt-badge kt-badge-sm {{ $statusBadgeClass }}">{{ $statusLabel }}</span></dd>
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
                                <dt class="text-xs text-secondary-foreground">Check-out</dt>
                                <dd class="text-sm font-medium text-mono">{{ $checkOutDisplay }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                @if($rawStatus === 'confirmed')
                    <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.checkin', $reservation->id) }}">Check-in</a>
                @elseif(in_array($rawStatus, ['checked-in', 'checkedin', 'checked_in'], true))
                    <a class="kt-btn kt-btn-destructive" onclick="return confirm('Are you sure you want to check out this reservation?')" href="{{ route('admin.reservations.checkout', $reservation->id) }}">Check-out</a>
                @endif

            </div>
            @else
            <div>No reservation found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
