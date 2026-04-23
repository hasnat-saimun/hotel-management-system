@extends('admin.layouts.app')

@section('content')
    @php
        $rackDate = $rackDate ?? \Carbon\Carbon::today();
        $generatedAt = $generatedAt ?? now();
        $counts = $counts ?? [];
        $floors = $floors ?? [];
        $filters = $filters ?? [];
        $roomTypes = $roomTypes ?? [];
        $floorOptions = $floorsList ?? [];

        $search = (string) ($filters['q'] ?? request('q', ''));
        $floorId = $filters['floor_id'] ?? request('floor_id');
        $roomTypeId = $filters['room_type_id'] ?? request('room_type_id');
        $status = (string) ($filters['status'] ?? request('status', 'all'));

        $totalRooms = array_sum($counts);

        $statusLegend = [
            'available' => ['label' => 'Available', 'dot' => '#22c55e'],
            'occupied' => ['label' => 'Occupied', 'dot' => '#ef4444'],
            'reserved' => ['label' => 'Reserved', 'dot' => '#0ea5e9'],
            'clean' => ['label' => 'Clean', 'dot' => '#10b981'],
            'dirty' => ['label' => 'Dirty', 'dot' => '#f59e0b'],
            'maintenance' => ['label' => 'Maintenance', 'dot' => '#f97316'],
            'out_of_order' => ['label' => 'Out of Order', 'dot' => '#94a3b8'],
        ];
    @endphp

    <div class="kt-card">
        <div class="kt-card-header flex items-center justify-between">
            <div>
                <h3 class="kt-card-title">Room Rack</h3>
                <div class="text-sm text-secondary-foreground">Front Desk • Live room control dashboard</div>
            </div>

            <div class="text-sm text-secondary-foreground text-right">
                <div>Date: <span class="text-foreground font-medium">{{ $rackDate->format('M d, Y') }}</span></div>
                <div>Updated: {{ $generatedAt->format('h:i A') }}</div>
            </div>
        </div>

        <div class="kt-card-content p-4">
            <div class="sticky top-0 z-10 -mx-4 px-4 py-3 bg-background/95 backdrop-blur border-b border-input/70 mb-4">
                <form id="room-rack-filter-form" method="GET" action="{{ route('admin.front-desk.room-rack') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-5 items-end">
                    <input type="hidden" name="date" value="{{ $rackDate->toDateString() }}" />

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Search room number</label>
                        <input id="room-rack-search" type="text" name="q" class="kt-input w-full" placeholder="e.g. 101, 2A" value="{{ $search }}" autocomplete="off" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Floor</label>
                        <select id="room-rack-floor" name="floor_id" class="kt-input w-full">
                            <option value="">All floors</option>
                            @foreach($floorOptions as $floor)
                                @php
                                    $floorLabel = trim((string) ($floor->name ?? ''));
                                    if ($floorLabel === '') {
                                        $floorLabel = $floor->level_number ? ('Floor ' . $floor->level_number) : ('Floor #' . $floor->id);
                                    } elseif ($floor->level_number) {
                                        $floorLabel = $floorLabel . ' (L' . $floor->level_number . ')';
                                    }
                                @endphp
                                <option value="{{ $floor->id }}" {{ (string) $floorId === (string) $floor->id ? 'selected' : '' }}>
                                    {{ $floorLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Room type</label>
                        <select id="room-rack-room-type" name="room_type_id" class="kt-input w-full">
                            <option value="">All types</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ (string) $roomTypeId === (string) $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="text-sm text-secondary-foreground">Status</label>
                            <select id="room-rack-status" name="status" class="kt-input w-full">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All statuses</option>
                                <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ $status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="reserved" {{ $status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="clean" {{ $status === 'clean' ? 'selected' : '' }}>Clean</option>
                                <option value="dirty" {{ $status === 'dirty' ? 'selected' : '' }}>Dirty</option>
                                <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="out_of_order" {{ $status === 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                            </select>
                        </div>

                        @if($search !== '' || $floorId || $roomTypeId || $status !== 'all')
                            <a class="kt-btn" href="{{ route('admin.front-desk.room-rack', ['date' => $rackDate->toDateString()]) }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="kt-badge kt-badge-outline kt-badge-info">Matching: {{ $totalRooms }}</span>
                <span class="kt-badge border" style="background-color:#dcfce7;color:#166534;border-color:#bbf7d0;">Available: {{ (int) ($counts['available'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#fee2e2;color:#b91c1c;border-color:#fecaca;">Occupied: {{ (int) ($counts['occupied'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#e0f2fe;color:#0369a1;border-color:#bae6fd;">Reserved: {{ (int) ($counts['reserved'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#d1fae5;color:#047857;border-color:#a7f3d0;">Clean: {{ (int) ($counts['clean'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#fef3c7;color:#92400e;border-color:#fde68a;">Dirty: {{ (int) ($counts['dirty'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#ffedd5;color:#c2410c;border-color:#fed7aa;">Maintenance: {{ (int) ($counts['maintenance'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#f1f5f9;color:#334155;border-color:#cbd5e1;">Out of Order: {{ (int) ($counts['out_of_order'] ?? 0) }}</span>
            </div>

            <div class="mb-5 rounded-lg border border-input/70 bg-muted/20 px-3 py-2">
                <div class="text-xs uppercase tracking-wide text-secondary-foreground mb-2">Status Legend</div>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach($statusLegend as $legend)
                        <div class="inline-flex items-center gap-2 text-xs text-foreground/90">
                            <span class="inline-block h-2.5 w-2.5 rounded-full" style="background-color: {{ $legend['dot'] }};"></span>
                            <span>{{ $legend['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(empty($floors))
                <div class="p-6 text-center text-secondary-foreground">No rooms found.</div>
            @else
                <div class="grid gap-6">
                    @foreach($floors as $floor)
                        @php
                            $floorLabel = $floor['floor_label'] ?? 'Floor';
                            $rooms = $floor['rooms'] ?? [];
                        @endphp

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-semibold">{{ $floorLabel }}</div>
                                <div class="text-xs text-secondary-foreground">Rooms: {{ is_array($rooms) ? count($rooms) : 0 }}</div>
                            </div>

                            <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-7">
                                @foreach($rooms as $room)
                                    @php
                                        $roomNumber = $room['room_number'] ?? '-';
                                        $roomType = $room['room_type'] ?? '-';
                                        $statusLabel = $room['rack_status_label'] ?? '-';

                                        $guestName = $room['guest_name'] ?? null;
                                        $reservationCode = $room['reservation_code'] ?? null;
                                        $datesLine = $room['dates_line'] ?? null;
                                        $stayDuration = $room['stay_duration'] ?? null;
                                        $housekeepingLine = $room['housekeeping_line'] ?? null;

                                        $isVip = (bool) ($room['is_vip'] ?? false);
                                        $isOverstay = (bool) ($room['is_overstay'] ?? false);

                                        $statusTone = $room['status_tone'] ?? [
                                            'badge_style' => 'background-color:#f1f5f9;color:#334155;border-color:#cbd5e1;',
                                            'card_style' => 'border-color:#e2e8f0;background-color:#ffffff;',
                                            'bar_style' => 'background-color:#94a3b8;',
                                        ];
                                    @endphp

                                    <button
                                        type="button"
                                        class="relative w-full text-left overflow-hidden rounded-xl border p-3 transition-all duration-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/50"
                                        style="{{ $statusTone['card_style'] }}"
                                        data-room-card="true"
                                        data-room-id="{{ $room['id'] }}"
                                    >
                                        <span class="absolute left-0 top-0 h-full w-1" style="{{ $statusTone['bar_style'] }}"></span>
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0 pl-1">
                                                <div class="text-base font-semibold text-mono truncate">{{ $roomNumber }}</div>
                                                <div class="text-xs text-secondary-foreground truncate">{{ $roomType }}</div>
                                            </div>
                                            <span class="kt-badge kt-badge-sm border" style="{{ $statusTone['badge_style'] }}">{{ $statusLabel }}</span>
                                        </div>

                                        <div class="mt-2 min-h-[1.25rem]">
                                            @if($guestName)
                                                <div class="text-sm font-medium truncate">{{ $guestName }}</div>
                                            @else
                                                <div class="text-xs text-secondary-foreground">—</div>
                                            @endif
                                        </div>

                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @if($isVip)
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                            @endif
                                            @if($isOverstay)
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">Overstay</span>
                                            @endif
                                        </div>

                                        <div class="mt-2 grid gap-1 text-xs text-secondary-foreground">
                                            @if($reservationCode)
                                                <div class="truncate">Res: {{ $reservationCode }}</div>
                                            @endif
                                            @if($datesLine)
                                                <div class="truncate">{{ $datesLine }}</div>
                                            @endif
                                            @if($stayDuration)
                                                <div class="truncate">Stay: {{ $stayDuration }}</div>
                                            @endif
                                            @if($housekeepingLine)
                                                <div class="truncate">{{ $housekeepingLine }}</div>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <button id="room_rack_modal_toggle" class="hidden" type="button" data-kt-modal-toggle="#room_rack_modal"></button>

            <div class="kt-modal" data-kt-modal="true" id="room_rack_modal">
                <div class="kt-modal-content w-full max-w-[760px] top-5 lg:top-[8%]">
                    <div class="kt-modal-header">
                        <h3 class="kt-modal-title" id="room_rack_modal_title">Room Details</h3>
                        <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true" type="button">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>

                    <div class="kt-modal-body grid gap-4 max-h-[75vh] overflow-auto" id="room_rack_modal_body">
                        <div id="room_rack_modal_message" class="hidden rounded-md border px-3 py-2 text-xs"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-lg border border-input/70 bg-muted/20 px-3 py-2">
                                <div class="text-[11px] uppercase tracking-wide text-secondary-foreground mb-1">Room</div>
                                <div class="text-sm font-semibold" id="modal_room_number">-</div>
                                <div class="text-xs text-secondary-foreground" id="modal_room_type">-</div>
                                <div class="text-xs text-secondary-foreground" id="modal_room_floor">-</div>
                                <div class="mt-2">
                                    <span class="kt-badge kt-badge-sm border" id="modal_room_status_badge">-</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-input/70 bg-muted/20 px-3 py-2">
                                <div class="text-[11px] uppercase tracking-wide text-secondary-foreground mb-1">Current Guest</div>
                                <div class="text-sm font-medium" id="modal_guest_name">-</div>
                                <div class="text-xs text-secondary-foreground" id="modal_guest_phone">-</div>
                                <div class="mt-2" id="modal_guest_vip"></div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-input/70 px-3 py-2">
                            <div class="text-[11px] uppercase tracking-wide text-secondary-foreground mb-1">Reservation Info</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                <div><span class="text-secondary-foreground">Code:</span> <span id="modal_res_code">-</span></div>
                                <div><span class="text-secondary-foreground">Status:</span> <span id="modal_res_status">-</span></div>
                                <div><span class="text-secondary-foreground">Channel:</span> <span id="modal_res_channel">-</span></div>
                                <div><span class="text-secondary-foreground">Dates:</span> <span id="modal_res_dates">-</span></div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-input/70 px-3 py-2">
                            <div class="text-[11px] uppercase tracking-wide text-secondary-foreground mb-1">Stay Info</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                <div><span class="text-secondary-foreground">Stay #:</span> <span id="modal_stay_id">-</span></div>
                                <div><span class="text-secondary-foreground">Status:</span> <span id="modal_stay_status">-</span></div>
                                <div><span class="text-secondary-foreground">Check-in:</span> <span id="modal_stay_check_in">-</span></div>
                                <div><span class="text-secondary-foreground">Nights:</span> <span id="modal_stay_nights">-</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-modal-footer justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2 flex-wrap">
                            <button class="kt-btn kt-btn-sm kt-btn-primary" data-room-action="check-in" type="button">Check-In</button>
                            <button class="kt-btn kt-btn-sm kt-btn-primary" data-room-action="check-out" type="button">Check-Out</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline" data-room-action="mark-dirty" type="button">Mark Dirty</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline" data-room-action="mark-clean" type="button">Mark Clean</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline" data-room-action="mark-available" type="button">Mark Available</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline" data-room-action="mark-maintenance" type="button">Mark Maintenance</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline kt-btn-destructive" data-room-action="block" type="button">Block Room</button>
                            <button class="kt-btn kt-btn-sm kt-btn-outline" data-room-action="unblock" type="button">Unblock Room</button>
                        </div>
                        <button class="kt-btn" data-kt-modal-dismiss="true" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                    var filterForm = document.getElementById('room-rack-filter-form');
                    var searchInput = document.getElementById('room-rack-search');
                    var floorSelect = document.getElementById('room-rack-floor');
                    var roomTypeSelect = document.getElementById('room-rack-room-type');
                    var statusSelect = document.getElementById('room-rack-status');
                var rackDate = @json($rackDate->toDateString());
                var csrfToken = @json(csrf_token());
                var detailsUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.details', ['room' => '__ROOM__']));
                var checkInUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.check-in', ['room' => '__ROOM__']));
                var checkOutUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.check-out', ['room' => '__ROOM__']));
                var housekeepingUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.housekeeping', ['room' => '__ROOM__']));
                var blockUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.block', ['room' => '__ROOM__']));
                var unblockUrlTemplate = @json(route('admin.front-desk.room-rack.rooms.unblock', ['room' => '__ROOM__']));

                var currentRoom = null;
                var currentActions = {};

                var modalToggle = document.getElementById('room_rack_modal_toggle');
                var modalTitle = document.getElementById('room_rack_modal_title');
                var messageEl = document.getElementById('room_rack_modal_message');

                var actionButtons = Array.prototype.slice.call(document.querySelectorAll('[data-room-action]'));

                function makeUrl(template, roomId) {
                    return template.replace('__ROOM__', String(roomId));
                }

                function toDisplay(value) {
                    if (value === null || value === undefined || value === '') {
                        return '-';
                    }
                    return String(value);
                }

                function formatDate(value) {
                    if (!value) {
                        return '-';
                    }

                    var dt = new Date(value);
                    if (Number.isNaN(dt.getTime())) {
                        return String(value);
                    }

                    return dt.toLocaleString();
                }

                function showMessage(type, text) {
                    if (!messageEl) {
                        return;
                    }

                    messageEl.classList.remove('hidden', 'border-success/30', 'bg-success/10', 'text-success', 'border-destructive/30', 'bg-destructive/10', 'text-destructive');
                    if (type === 'error') {
                        messageEl.classList.add('border-destructive/30', 'bg-destructive/10', 'text-destructive');
                    } else {
                        messageEl.classList.add('border-success/30', 'bg-success/10', 'text-success');
                    }
                    messageEl.textContent = text;
                }

                function setActionState(isBusy) {
                    actionButtons.forEach(function (btn) {
                        btn.disabled = isBusy;
                    });
                }

                function setButtonVisibility() {
                    actionButtons.forEach(function (btn) {
                        var action = btn.getAttribute('data-room-action');
                        var can = false;
                        if (action === 'check-in') can = !!currentActions.can_check_in;
                        if (action === 'check-out') can = !!currentActions.can_check_out;
                        if (action === 'mark-dirty') can = !!currentActions.can_mark_dirty;
                        if (action === 'mark-clean') can = !!currentActions.can_mark_clean;
                        if (action === 'mark-available') can = !!currentActions.can_mark_available;
                        if (action === 'mark-maintenance') can = !!currentActions.can_mark_maintenance;
                        if (action === 'block') can = !!currentActions.can_block;
                        if (action === 'unblock') can = !!currentActions.can_unblock;
                        btn.classList.toggle('hidden', !can);
                    });
                }

                function setText(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.textContent = toDisplay(value);
                    }
                }

                function hydrateModal(room) {
                    currentRoom = room;
                    currentActions = room.actions || {};

                    if (modalTitle) {
                        modalTitle.textContent = 'Room ' + toDisplay(room.room_number) + ' Details';
                    }

                    setText('modal_room_number', room.room_number);
                    setText('modal_room_type', room.room_type);
                    setText('modal_room_floor', room.floor_name);

                    var statusBadge = document.getElementById('modal_room_status_badge');
                    if (statusBadge) {
                        statusBadge.textContent = toDisplay(room.rack_status_label);
                    }

                    setText('modal_guest_name', room.guest && room.guest.name);
                    setText('modal_guest_phone', room.guest && room.guest.phone);

                    var vipEl = document.getElementById('modal_guest_vip');
                    if (vipEl) {
                        vipEl.innerHTML = (room.guest && room.guest.vip)
                            ? '<span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>'
                            : '';
                    }

                    setText('modal_res_code', room.reservation && room.reservation.code);
                    setText('modal_res_status', room.reservation && room.reservation.status);
                    setText('modal_res_channel', room.reservation && room.reservation.channel);

                    var resDates = '-';
                    if (room.reservation && (room.reservation.check_in_date || room.reservation.check_out_date)) {
                        resDates = toDisplay(room.reservation.check_in_date) + ' to ' + toDisplay(room.reservation.check_out_date);
                    }
                    setText('modal_res_dates', resDates);

                    setText('modal_stay_id', room.stay && room.stay.id);
                    setText('modal_stay_status', room.stay && room.stay.status);
                    setText('modal_stay_check_in', room.stay ? formatDate(room.stay.check_in_time) : '-');
                    setText('modal_stay_nights', room.stay && room.stay.nights !== null && room.stay.nights !== undefined ? room.stay.nights : '-');

                    setButtonVisibility();
                }

                function fetchRoomDetails(roomId) {
                    setActionState(true);
                    showMessage('success', 'Loading room details...');

                    fetch(makeUrl(detailsUrlTemplate, roomId) + '?rack_date=' + encodeURIComponent(rackDate), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then(function (res) {
                            return res.json().then(function (payload) {
                                if (!res.ok || !payload || payload.ok !== true) {
                                    throw new Error(payload && payload.message ? payload.message : 'Unable to load room details.');
                                }
                                return payload;
                            });
                        })
                        .then(function (payload) {
                            hydrateModal(payload.room || {});
                            messageEl.classList.add('hidden');
                        })
                        .catch(function (error) {
                            showMessage('error', error.message || 'Unable to load room details.');
                        })
                        .finally(function () {
                            setActionState(false);
                        });
                }

                function runAction(action) {
                    if (!currentRoom || !currentRoom.room_id) {
                        return;
                    }

                    var url = null;
                    var payload = { rack_date: rackDate };

                    if (action === 'check-in') {
                        url = makeUrl(checkInUrlTemplate, currentRoom.room_id);
                    } else if (action === 'check-out') {
                        url = makeUrl(checkOutUrlTemplate, currentRoom.room_id);
                    } else if (action === 'mark-dirty') {
                        url = makeUrl(housekeepingUrlTemplate, currentRoom.room_id);
                        payload.state = 'dirty';
                    } else if (action === 'mark-clean') {
                        url = makeUrl(housekeepingUrlTemplate, currentRoom.room_id);
                        payload.state = 'clean';
                    } else if (action === 'mark-available') {
                        url = makeUrl(housekeepingUrlTemplate, currentRoom.room_id);
                        payload.state = 'available';
                    } else if (action === 'mark-maintenance') {
                        url = makeUrl(housekeepingUrlTemplate, currentRoom.room_id);
                        payload.state = 'maintenance';
                    } else if (action === 'block') {
                        url = makeUrl(blockUrlTemplate, currentRoom.room_id);
                    } else if (action === 'unblock') {
                        url = makeUrl(unblockUrlTemplate, currentRoom.room_id);
                    }

                    if (!url) {
                        return;
                    }

                    setActionState(true);
                    showMessage('success', 'Processing action...');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(payload)
                    })
                        .then(function (res) {
                            return res.json().then(function (responsePayload) {
                                if (!res.ok || !responsePayload || responsePayload.ok !== true) {
                                    throw new Error(responsePayload && responsePayload.message ? responsePayload.message : 'Action failed.');
                                }
                                return responsePayload;
                            });
                        })
                        .then(function (responsePayload) {
                            showMessage('success', responsePayload.message || 'Action completed.');
                            window.setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        })
                        .catch(function (error) {
                            showMessage('error', error.message || 'Action failed.');
                            setActionState(false);
                        });
                }

                document.querySelectorAll('[data-room-card="true"]').forEach(function (card) {
                    card.addEventListener('click', function () {
                        var roomId = card.getAttribute('data-room-id');
                        if (!roomId) {
                            return;
                        }

                        if (modalToggle) {
                            modalToggle.click();
                        }

                        fetchRoomDetails(roomId);
                    });
                });

                if (filterForm && searchInput) {
                    var searchTimer;
                    searchInput.addEventListener('input', function () {
                        window.clearTimeout(searchTimer);
                        searchTimer = window.setTimeout(function () {
                            filterForm.submit();
                        }, 350);
                    });
                }

                [floorSelect, roomTypeSelect, statusSelect].forEach(function (selectEl) {
                    if (!selectEl) {
                        return;
                    }

                    selectEl.addEventListener('change', function () {
                        filterForm.submit();
                    });
                });

                actionButtons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        runAction(btn.getAttribute('data-room-action'));
                    });
                });

                // Lightweight auto-refresh to behave like a live control dashboard.
                window.setTimeout(function () {
                    window.location.reload();
                }, 60000);
            });
        </script>
    @endpush
@endsection
