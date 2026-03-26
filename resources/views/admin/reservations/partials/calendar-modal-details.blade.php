@php
    $guest = $reservation->guest;
    $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
    $guestName = $guestName !== '' ? $guestName : '-';

    $roomNumbers = $reservation->rooms?->pluck('room_number')->filter()->unique()->values()->all() ?? [];

    $floorLabels = $reservation->rooms
        ? $reservation->rooms
            ->map(function ($room) {
                $floor = $room->floor ?? null;
                if (!$floor) {
                    return null;
                }

                $name = trim((string) ($floor->name ?? ''));
                $level = trim((string) ($floor->level_number ?? ''));

                return $name !== '' ? $name : ($level !== '' ? $level : null);
            })
            ->filter()
            ->unique()
            ->values()
            ->all()
        : [];

    $statusLabel = $reservation->status
        ? ucfirst(str_replace(['_', '-'], ' ', (string) $reservation->status))
        : '-';

    $checkInDisplay = $reservation->check_in_date ? \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') : '-';
    $checkOutDisplay = $reservation->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') : '-';
@endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="kt-card p-4">
        <div class="text-xs font-semibold text-secondary-foreground">Reservation Code</div>
        <div class="mt-1 text-sm font-medium text-mono break-words">{{ $reservation->reservation_code ?? '-' }}</div>

        <div class="mt-3 pt-3 border-t border-border">
            <div class="text-xs font-semibold text-secondary-foreground">Status</div>
            <div class="mt-1 text-sm font-medium text-mono">{{ $statusLabel }}</div>
        </div>
    </div>

    <div class="kt-card p-4">
        <div class="text-xs font-semibold text-secondary-foreground">Guest</div>
        <div class="mt-1 text-sm font-medium text-mono break-words">{{ $guestName }}</div>

        <div class="mt-3 pt-3 border-t border-border">
            <div class="text-xs font-semibold text-secondary-foreground">Phone</div>
            <div class="mt-1 text-sm font-medium text-mono break-words">{{ $guest?->phone ?? '-' }}</div>
        </div>
    </div>

    <div class="kt-card p-4">
        <div class="text-xs font-semibold text-secondary-foreground">Stay Dates</div>
        <dl class="mt-2 grid gap-2">
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

    <div class="kt-card p-4">
        <div class="text-xs font-semibold text-secondary-foreground">Floor / Room</div>
        <div class="mt-2 text-sm font-medium text-mono break-words">
            @if(!empty($floorLabels))
                {{ implode(', ', $floorLabels) }}
            @else
                -
            @endif
            <span class="text-secondary-foreground font-normal"> • </span>
            @if(!empty($roomNumbers))
                {{ implode(', ', $roomNumbers) }}
            @else
                -
            @endif
        </div>

        <div class="mt-3 pt-3 border-t border-border">
            <div class="text-xs font-semibold text-secondary-foreground">Guests</div>
            <div class="mt-1 text-sm font-medium text-mono">
                {{ $reservation->adults ?? '-' }} adults
                <span class="text-secondary-foreground font-normal">•</span>
                {{ $reservation->children ?? '-' }} children
            </div>
        </div>
    </div>
</div>
