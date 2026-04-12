@extends('admin.layouts.app')
@section('title', 'Create Room Block')

@push('scripts')
<script>
    (function () {
        function byId(id) { return document.getElementById(id); }

        function setDisabled(el, disabled) {
            if (!el) return;
            el.disabled = !!disabled;
            if (disabled) {
                el.setAttribute('aria-disabled', 'true');
            } else {
                el.removeAttribute('aria-disabled');
            }
        }

        function clearRoomsSelection(listEl) {
            if (!listEl) return;
            Array.from(listEl.querySelectorAll('input[type="checkbox"][name="room_ids[]"]') || [])
                .forEach(function (cb) { cb.checked = false; });
        }

        function selectedRoomsCount(listEl) {
            if (!listEl) return 0;
            return Array.from(listEl.querySelectorAll('input[type="checkbox"][name="room_ids[]"]:checked') || []).length;
        }

        function normalizeDateInputs(startEl, endEl) {
            if (!startEl || !endEl) return;
            var start = startEl.value;
            if (start) {
                endEl.min = start;
                if (endEl.value && endEl.value < start) {
                    endEl.value = start;
                }
            }
        }

        function init() {
            var modeSpecific = byId('rb_mode_specific');
            var modeAuto = byId('rb_mode_auto');
            var roomsList = byId('rb_rooms_list');
            var roomsSearch = byId('rb_rooms_search');
            var roomsClearBtn = byId('rb_rooms_clear');
            var roomsEmpty = byId('rb_rooms_empty');
            var roomsDropdownBtn = byId('rb_rooms_dropdown_btn');
            var roomsDropdownPanel = byId('rb_rooms_dropdown_panel');
            var roomsDropdownLabel = byId('rb_rooms_dropdown_label');
            var roomTypeSelect = byId('rb_room_type_id');
            var totalRoomsInput = byId('rb_total_rooms');
            var specificCount = byId('rb_specific_count');
            var startDate = byId('rb_start_date');
            var endDate = byId('rb_end_date');

            function renderCounts() {
                if (!specificCount) return;
                var count = selectedRoomsCount(roomsList);
                specificCount.textContent = String(count);
                if (roomsDropdownLabel) {
                    roomsDropdownLabel.textContent = count > 0 ? (count + ' selected') : 'Select rooms';
                }
            }

            function closeRoomsDropdown() {
                if (!roomsDropdownPanel) return;
                roomsDropdownPanel.classList.add('hidden');
                if (roomsDropdownBtn) roomsDropdownBtn.setAttribute('aria-expanded', 'false');
            }

            function toggleRoomsDropdown() {
                if (!roomsDropdownPanel || !roomsDropdownBtn) return;
                var isHidden = roomsDropdownPanel.classList.contains('hidden');
                if (isHidden) {
                    roomsDropdownPanel.classList.remove('hidden');
                    roomsDropdownBtn.setAttribute('aria-expanded', 'true');
                    if (roomsSearch && !roomsSearch.disabled) {
                        roomsSearch.focus();
                    }
                    return;
                }
                closeRoomsDropdown();
            }

            function applyRoomsFilter() {
                if (!roomsList || !roomsSearch) return;
                var q = String(roomsSearch.value || '').trim().toLowerCase();
                var items = Array.from(roomsList.querySelectorAll('[data-room-item="1"]') || []);
                var visible = 0;
                items.forEach(function (el) {
                    var hay = String(el.getAttribute('data-search') || '').toLowerCase();
                    var show = q === '' || hay.indexOf(q) !== -1;
                    el.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                if (roomsEmpty) {
                    roomsEmpty.classList.toggle('hidden', visible !== 0);
                }
            }

            function applyMode(mode) {
                var isSpecific = mode === 'specific';

                setDisabled(roomsSearch, !isSpecific);
                setDisabled(roomsClearBtn, !isSpecific);
                setDisabled(roomsDropdownBtn, !isSpecific);
                if (roomsList) {
                    Array.from(roomsList.querySelectorAll('input[type="checkbox"][name="room_ids[]"]') || [])
                        .forEach(function (cb) { cb.disabled = !isSpecific; });
                }
                setDisabled(roomTypeSelect, isSpecific);
                setDisabled(totalRoomsInput, isSpecific);

                if (isSpecific) {
                    if (roomTypeSelect) roomTypeSelect.value = '';
                    if (totalRoomsInput) totalRoomsInput.value = '';
                } else {
                    clearRoomsSelection(roomsList);
                    closeRoomsDropdown();
                }

                renderCounts();
            }

            function detectModeFromOldInput() {
                var hasSpecific = selectedRoomsCount(roomsList) > 0;
                var hasAuto = (roomTypeSelect && roomTypeSelect.value) || (totalRoomsInput && String(totalRoomsInput.value || '').trim() !== '');
                if (hasSpecific) return 'specific';
                if (hasAuto) return 'auto';
                return 'specific';
            }

            if (startDate && !startDate.min) {
                var d = new Date();
                d.setHours(0, 0, 0, 0);
                var yyyy = d.getFullYear();
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var dd = String(d.getDate()).padStart(2, '0');
                startDate.min = yyyy + '-' + mm + '-' + dd;
            }

            if (startDate && endDate) {
                startDate.addEventListener('change', function () { normalizeDateInputs(startDate, endDate); });
                normalizeDateInputs(startDate, endDate);
            }

            if (roomsList) {
                roomsList.addEventListener('change', function (e) {
                    var t = e && e.target ? e.target : null;
                    if (!t || String(t.tagName || '').toLowerCase() !== 'input') return;
                    if (t.type !== 'checkbox') return;
                    if (t.name !== 'room_ids[]') return;

                    renderCounts();
                    if (modeSpecific) modeSpecific.checked = true;
                    applyMode('specific');
                });
            }

            if (roomsDropdownBtn) {
                roomsDropdownBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (roomsDropdownBtn.disabled) return;
                    toggleRoomsDropdown();
                });
            }

            document.addEventListener('click', function (e) {
                if (!roomsDropdownPanel || roomsDropdownPanel.classList.contains('hidden')) return;
                var target = e && e.target ? e.target : null;
                if (!target) return;
                if (roomsDropdownPanel.contains(target) || (roomsDropdownBtn && roomsDropdownBtn.contains(target))) return;
                closeRoomsDropdown();
            });

            document.addEventListener('keydown', function (e) {
                if (!roomsDropdownPanel || roomsDropdownPanel.classList.contains('hidden')) return;
                if ((e && e.key) !== 'Escape') return;
                closeRoomsDropdown();
            });

            if (roomsSearch) {
                roomsSearch.addEventListener('input', applyRoomsFilter);
            }

            if (roomsClearBtn) {
                roomsClearBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    clearRoomsSelection(roomsList);
                    renderCounts();
                });
            }

            if (roomTypeSelect) {
                roomTypeSelect.addEventListener('change', function () {
                    if (modeAuto) modeAuto.checked = true;
                    applyMode('auto');
                });
            }

            if (totalRoomsInput) {
                totalRoomsInput.addEventListener('input', function () {
                    if (String(totalRoomsInput.value || '').trim() !== '') {
                        if (modeAuto) modeAuto.checked = true;
                        applyMode('auto');
                    }
                });
            }

            if (modeSpecific) {
                modeSpecific.addEventListener('change', function () { if (modeSpecific.checked) applyMode('specific'); });
            }
            if (modeAuto) {
                modeAuto.addEventListener('change', function () { if (modeAuto.checked) applyMode('auto'); });
            }

            var initialMode = detectModeFromOldInput();
            if (initialMode === 'auto' && modeAuto) modeAuto.checked = true;
            if (initialMode === 'specific' && modeSpecific) modeSpecific.checked = true;
            applyMode(initialMode);

            applyRoomsFilter();
            renderCounts();
        }

        document.addEventListener('DOMContentLoaded', init);
    })();
