@extends('admin.layouts.app')
@section('title', 'Manage Room Block')

@push('scripts')
<script>
    (function () {
        function byId(id) { return document.getElementById(id); }

        function initAssignRoomsDropdown() {
            var btn = byId('rb_assign_rooms_btn');
            var panel = byId('rb_assign_rooms_panel');
            var search = byId('rb_assign_rooms_search');
            var clearBtn = byId('rb_assign_rooms_clear');
            var list = byId('rb_assign_rooms_list');
            var empty = byId('rb_assign_rooms_empty');
            var countEl = byId('rb_assign_rooms_count');
            var labelEl = byId('rb_assign_rooms_label');

            if (!btn || !panel || !list) return;

            function selectedCount() {
                return Array.from(list.querySelectorAll('input[type="checkbox"][name="room_ids[]"]:checked') || []).length;
            }

            function renderCount() {
                var c = selectedCount();
                if (countEl) countEl.textContent = String(c);
                if (labelEl) labelEl.textContent = c > 0 ? (c + ' selected') : 'Select rooms';
            }

            function close() {
                panel.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }

            function open() {
                panel.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
                if (search) search.focus();
            }

            function toggle() {
                if (panel.classList.contains('hidden')) {
                    open();
                    return;
                }
                close();
            }

            function applyFilter() {
                if (!search) return;
                var q = String(search.value || '').trim().toLowerCase();
                var items = Array.from(list.querySelectorAll('[data-room-item="1"]') || []);
                var visible = 0;
                items.forEach(function (el) {
                    var hay = String(el.getAttribute('data-search') || '').toLowerCase();
                    var show = q === '' || hay.indexOf(q) !== -1;
                    el.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                if (empty) empty.classList.toggle('hidden', visible !== 0);
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                toggle();
            });

            document.addEventListener('click', function (e) {
                if (panel.classList.contains('hidden')) return;
                var target = e && e.target ? e.target : null;
                if (!target) return;
                if (panel.contains(target) || btn.contains(target)) return;
                close();
            });

            document.addEventListener('keydown', function (e) {
                if (panel.classList.contains('hidden')) return;
                if ((e && e.key) !== 'Escape') return;
                close();
            });

            list.addEventListener('change', function (e) {
                var t = e && e.target ? e.target : null;
                if (!t || String(t.tagName || '').toLowerCase() !== 'input') return;
                if (t.type !== 'checkbox' || t.name !== 'room_ids[]') return;
                renderCount();
            });

            if (search) search.addEventListener('input', applyFilter);

            if (clearBtn) {
                clearBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    Array.from(list.querySelectorAll('input[type="checkbox"][name="room_ids[]"]') || [])
                        .forEach(function (cb) { cb.checked = false; });
                    renderCount();
                });
            }

            renderCount();
            applyFilter();
        }

        document.addEventListener('DOMContentLoaded', initAssignRoomsDropdown);
    })();
</script>
@endpush

