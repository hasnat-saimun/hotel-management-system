@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Walk-in Booking</h3>
        <div class="text-sm text-secondary-foreground">Quick create reservation for walk-in guests</div>
    </div>
    <div class="kt-card-content p-4">
        <div class="mb-4">
            <h5 class="text-md font-semibold">Find Available Rooms</h5>
        </div>
        <form method="GET" action="{{ route('admin.reservations.walkin') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            <div>
                <label class="text-sm text-secondary-foreground">Check-in</label>
                <input type="date" class="kt-input w-full" name="check_in_date" value="{{ $checkInDate ?? '' }}" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Check-out</label>
                <input type="date" class="kt-input w-full" name="check_out_date" value="{{ $checkOutDate ?? '' }}" />
            </div>
            
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="kt-btn kt-btn-primary">Search</button>
            </div>
        </form>

        <div class="mt-6">
            @php($availableRooms = $availableRooms ?? collect())
            @php($partiallyAvailableRooms = $partiallyAvailableRooms ?? collect())

            @if($availableRooms->count() > 0 || $partiallyAvailableRooms->count() > 0)
                @if($availableRooms->count() > 0)
                    <div class="mb-2">
                        <h5 class="text-md font-semibold">Available for full stay</h5>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto kt-table">
                            <thead>
                                <tr class="text-sm text-secondary-foreground bg-muted/20">
                                    <th class="px-4 py-3 text-left w-10">
                                        <input type="checkbox" data-room-select-all aria-label="Select all rooms" />
                                    </th>
                                    <th class="px-4 py-3 text-left">Sl</th>
                                    <th class="px-4 py-3 text-left">Room ID</th>
                                    <th class="px-4 py-3 text-left">Room #</th>
                                    <th class="px-4 py-3 text-left">Type</th>
                                    <th class="px-4 py-3 text-left">Floor</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($availableRooms as $room)
                                    @php($detailsId = 'room-details-full-' . $room->id)
                                    <tr
                                        class="border-b border-input hover:bg-accent/10 cursor-pointer"
                                        data-room-details-target="{{ $detailsId }}"
                                        aria-expanded="false"
                                    >
                                        <td class="px-4 py-3 align-top">
                                            <input
                                                type="checkbox"
                                                class="room-select-checkbox"
                                                value="{{ $room->id }}"
                                                data-room-number="{{ $room->room_number ?? '' }}"
                                                data-room-type="{{ $room->roomType?->name ?? '' }}"
                                                aria-label="Select room"
                                            />
                                        </td>
                                        <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 align-top">{{ $room->id }}</td>
                                        <td class="px-4 py-3 align-top">{{ $room->room_number ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">{{ $room->roomType?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">{{ $room->floor?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">{{ ucfirst($room->status ?? '-') }}</td>
                                    </tr>
                                    <tr id="{{ $detailsId }}" class="hidden border-b border-input bg-muted/10">
                                        <td colspan="7" class="px-4 py-3">
                                            <div class="rounded border border-input bg-background p-4">
                                                <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
                                                    <div class="space-y-2">
                                                        <div class="text-xs font-semibold text-secondary-foreground">Room</div>
                                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                            <dt class="text-secondary-foreground">Active</dt>
                                                            <dd class="text-foreground">{{ $room->is_active ? 'Yes' : 'No' }}</dd>
                                                            <dt class="text-secondary-foreground">Notes</dt>
                                                            <dd class="text-foreground">{{ $room->notes ?: '-' }}</dd>
                                                        </dl>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <div class="text-xs font-semibold text-secondary-foreground">Room Type</div>
                                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                            <dt class="text-secondary-foreground">Name</dt>
                                                            <dd class="text-foreground">{{ $room->roomType?->name ?? '-' }}</dd>
                                                            <dt class="text-secondary-foreground">Capacity</dt>
                                                            <dd class="text-foreground">A {{ $room->roomType?->capacity_adults ?? '-' }}, C {{ $room->roomType?->capacity_children ?? '-' }}</dd>
                                                            <dt class="text-secondary-foreground">Base price</dt>
                                                            <dd class="text-foreground">{{ $room->roomType?->base_price ?? '-' }}</dd>
                                                            <dt class="text-secondary-foreground">Type active</dt>
                                                            <dd class="text-foreground">{{ $room->roomType ? ($room->roomType->is_active ? 'Yes' : 'No') : '-' }}</dd>
                                                        </dl>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <div class="text-xs font-semibold text-secondary-foreground">Floor</div>
                                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                            <dt class="text-secondary-foreground">Name</dt>
                                                            <dd class="text-foreground">{{ $room->floor?->name ?? '-' }}</dd>
                                                            <dt class="text-secondary-foreground">Level</dt>
                                                            <dd class="text-foreground">{{ $room->floor?->level_number ?? '-' }}</dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-sm text-secondary-foreground bg-muted/20 rounded">
                        No rooms available for the full selected period.
                    </div>
                @endif

                @if($partiallyAvailableRooms->count() > 0)
                    <div class="mt-6 mb-2">
                        <h5 class="text-md font-semibold">Available within selected dates</h5>
                        <div class="text-sm text-secondary-foreground">These rooms are not available for the whole stay but have free dates inside your selected range.</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto kt-table">
                            <thead>
                                <tr class="text-sm text-secondary-foreground bg-muted/20">
                                    <th class="px-4 py-3 text-left w-10">
                                        <input type="checkbox" data-room-select-all aria-label="Select all rooms" />
                                    </th>
                                    <th class="px-4 py-3 text-left">Sl</th>
                                    <th class="px-4 py-3 text-left">Room ID</th>
                                    <th class="px-4 py-3 text-left">Room #</th>
                                    <th class="px-4 py-3 text-left">Type</th>
                                    <th class="px-4 py-3 text-left">Floor</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Available (Check-in &rarr; Check-out)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($partiallyAvailableRooms as $row)
                                    @php($room = $row['room'] ?? null)
                                    @php($ranges = $row['availableRanges'] ?? [])

                                    @if($room)
                                        @php($detailsId = 'room-details-partial-' . $room->id)
                                        <tr
                                            class="border-b border-input hover:bg-accent/10 cursor-pointer"
                                            data-room-details-target="{{ $detailsId }}"
                                            aria-expanded="false"
                                        >
                                            <td class="px-4 py-3 align-top">
                                                <input
                                                    type="checkbox"
                                                    class="room-select-checkbox"
                                                    value="{{ $room->id }}"
                                                    data-room-number="{{ $room->room_number ?? '' }}"
                                                    data-room-type="{{ $room->roomType?->name ?? '' }}"
                                                    aria-label="Select room"
                                                />
                                            </td>
                                            <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 align-top">{{ $room->id }}</td>
                                            <td class="px-4 py-3 align-top">{{ $room->room_number ?? '-' }}</td>
                                            <td class="px-4 py-3 align-top">{{ $room->roomType?->name ?? '-' }}</td>
                                            <td class="px-4 py-3 align-top">{{ $room->floor?->name ?? '-' }}</td>
                                            <td class="px-4 py-3 align-top">{{ ucfirst($room->status ?? '-') }}</td>
                                            <td class="px-4 py-3 align-top">
                                                @forelse($ranges as $range)
                                                    <span class="whitespace-nowrap">
                                                        {{ \Carbon\Carbon::parse($range['from'])->format('d M Y') }}
                                                        &rarr;
                                                        {{ \Carbon\Carbon::parse($range['to'])->format('d M Y') }}
                                                    </span>
                                                    @if(!$loop->last)
                                                        <span class="text-secondary-foreground">,</span>
                                                    @endif
                                                @empty
                                                    -
                                                @endforelse
                                            </td>
                                        </tr>
                                        <tr id="{{ $detailsId }}" class="hidden border-b border-input bg-muted/10">
                                            <td colspan="8" class="px-4 py-3">
                                                <div class="rounded border border-input bg-background p-4">
                                                    <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
                                                        <div class="space-y-2">
                                                            <div class="text-xs font-semibold text-secondary-foreground">Room</div>
                                                            <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                                <dt class="text-secondary-foreground">Active</dt>
                                                                <dd class="text-foreground">{{ $room->is_active ? 'Yes' : 'No' }}</dd>
                                                                <dt class="text-secondary-foreground">Notes</dt>
                                                                <dd class="text-foreground">{{ $room->notes ?: '-' }}</dd>
                                                            </dl>
                                                        </div>
                                                        <div class="space-y-2">
                                                            <div class="text-xs font-semibold text-secondary-foreground">Room Type</div>
                                                            <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                                <dt class="text-secondary-foreground">Name</dt>
                                                                <dd class="text-foreground">{{ $room->roomType?->name ?? '-' }}</dd>
                                                                <dt class="text-secondary-foreground">Capacity</dt>
                                                                <dd class="text-foreground">A {{ $room->roomType?->capacity_adults ?? '-' }}, C {{ $room->roomType?->capacity_children ?? '-' }}</dd>
                                                                <dt class="text-secondary-foreground">Base price</dt>
                                                                <dd class="text-foreground">{{ $room->roomType?->base_price ?? '-' }}</dd>
                                                                <dt class="text-secondary-foreground">Type active</dt>
                                                                <dd class="text-foreground">{{ $room->roomType ? ($room->roomType->is_active ? 'Yes' : 'No') : '-' }}</dd>
                                                            </dl>
                                                        </div>
                                                        <div class="space-y-2">
                                                            <div class="text-xs font-semibold text-secondary-foreground">Floor</div>
                                                            <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                                                <dt class="text-secondary-foreground">Name</dt>
                                                                <dd class="text-foreground">{{ $room->floor?->name ?? '-' }}</dd>
                                                                <dt class="text-secondary-foreground">Level</dt>
                                                                <dd class="text-foreground">{{ $room->floor?->level_number ?? '-' }}</dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="p-4 text-sm text-secondary-foreground bg-muted/20 rounded">
                    {{ $missingMessage ?? 'No data found.' }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    function roomCheckboxes() {
        return Array.from(document.querySelectorAll('.room-select-checkbox'));
    }

    function syncSelectedRooms() {
        var selected = roomCheckboxes().filter(function (c) { return c.checked; });

        // Chips
        var chips = document.getElementById('selected-rooms');
        if (chips) {
            while (chips.firstChild) chips.removeChild(chips.firstChild);

            selected.forEach(function (c) {
                var number = (c.getAttribute('data-room-number') || '').trim();
                var type = (c.getAttribute('data-room-type') || '').trim();

                var label = number || ('ID ' + c.value);
                if (type) label += ' (' + type + ')';

                var chip = document.createElement('span');
                chip.className = 'kt-badge kt-badge-outline kt-badge-info';
                chip.textContent = label;
                chips.appendChild(chip);
            });
        }

        // Hidden inputs (IDs)
        var hidden = document.getElementById('rooms-hidden-inputs');
        if (hidden) {
            while (hidden.firstChild) hidden.removeChild(hidden.firstChild);
            selected.forEach(function (c) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'room_ids[]';
                input.value = c.value;
                hidden.appendChild(input);
            });
        }

        // Keep legacy field populated as comma-separated room numbers
        var legacy = document.querySelector('input[name="room_number"]');
        if (legacy) {
            var roomNumbers = selected
                .map(function (c) { return (c.getAttribute('data-room-number') || '').trim(); })
                .filter(Boolean);
            legacy.value = roomNumbers.join(',');
        }

        // Select-all states
        var all = roomCheckboxes();
        var selectAll = document.getElementById('select-all');
        var allToggles = document.querySelectorAll('[data-room-select-all]');
        var allChecked = all.length > 0 && selected.length === all.length;
        var someChecked = selected.length > 0 && selected.length < all.length;

        if (selectAll) {
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked;
        }
        allToggles.forEach(function (t) {
            t.checked = allChecked;
            t.indeterminate = someChecked;
        });
    }

    document.addEventListener('change', function (event) {
        var el = event.target;

        if (el && el.classList && el.classList.contains('room-select-checkbox')) {
            syncSelectedRooms();
            return;
        }

        if (el && el.id === 'select-all') {
            var checked = el.checked;
            roomCheckboxes().forEach(function (c) { c.checked = checked; });
            syncSelectedRooms();
            return;
        }

        if (el && el.matches && el.matches('[data-room-select-all]')) {
            var checked2 = el.checked;
            roomCheckboxes().forEach(function (c) { c.checked = checked2; });
            syncSelectedRooms();
        }
    });

    document.addEventListener('click', function (event) {
        // Do not toggle details if interacting with form controls
        if (event.target.closest('input, label, button, a')) return;

        var row = event.target.closest('tr[data-room-details-target]');
        if (!row) return;

        var targetId = row.getAttribute('data-room-details-target');
        if (!targetId) return;

        var detailsRow = document.getElementById(targetId);
        if (!detailsRow) return;

        var isHidden = detailsRow.classList.contains('hidden');
        detailsRow.classList.toggle('hidden');
        row.setAttribute('aria-expanded', String(isHidden));
        row.classList.toggle('bg-accent/10', isHidden);
    });

    // initial sync
    syncSelectedRooms();
})();
</script>
@endpush