</script>
@endpush

@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Create Room Block</h3>
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

        <form method="POST" action="{{ route('admin.room-blocks.store') }}" class="grid gap-4 grid-cols-1 lg:grid-cols-2">
            @csrf

            <div class="lg:col-span-2">
                <div class="rounded border border-border bg-background overflow-hidden">
                    <div class="px-4 py-3 border-b border-border bg-muted/10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2">
                        <div>
                            <div class="font-medium">Block Details</div>
                        </div>
                    </div>

                    <div class="p-4 grid gap-4 grid-cols-1 lg:grid-cols-2">
                        <div>
                            <label class="text-sm text-secondary-foreground required-label">Group Name</label>
                            <input class="kt-input w-full" name="group_name" required value="{{ old('group_name') }}" />
                        </div>

                        <div>
                            <label class="text-sm text-secondary-foreground required-label">Status</label>
                            <select class="kt-input w-full" name="status" required>
                                @foreach(['tentative','confirmed','cancelled'] as $st)
                                    <option value="{{ $st }}" {{ old('status','tentative')===$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="text-sm text-secondary-foreground required-label">Stay Dates</div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-2">
                                <div>
                                    <label class="text-xs text-muted-foreground">Start Date</label>
                                    <input id="rb_start_date" type="date" class="kt-input w-full" name="start_date" required value="{{ old('start_date') }}" />
                                </div>
                                <div>
                                    <label class="text-xs text-muted-foreground">End Date</label>
                                    <input id="rb_end_date" type="date" class="kt-input w-full" name="end_date" required value="{{ old('end_date') }}" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-secondary-foreground">Release Deadline (auto-expire)</label>
                            <input type="datetime-local" class="kt-input w-full" name="release_at" value="{{ old('release_at') }}" />
                        </div>

                        <div>
                            <label class="text-sm text-secondary-foreground">Notes</label>
                            <textarea class="kt-input w-full" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="font-medium">Assign Inventory</div>
            </div>

            <div class="lg:col-span-2">
                <div class="p-3 rounded bg-muted/30">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-3">
                        <div class="font-medium">Assignment Mode</div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <label class="inline-flex items-center gap-2">
                                <input id="rb_mode_specific" type="radio" name="__rb_mode" value="specific" checked>
                                Select specific rooms
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input id="rb_mode_auto" type="radio" name="__rb_mode" value="auto">
                                Auto-assign by room type
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between">
                                    <label class="text-sm text-secondary-foreground">Specific Rooms</label>
                                    <div class="text-xs text-muted-foreground">Selected: <span id="rb_specific_count">0</span></div>
                            </div>
                                <div class="mt-2 relative">
                                    <button
                                        id="rb_rooms_dropdown_btn"
                                        type="button"
                                        class="kt-input w-full flex items-center justify-between gap-3"
                                        aria-haspopup="listbox"
                                        aria-expanded="false"
                                    >
                                        <span id="rb_rooms_dropdown_label" class="truncate">Select rooms</span>
                                        <span class="text-muted-foreground">▾</span>
                                    </button>

                                    <div id="rb_rooms_dropdown_panel" class="hidden absolute z-20 mt-2 w-full rounded border border-input bg-background overflow-hidden">
                                        <div class="p-3 border-b border-border bg-muted/10">
                                            <div class="flex items-center gap-2">
                                                <input id="rb_rooms_search" type="text" class="kt-input w-full" placeholder="Search room number, type, floor..." autocomplete="off" />
                                                <button id="rb_rooms_clear" class="kt-btn" type="button">Clear</button>
                                            </div>
                                        </div>

                                        <div id="rb_rooms_list" class="max-h-[360px] overflow-auto">
                                        @php($oldRoomIds = (array) old('room_ids', []))
                                        @foreach($rooms as $r)
                                            @php($typeName = $r->roomType->name ?? 'Type')
                                            @php($floorName = $r->floor->name ?? '')
                                            @php($search = strtolower(trim(($r->room_number ?? '') . ' ' . $typeName . ' ' . $floorName)))
                                            <div data-room-item="1" data-search="{{ $search }}" class="px-3 py-2 border-b border-border last:border-b-0 hover:bg-accent/10">
                                                <label class="flex items-center justify-between gap-3 cursor-pointer">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <input type="checkbox" name="room_ids[]" value="{{ $r->id }}" {{ in_array($r->id, $oldRoomIds) ? 'checked' : '' }} />
                                                        <div class="min-w-0">
                                                            <div class="text-sm font-medium text-foreground truncate">Room {{ $r->room_number }}</div>
                                                            <div class="text-xs text-muted-foreground truncate">{{ $typeName }}@if($floorName) • {{ $floorName }}@endif</div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-muted-foreground whitespace-nowrap">{{ ucfirst($r->status ?? 'available') }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                        <div id="rb_rooms_empty" class="hidden px-3 py-6 text-center text-sm text-muted-foreground">No rooms match your search.</div>
                                    </div>
                                    </div>
                                </div>
                        </div>

                        <div>
                            <label class="text-sm text-secondary-foreground">Auto-Assign by Room Type</label>
                            <div class="grid grid-cols-1 gap-2">
                                <select id="rb_room_type_id" name="room_type_id" class="kt-input w-full">
                                    <option value="">-- Select Room Type --</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t->id }}" {{ old('room_type_id')==$t->id ? 'selected':'' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <input id="rb_total_rooms" class="kt-input w-full" type="number" min="1" name="total_rooms" placeholder="Total rooms" value="{{ old('total_rooms') }}" />
                                <div class="text-xs text-muted-foreground">Rooms will be picked from available inventory for the date range.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Create Block</button>
                <a class="kt-btn" href="{{ route('admin.room-blocks.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