@section('content')
@php($blockExpired = !empty($block->release_at) && $block->release_at->lessThanOrEqualTo(now()))
@php($inventoryLocked = (($block->status ?? null) === 'cancelled') || !empty($block->released_at) || $blockExpired)

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <h3 class="kt-card-title">Block #{{ $block->id }} — {{ $block->group_name }}</h3>
                <div class="flex gap-2">
                    @if(($block->status ?? null) === 'confirmed' && !$inventoryLocked)
                        <a class="kt-btn kt-btn-primary" href="{{ route('admin.room-blocks.convert', $block->id) }}">Create Reservations</a>
                    @endif
                    <form method="POST" action="{{ route('admin.room-blocks.release', $block->id) }}">
                        @csrf
                        <button class="kt-btn" type="submit" onclick="return confirm('Release this block inventory?')">Release</button>
                    </form>
                </div>
            </div>
            <div class="kt-card-content p-4">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div class="p-3 rounded bg-muted/30">
                        <div class="text-sm text-muted-foreground">Dates</div>
                        <div class="font-medium">{{ $block->start_date->toDateString() }} → {{ $block->end_date->toDateString() }}</div>
                    </div>
                    <div class="p-3 rounded bg-muted/30">
                        <div class="text-sm text-muted-foreground">Status</div>
                        <div class="font-medium">{{ ucfirst($block->status) }} @if($block->released_at) (Released) @endif</div>
                    </div>
                </div>

                <div class="p-3 rounded bg-muted/30 mb-4">
                    <div class="text-sm text-muted-foreground">Rooms blocked</div>
                    <div class="font-medium">{{ $block->roomBlockRooms->where('status','blocked')->count() }} blocked, {{ $block->roomBlockRooms->where('status','converted')->count() }} converted</div>
                </div>

                @if($inventoryLocked)
                    <div class="mb-4 p-3 bg-muted/30 text-sm text-muted-foreground rounded">
                        This block is cancelled/released/expired. Room assignments are locked (read-only).
                    </div>
                @endif

                <div class="font-medium mb-2">Assigned Rooms</div>

                <form method="POST" action="{{ route('admin.room-blocks.unassign-rooms', $block->id) }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="kt-table w-full">
                            <thead>
                                <tr>
                                    <th class="p-2"></th>
                                    <th class="text-left p-2">Room</th>
                                    <th class="text-left p-2">Type</th>
                                    <th class="text-left p-2">Guest (planned)</th>
                                    <th class="text-left p-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($block->roomBlockRooms as $r)
                                    <tr class="border-t">
                                        <td class="p-2">
                                            @if(!$inventoryLocked && $r->status !== 'converted')
                                                <input type="checkbox" name="room_block_room_ids[]" value="{{ $r->id }}">
                                            @endif
                                        </td>
                                        <td class="p-2">{{ $r->room->room_number ?? '—' }}</td>
                                        <td class="p-2">{{ $r->room->roomType->name ?? '—' }}</td>
                                        <td class="p-2">
                                            @if($r->assignedGuest)
                                                {{ $r->assignedGuest->first_name }} {{ $r->assignedGuest->last_name }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="p-2">{{ ucfirst($r->status) }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="p-3 text-center" colspan="5">No rooms assigned yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <button class="kt-btn" type="submit" onclick="return confirm('Unassign selected rooms?')" {{ $inventoryLocked ? 'disabled' : '' }}>Unassign Selected</button>
                        <a class="kt-btn" href="{{ route('admin.room-blocks.index') }}">Back</a>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <div class="font-medium">Block Reservations</div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.room-blocks.checkin-all-confirmed', $block->id) }}">
                                @csrf
                                <button class="kt-btn kt-btn-sm" type="submit" onclick="return confirm('Check-in all CONFIRMED reservations in this block?')">Check-in All Confirmed</button>
                            </form>
                            <a class="kt-btn kt-btn-sm" href="{{ route('admin.reservations.index') }}">All Reservations</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <form method="POST" action="{{ route('admin.room-blocks.checkin-selected', $block->id) }}">
                            @csrf
                            <table class="kt-table w-full">
                            <thead>
                                <tr>
                                    <th class="p-2"></th>
                                    <th class="text-left p-2">Reservation</th>
                                    <th class="text-left p-2">Guest</th>
                                    <th class="text-left p-2">Room</th>
                                    <th class="text-left p-2">Check-in</th>
                                    <th class="text-left p-2">Check-out</th>
                                    <th class="text-left p-2">Status</th>
                                    <th class="text-right p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($block->reservations ?? collect()) as $res)
                                    <tr class="border-t">
                                        <td class="p-2">
                                            @if(($res->status ?? null) === 'confirmed')
                                                <input type="checkbox" name="reservation_ids[]" value="{{ $res->id }}" />
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="p-2">
                                            <div class="font-medium">#{{ $res->id }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $res->reservation_code ?? '—' }}</div>
                                        </td>
                                        <td class="p-2">
                                            {{ $res->guest?->first_name ?? '—' }} {{ $res->guest?->last_name ?? '' }}
                                            @if(!empty($res->guest?->email))
                                                <div class="text-xs text-muted-foreground">{{ $res->guest->email }}</div>
                                            @endif
                                        </td>
                                        <td class="p-2">
                                            @php($rooms = $res->rooms ?? collect())
                                            @if($rooms->isNotEmpty())
                                                @foreach($rooms as $room)
                                                    <div class="text-xs">{{ $room->room_number ?? '—' }}</div>
                                                @endforeach
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="p-2">{{ optional($res->check_in_date)->toDateString() ?? '—' }}</td>
                                        <td class="p-2">{{ optional($res->check_out_date)->toDateString() ?? '—' }}</td>
                                        <td class="p-2">{{ ucfirst(str_replace('_',' ', (string) ($res->status ?? 'booked'))) }}</td>
                                        <td class="p-2 text-right">
                                            <a class="kt-btn kt-btn-sm kt-btn-ghost" href="{{ route('admin.reservations.show', $res->id) }}">Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="p-3 text-center" colspan="7">No reservations created from this block yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            </table>

                            @if(($block->reservations ?? collect())->isNotEmpty())
                                <div class="mt-3 flex gap-2">
                                    <button class="kt-btn kt-btn-sm" type="submit" onclick="return confirm('Check-in selected CONFIRMED reservations?')">Check-in Selected</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="kt-card mb-4">
            <div class="kt-card-header">
                <h3 class="kt-card-title">Update Block</h3>
            </div>
            <div class="kt-card-content p-4">
                <form method="POST" action="{{ route('admin.room-blocks.update', $block->id) }}" class="grid gap-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Group Name</label>
                        <input class="kt-input w-full" name="group_name" required value="{{ old('group_name', $block->group_name) }}" />
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Status</label>
                        <select class="kt-input w-full" name="status" required>
                            @foreach(['tentative','confirmed','cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $block->status)===$st ? 'selected':'' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground">Release Deadline</label>
                        <input type="datetime-local" class="kt-input w-full" name="release_at" value="{{ old('release_at', optional($block->release_at)->format('Y-m-d\TH:i')) }}" />
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground">Notes</label>
                        <textarea class="kt-input w-full" name="notes" rows="3">{{ old('notes', $block->notes) }}</textarea>
                    </div>
                    <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                </form>
            </div>
        </div>

        @if(!$inventoryLocked)
        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title">Assign More Rooms</h3>
            </div>
            <div class="kt-card-content p-4">
                <form method="POST" action="{{ route('admin.room-blocks.assign-rooms', $block->id) }}" class="grid gap-3">
                    @csrf
                    <div>
                        <label class="text-sm text-secondary-foreground">Available rooms for these dates</label>

                        <div class="mt-2 relative">
                            <button
                                id="rb_assign_rooms_btn"
                                type="button"
                                class="kt-input w-full flex items-center justify-between gap-3"
                                aria-haspopup="listbox"
                                aria-expanded="false"
                            >
                                <span id="rb_assign_rooms_label" class="truncate">Select rooms</span>
                                <span class="text-muted-foreground">▾</span>
                            </button>

                            <div id="rb_assign_rooms_panel" class="hidden absolute z-20 mt-2 w-full rounded border border-input bg-background overflow-hidden">
                                <div class="p-3 border-b border-border bg-muted/10">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="text-xs text-muted-foreground">Selected: <span id="rb_assign_rooms_count">0</span></div>
                                        <button id="rb_assign_rooms_clear" class="kt-btn" type="button">Clear</button>
                                    </div>
                                    <div class="mt-2">
                                        <input id="rb_assign_rooms_search" type="text" class="kt-input w-full" placeholder="Search room number, type..." autocomplete="off" />
                                    </div>
                                </div>

                                <div id="rb_assign_rooms_list" class="max-h-[360px] overflow-auto">
                                    @foreach($availableRooms as $r)
                                        @php($typeName = $r->roomType->name ?? 'Type')
                                        @php($search = strtolower(trim(($r->room_number ?? '') . ' ' . $typeName)))
                                        <div data-room-item="1" data-search="{{ $search }}" class="px-3 py-2 border-b border-border last:border-b-0 hover:bg-accent/10">
                                            <label class="flex items-center justify-between gap-3 cursor-pointer">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <input type="checkbox" name="room_ids[]" value="{{ $r->id }}" />
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium text-foreground truncate">Room {{ $r->room_number }}</div>
                                                        <div class="text-xs text-muted-foreground truncate">{{ $typeName }}</div>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-muted-foreground whitespace-nowrap">{{ ucfirst($r->status ?? 'available') }}</span>
                                            </label>
                                        </div>
                                    @endforeach

                                    <div id="rb_assign_rooms_empty" class="hidden px-3 py-6 text-center text-sm text-muted-foreground">No rooms match your search.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="kt-btn" type="submit">Assign Selected</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
