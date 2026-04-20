@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Edit Reservation</h3>
            <div class="text-sm text-secondary-foreground">Update guest and stay details</div>
        </div>
        <div class="flex gap-2">
            <a class="kt-btn" href="{{ route('admin.reservations.show', $reservation->id) }}">Details</a>
            <a class="kt-btn" href="{{ route('admin.reservations.index') }}">Back to list</a>
        </div>
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

        <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
            <div class="rounded border border-input bg-background p-4 lg:col-span-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-sm font-medium text-foreground">Reservation Summary</div>
                    @php
                        $room = ($reservation->rooms ?? collect())->first();
                        $roomType = $room?->roomType;

                        $prefillCheckIn = $prefill['check_in_date'] ?? null;
                        $prefillCheckOut = $prefill['check_out_date'] ?? null;

                        $checkInDate = $prefillCheckIn
                            ? \Carbon\Carbon::parse($prefillCheckIn)->startOfDay()
                            : ($reservation->check_in_date ? \Carbon\Carbon::parse($reservation->check_in_date)->startOfDay() : null);
                        $checkOutDate = $prefillCheckOut
                            ? \Carbon\Carbon::parse($prefillCheckOut)->startOfDay()
                            : ($reservation->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->startOfDay() : null);

                        $backMonth = $checkInDate ? $checkInDate->format('Y-m') : null;
                        $backDates = $prefill['dates'] ?? '';
                        if ($backDates === '' && $checkInDate && $checkOutDate) {
                            $endInclusive = $checkOutDate->copy()->subDay();
                            if ($endInclusive->greaterThanOrEqualTo($checkInDate)) {
                                $dates = [];
                                for ($d = $checkInDate->copy(); $d->lessThanOrEqualTo($endInclusive); $d->addDay()) {
                                    $dates[] = $d->toDateString();
                                }
                                $backDates = implode(',', $dates);
                            }
                        }

                        $checkInDisplay = $checkInDate ? $checkInDate->format('M d, Y') : '-';
                        $checkOutDisplay = $checkOutDate ? $checkOutDate->format('M d, Y') : '-';
                        $rawStatus = strtolower($reservation->status ?? 'booked');
                    @endphp

                    <a class="kt-btn kt-btn-sm" href="{{ route('admin.reservations.calendar-by-room', array_filter(['room_id' => $room?->id, 'month' => $backMonth, 'dates' => $backDates, 'edit_reservation_id' => $reservation->id], fn ($v) => $v !== null && $v !== '')) }}">Re-edit</a>
                </div>
                <div class="text-xs text-secondary-foreground mt-0.5">Rooms are read-only here</div>

                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Reservation Code</div>
                        <div class="text-sm text-foreground mt-1">{{ $reservation->reservation_code ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Room</div>
                        <div class="text-sm text-foreground mt-1">
                            @if($room)
                                <div class="font-medium">
                                    {{ $room->room_number ?? ('Room #' . $room->id) }}
                                </div>
                                <div class="text-secondary-foreground">
                                    {{ $roomType?->name ?? '-' }}
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-xs font-semibold text-secondary-foreground">Base price (per day)</div>
                                        <div class="text-sm text-foreground mt-1">
                                            @if($roomType?->base_price !== null)
                                                {{ number_format((float) $roomType->base_price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-secondary-foreground">Capacity</div>
                                        <div class="text-sm text-foreground mt-1">
                                            A: {{ (int) ($roomType?->capacity_adults ?? 0) }} - C: {{ (int) ($roomType?->capacity_children ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Stay</div>
                        <div class="mt-2 grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-xs font-semibold text-secondary-foreground">Check-in</div>
                                <div class="text-sm text-foreground mt-1">{{ $checkInDisplay }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-secondary-foreground">Check-out</div>
                                <div class="text-sm text-foreground mt-1">{{ $checkOutDisplay }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Status</div>
                        <div class="mt-2">
                            @if($rawStatus === 'confirmed')
                                <span class="kt-badge kt-badge-outline kt-badge-success">Confirmed</span>
                            @elseif($rawStatus === 'cancelled')
                                <span class="kt-badge kt-badge-outline kt-badge-destructive">Cancelled</span>
                            @elseif(in_array($rawStatus, ['no_show', 'no-show', 'noshow'], true))
                                <span class="kt-badge kt-badge-outline kt-badge-warning">No-show</span>
                            @elseif($rawStatus === 'booked')
                                <span class="kt-badge kt-badge-outline kt-badge-info">Booked</span>
                            @else
                                <span class="kt-badge kt-badge-outline kt-badge-info">{{ ucfirst($reservation->status ?? 'Booked') }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Created</div>
                        <div class="text-sm text-foreground mt-1">
                            {{ $reservation->created_at ? $reservation->created_at->format('M d, Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded border border-input bg-background p-4 lg:col-span-2" id="reservation_edit_form">
                <div class="text-sm font-medium text-foreground">Edit Reservation</div>
                <div class="text-xs text-secondary-foreground mt-0.5">Save to update reservation data</div>

                <form method="POST" action="{{ route('admin.reservations.update', $reservation->id) }}" class="mt-4 grid gap-3 grid-cols-1 lg:grid-cols-2">
                    @csrf
                    @method('PUT')

                    <div class="lg:col-span-2">
                        <label class="text-xs font-semibold text-secondary-foreground">Guest</label>
                        @php($selectedGuestId = old('guest_id', $reservation->guest_id))
                        <select
                            class="kt-select w-full"
                            data-kt-select="true"
                            data-kt-select-placeholder="Select guest"
                            data-kt-select-enable-search="true"
                            data-kt-select-search-placeholder="Search guests..."
                            name="guest_id"
                        >
                            <option value=""></option>
                            @foreach(($guests ?? collect()) as $g)
                                @php($labelName = trim(($g->first_name ?? '') . ' ' . ($g->last_name ?? '')))
                                <option value="{{ $g->id }}" @selected((string)$selectedGuestId === (string)$g->id)>
                                    {{ $labelName !== '' ? $labelName : ('Guest #' . $g->id) }} — {{ $g->id_number ?: '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Check-in</label>
                        <input
                            type="date"
                            class="kt-input w-full"
                            name="check_in_date"
                            required
                            value="{{ old('check_in_date', $prefill['check_in_date'] ?? optional($reservation->check_in_date)->format('Y-m-d')) }}"
                        />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Check-out</label>
                        <input
                            type="date"
                            class="kt-input w-full"
                            name="check_out_date"
                            required
                            value="{{ old('check_out_date', $prefill['check_out_date'] ?? optional($reservation->check_out_date)->format('Y-m-d')) }}"
                        />
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Reason (optional)</label>
                        <textarea class="kt-input w-full" name="reason" rows="2" placeholder="Reason for changing dates...">{{ old('reason') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Adults</label>
                        <input type="number" min="1" class="kt-input w-full" name="adults" value="{{ old('adults', $reservation->adults ?? 1) }}" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Children</label>
                        <input type="number" min="0" class="kt-input w-full" name="children" value="{{ old('children', $reservation->children ?? 0) }}" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Status</label>
                        @php($selectedStatus = old('status', $reservation->status ?? 'booked'))
                        <select name="status" class="kt-select w-full">
                            <option value="booked" @selected($selectedStatus === 'booked')>Booked</option>
                            <option value="confirmed" @selected($selectedStatus === 'confirmed')>Confirmed</option>
                            <option value="cancelled" @selected($selectedStatus === 'cancelled')>Cancelled</option>
                            <option value="no_show" @selected($selectedStatus === 'no_show')>No-show</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Source</label>
                        <input class="kt-input w-full" name="channel" value="{{ old('channel', $reservation->channel) }}" placeholder="Walk-in, OTA, phone..." />
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Note (optional)</label>
                        <textarea class="kt-input w-full" name="note" rows="3" placeholder="Internal note...">{{ old('note', $reservation->note) }}</textarea>
                    </div>

                    <div class="lg:col-span-2 flex gap-2">
                        <button class="kt-btn kt-btn-primary" type="submit">Update</button>
                        <a class="kt-btn" href="{{ route('admin.reservations.show', $reservation->id) }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
