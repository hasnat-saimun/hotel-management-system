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
                $guestName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? ''));
                $guestName = $guestName !== '' ? $guestName : '-';

                $room = $reservation->rooms->first();
                $floorName = $room->floor->name ?? null;
                $roomNumber = $room->room_number ?? '-';

                $reservationRoom = $reservation->reservationRooms->first();
                $roomType = $reservationRoom->roomType ?? ($room->roomType ?? null);

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

                $roomTypeName = $roomType->name ?? '-';
                $capacityAdults = $roomType->capacity_adults ?? null;
                $capacityChildren = $roomType->capacity_children ?? null;
                $roomPrice = $reservationRoom->nightly_rate ?? ($reservation->rate ?? ($roomType->base_price ?? null));
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a class="kt-card p-3 block hover:bg-muted/10" data-kt-modal-toggle="#reservation_guest_modal" href="#">
                    <p class="text-sm text-secondary-foreground">Guest</p>
                    <p class="font-medium">{{ $guestName }}</p>
                </a>

                <a class="kt-card p-3 block hover:bg-muted/10" data-kt-modal-toggle="#reservation_room_modal" href="#">
                    <p class="text-sm text-secondary-foreground">Room / Floor</p>
                    <p class="font-medium">
                        {{ $roomNumber }}
                        @if($floorName)
                            • {{ $floorName }}
                        @endif
                    </p>
                </a>

                <a class="kt-card p-3 block hover:bg-muted/10" data-kt-modal-toggle="#reservation_channel_modal" href="#">
                    <p class="text-sm text-secondary-foreground">Channel / Status</p>
                    <p class="font-medium">{{ $channelLabel }} • {{ $statusLabel }}</p>
                </a>

                <a class="kt-card p-3 block hover:bg-muted/10" data-kt-modal-toggle="#reservation_dates_modal" href="#">
                    <p class="text-sm text-secondary-foreground">Dates</p>
                    <p class="font-medium">{{ $checkInDisplay }} → {{ $checkOutDisplay }}</p>
                </a>
            </div>

            <div class="mt-4 flex gap-2">
                @if($rawStatus === 'confirmed')
                    <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.checkin', $reservation->id) }}">Check-in</a>
                @elseif(in_array($rawStatus, ['checked-in', 'checkedin', 'checked_in'], true))
                    <a class="kt-btn kt-btn-destructive" onclick="return confirm('Are you sure you want to check out this reservation?')" href="{{ route('admin.reservations.checkout', $reservation->id) }}">Check-out</a>
                @endif

            </div>

            <div class="kt-modal" data-kt-modal="true" id="reservation_guest_modal">
                <div class="kt-modal-content max-w-[600px] top-[15%]">
                    <div class="kt-modal-header">
                        <h3 class="kt-modal-title">Guest Details</h3>
                        <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>
                    <div class="kt-modal-body grid gap-5">
                        <div class="grid gap-0.5">
                            <div class="text-sm text-secondary-foreground">Guest</div>
                            <div class="text-base font-semibold text-mono">{{ $guestName }}</div>
                        </div>

                        <div class="border-b border-border"></div>

                        <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-4">
                            <div class="sm:col-span-2 py-2">
                                <dt class="text-sm text-secondary-foreground">Address</dt>
                                <dd class="font-medium text-mono break-words">{{ $guest->address ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-foreground">Email</dt>
                                <dd class="font-medium text-mono break-words">{{ $guest->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-foreground">Phone</dt>
                                <dd class="font-medium text-mono break-words">{{ $guest->phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-foreground">ID Type</dt>
                                <dd class="font-medium text-mono">{{ $guest->id_type ?? '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2 py-2">
                                <dt class="text-sm text-secondary-foreground">ID Number</dt>
                                <dd class="font-medium text-mono break-words">{{ $guest->id_number ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="kt-modal" data-kt-modal="true" id="reservation_room_modal">
                <div class="kt-modal-content max-w-[600px] top-[15%]">
                    <div class="kt-modal-header">
                        <h3 class="kt-modal-title">Room Details</h3>
                        <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>
                    <div class="kt-modal-body grid gap-5">
                        <div class="grid gap-0.5">
                            <div class="text-sm text-secondary-foreground">Room / Floor</div>
                            <div class="text-base font-semibold text-mono">
                                {{ $roomNumber }}
                                @if($floorName)
                                    <span class="text-secondary-foreground font-normal">• {{ $floorName }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="border-b border-border"></div>

                        <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-4 ">
                            <div class="sm:col-span-2 py-2">
                                <dt class="text-sm text-secondary-foreground">Room Type</dt>
                                <dd class="font-medium text-mono break-words">{{ $roomTypeName }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-foreground">Capacity</dt>
                                <dd class="font-medium text-mono">
                                    Adults: {{ $capacityAdults !== null ? $capacityAdults : '-' }}
                                    <span class="text-secondary-foreground font-normal">•</span>
                                    Children: {{ $capacityChildren !== null ? $capacityChildren : '-' }}
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm text-secondary-foreground">Price</dt>
                                <dd class="font-medium text-mono">
                                    {{ $roomPrice !== null ? number_format((float) $roomPrice, 2) : '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="kt-modal" data-kt-modal="true" id="reservation_channel_modal">
                <div class="kt-modal-content max-w-[600px] top-[15%]">
                    <div class="kt-modal-header">
                        <h3 class="kt-modal-title">Channel & Status</h3>
                        <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>
                    <div class="kt-modal-body grid gap-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm text-secondary-foreground">Channel</div>
                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">{{ $channelLabel }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm text-secondary-foreground">Status</div>
                            <span class="kt-badge kt-badge-sm {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-modal" data-kt-modal="true" id="reservation_dates_modal">
                <div class="kt-modal-content max-w-[600px] top-[15%]">
                    <div class="kt-modal-header">
                        <h3 class="kt-modal-title">Reservation Dates</h3>
                        <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>
                    <div class="kt-modal-body">
                        <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-4">
                            <div>
                                <dt class="text-sm text-secondary-foreground">Check-in Date</dt>
                                <dd class="text-base font-semibold text-mono">{{ $checkInDisplay }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-foreground">Check-out Date</dt>
                                <dd class="text-base font-semibold text-mono">{{ $checkOutDisplay }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
            @else
            <div>No reservation found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
