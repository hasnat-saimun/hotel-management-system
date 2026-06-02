@props([
    'guest' => null,
    'room' => null,
    'stay' => null,
    'financial' => null,
])

@php
    $guestData = is_object($guest) ? (array) $guest : (array) ($guest ?? []);
    $roomData = is_object($room) ? (array) $room : (array) ($room ?? []);
    $stayData = is_object($stay) ? (array) $stay : (array) ($stay ?? []);
    $financialData = is_object($financial) ? (array) $financial : (array) ($financial ?? []);

    $guestName = trim((string) ($guestData['name'] ?? trim(($guestData['first_name'] ?? '') . ' ' . ($guestData['last_name'] ?? ''))));
    $guestName = $guestName !== '' ? $guestName : 'Guest Name';

    $guestPhone = $guestData['phone'] ?? '—';
    $roomNumber = $roomData['room_number'] ?? '—';
    $roomType = $roomData['room_type'] ?? ($roomData['room_type_name'] ?? '—');
    $checkInDate = $stayData['check_in_date'] ?? '—';
    $checkOutDate = $stayData['check_out_date'] ?? '—';
    $nights = $stayData['nights'] ?? '—';

    $roomCharge = $financialData['room_charge'] ?? '0.00';
    $taxes = $financialData['taxes'] ?? '0.00';
    $extraServices = $financialData['extra_services'] ?? '0.00';
    $grandTotal = $financialData['grand_total'] ?? '0.00';

    $summaryRows = [
        ['label' => 'Room Charge', 'value' => $roomCharge],
        ['label' => 'Taxes', 'value' => $taxes],
        ['label' => 'Extra Services', 'value' => $extraServices],
    ];

    $actions = [
        ['label' => 'Save Walk-In', 'style' => 'kt-btn-primary'],
        ['label' => 'Save & Print', 'style' => 'kt-btn-outline'],
        ['label' => 'Save & Open Folio', 'style' => 'kt-btn-outline'],
    ];

    $recommendations = [
        'Keep the sidebar sticky on desktop so staff can review totals while changing room details.',
        'Show the financial total in a visually stronger hierarchy than the individual line items.',
        'Make the three actions visually distinct, with the primary save action first and the folio action last.',
        'Collapse the sidebar into a stacked summary card on small screens to preserve readability.',
    ];
@endphp

<aside class="xl:sticky xl:top-6">
    <section class="kt-card h-full">
        <div class="kt-card-header flex items-center justify-between gap-4">
            <div>
                <h3 class="kt-card-title">Booking Summary</h3>
                <div class="text-sm text-secondary-foreground">Review the walk-in snapshot before saving.</div>
            </div>
            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Section 4</span>
        </div>

        <div class="kt-card-content p-4 sm:p-5">
            <div class="space-y-4">
                <div class="rounded-2xl border border-border bg-background p-4 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Guest</div>
                    <div class="mt-2 text-base font-semibold text-foreground break-words">{{ $guestName }}</div>
                    <div class="mt-1 text-sm text-secondary-foreground break-words">{{ $guestPhone }}</div>
                </div>

                <div class="rounded-2xl border border-border bg-muted/30 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Room</div>
                    <dl class="mt-3 grid gap-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-secondary-foreground">Room Number</dt>
                            <dd class="font-medium text-foreground">{{ $roomNumber }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-secondary-foreground">Room Type</dt>
                            <dd class="font-medium text-foreground">{{ $roomType }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-border bg-muted/30 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Stay</div>
                    <dl class="mt-3 grid gap-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-secondary-foreground">Check-In Date</dt>
                            <dd class="font-medium text-foreground">{{ $checkInDate }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-secondary-foreground">Check-Out Date</dt>
                            <dd class="font-medium text-foreground">{{ $checkOutDate }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-secondary-foreground">Nights</dt>
                            <dd class="font-medium text-foreground">{{ $nights }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-border bg-muted/30 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Financial Summary</div>
                    <dl class="mt-3 grid gap-3 text-sm">
                        @foreach($summaryRows as $row)
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-secondary-foreground">{{ $row['label'] }}</dt>
                                <dd class="font-medium text-foreground">{{ $row['value'] }}</dd>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-between gap-3 border-t border-border pt-3 text-base">
                            <dt class="font-semibold text-foreground">Grand Total</dt>
                            <dd class="font-semibold text-foreground">{{ $grandTotal }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="grid gap-2">
                    @foreach($actions as $action)
                        <button type="button" class="kt-btn {{ $action['style'] }} w-full justify-center">{{ $action['label'] }}</button>
                    @endforeach
                </div>

                <div class="rounded-2xl border border-dashed border-border bg-muted/20 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">UX Recommendations</div>
                    <div class="mt-3 space-y-3">
                        @foreach($recommendations as $recommendation)
                            <div class="text-sm text-foreground">{{ $recommendation }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>