@extends('admin.layouts.app')
@section('title', 'Create Reservation')

@section('content')
@php($rooms = $rooms ?? collect())
@php($roomId = old('room_id', request('room_id', $roomId ?? '')))
@php($selectedDates = $selectedDates ?? collect())
@php($selectedRoom = $roomId ? $rooms->firstWhere('id', (int) $roomId) : null)
@php($dateSegments = $dateSegments ?? collect())
@php($hasMultipleSegments = $dateSegments->count() > 1)
@php($backMonth = $selectedDates->isNotEmpty() ? \Carbon\Carbon::parse($selectedDates->first())->format('Y-m') : null)
@php($backDates = $selectedDates->implode(','))

@php(
    $ordinal = function (int $n) {
        if ($n % 100 >= 11 && $n % 100 <= 13) return $n . 'th';
        switch ($n % 10) {
            case 1: return $n . 'st';
            case 2: return $n . 'nd';
            case 3: return $n . 'rd';
            default: return $n . 'th';
        }
    }
)

<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Create Reservation</h3>
            <div class="text-sm text-secondary-foreground">Prefilled from calendar selection</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.reservations.calendar-by-room', array_filter(['room_id' => $roomId, 'month' => $backMonth, 'dates' => $backDates], fn ($v) => $v !== null && $v !== '')) }}">Back to Calendar</a>
    </div>

    <div class="kt-card-content p-4">
        <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
            <div class="rounded border border-input bg-background p-4 lg:col-span-1">
                <div class="text-sm font-medium text-foreground">Reservation Summary</div>
                <div class="text-xs text-secondary-foreground mt-0.5">Review selection from calendar</div>

                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Room</div>
                        <div class="text-sm text-foreground mt-1">
                            @if($selectedRoom)
                                <div class="font-medium">
                                    {{ $selectedRoom->room_number ?? ('Room #' . $selectedRoom->id) }}
                                </div>
                                <div class="text-secondary-foreground">
                                    {{ $selectedRoom->roomType?->name ?? '-' }}
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-xs font-semibold text-secondary-foreground">Base price (per day)</div>
                                        <div class="text-sm text-foreground mt-1">
                                            @if($selectedRoom->roomType?->base_price !== null)
                                                {{ number_format((float) $selectedRoom->roomType->base_price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-secondary-foreground">Capacity</div>
                                        <div class="text-sm text-foreground mt-1">
                                            A: {{ (int) ($selectedRoom->roomType?->capacity_adults ?? 0) }} - C: {{ (int) ($selectedRoom->roomType?->capacity_children ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if($dateSegments->isNotEmpty())
                        <div>
                            <div class="text-xs font-semibold text-secondary-foreground">Stays</div>
                            <div class="mt-2 space-y-2">
                                @foreach($dateSegments as $idx => $seg)
                                    <div class="rounded border border-input bg-background p-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-medium text-foreground">{{ $ordinal($idx + 1) }} stay</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 grid grid-cols-2 gap-3">
                                            <div>
                                                <div class="text-xs font-semibold text-secondary-foreground">Check-in</div>
                                                <div class="text-sm text-foreground mt-1">{{ $seg['check_in'] ?? '-' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs font-semibold text-secondary-foreground">Check-out</div>
                                                <div class="text-sm text-foreground mt-1">{{ $seg['check_out'] ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($hasMultipleSegments)
                                <div class="mt-2 text-xs text-secondary-foreground">
                                    Your selected dates contain gaps. This will create {{ $dateSegments->count() }} reservations.
                                </div>
                            @endif
                        </div>
                    @endif

                    <div>
                        <div class="text-xs font-semibold text-secondary-foreground">Selected dates</div>
                        @if($selectedDates->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($selectedDates as $date)
                                    <span class="kt-badge kt-badge-sm kt-badge-outline">{{ $date }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-1 text-sm text-secondary-foreground">No dates selected.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded border border-input bg-background p-4 lg:col-span-2">
                <div class="text-sm font-medium text-foreground">Guest Reservation Form</div>
                <div class="text-xs text-secondary-foreground mt-0.5">Select guest and confirm reservation</div>

                @if($errors->any())
                    <div class="mt-4 p-3 bg-danger/10 text-danger rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.reservations.store') }}" class="mt-4 grid gap-3 grid-cols-1 lg:grid-cols-2">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $roomId }}" />
                    <input type="hidden" name="dates" value="{{ old('dates', $backDates) }}" />

                    <div class="lg:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm text-secondary-foreground">
                            <input type="checkbox" name="ignore_blocks" value="1" {{ old('ignore_blocks') ? 'checked' : '' }} />
                            Override Room Blocks (admin)
                        </label>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="flex items-center justify-between gap-2">
                            <label class="text-xs font-semibold text-secondary-foreground required-label">Guest</label>
                            <button type="button" class="kt-btn kt-btn-sm" data-kt-modal-toggle="#quick_add_guest_modal">Quick Guest</button>
                        </div>

                        @php($selectedGuestId = old('guest_id'))
                        <select
                            class="kt-select w-full"
                            data-kt-select="true"
                            data-kt-select-placeholder="Select guest"
                            data-kt-select-enable-search="true"
                            data-kt-select-search-placeholder="Search guests..."
                            name="guest_id"
                            id="guest_id_select"
                        >
                            <option value=""></option>
                            @foreach(($guests ?? collect()) as $g)
                                @php($labelName = trim(($g->first_name ?? '') . ' ' . ($g->last_name ?? '')))
                                <option value="{{ $g->id }}" @selected((string)$selectedGuestId === (string)$g->id)>
                                    {{ $labelName !== '' ? $labelName : ('Guest #' . $g->id) }} — {{ $g->id_number ?: '-' }}
                                </option>
                            @endforeach
                        </select>

                        <div id="guest_readonly_box" class="mt-2 rounded border border-input bg-background p-3 text-sm" style="display:none;">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                                <div><span class="text-secondary-foreground">Email:</span> <span id="guest_ro_email"></span></div>
                                <div><span class="text-secondary-foreground">Phone:</span> <span id="guest_ro_phone"></span></div>
                                <div><span class="text-secondary-foreground">ID Type:</span> <span id="guest_ro_id_type"></span></div>
                                <div><span class="text-secondary-foreground">ID Number:</span> <span id="guest_ro_id_number"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="text-xs font-semibold text-secondary-foreground">Stay</div>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Adults</label>
                        <input type="number" min="1" class="kt-input w-full" name="adults" value="{{ old('adults', 1) }}" placeholder="1" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Children</label>
                        <input type="number" min="0" class="kt-input w-full" name="children" value="{{ old('children', 0) }}" placeholder="0" />
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Special Requests</label>
                        <textarea class="kt-input w-full" name="special_requests" rows="3" placeholder="Any special requests...">{{ old('special_requests') }}</textarea>
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Note (optional)</label>
                        <textarea class="kt-input w-full" name="note" rows="3" placeholder="Internal note...">{{ old('note') }}</textarea>
                    </div>

                    <div class="lg:col-span-2 flex gap-2">
                        <button type="submit" class="kt-btn kt-btn-primary">{{ $hasMultipleSegments ? 'Confirm Reservations' : 'Confirm Reservation' }}</button>
                        <button type="reset" class="kt-btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.guests._quick_add_modal')

@php(
    $guestsIndex = ($guests ?? collect())
        ->mapWithKeys(function ($g) {
            return [
                (string) $g->id => [
                    'email' => $g->email,
                    'phone' => $g->phone,
                    'id_type' => $g->id_type,
                    'id_number' => $g->id_number,
                ],
            ];
        })
        ->toArray()
)

@push('scripts')
<script>
(function(){
    var guestSelect = document.getElementById('guest_id_select');

    var roBox = document.getElementById('guest_readonly_box');
    var roEmail = document.getElementById('guest_ro_email');
    var roPhone = document.getElementById('guest_ro_phone');
    var roIdType = document.getElementById('guest_ro_id_type');
    var roIdNumber = document.getElementById('guest_ro_id_number');

    var quickAddForm = document.getElementById('quick_add_guest_form');
    var quickAddErrBox = document.getElementById('quick_add_guest_error');

    var blacklistToggle = document.getElementById('quick_blacklisted_toggle');
    var blacklistFields = document.getElementById('quick_blacklist_fields');

    var guestsIndex = @json($guestsIndex ?? []);

    function escapeHtml(s){
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function norm(v){
        if (v === null || v === undefined) return '-';
        var s = String(v).trim();
        return s === '' ? '-' : s;
    }

    function updateReadonly(){
        if (!guestSelect || !roBox) return;

        var id = String(guestSelect.value || '');
        var g = guestsIndex && Object.prototype.hasOwnProperty.call(guestsIndex, id) ? guestsIndex[id] : null;

        if (!id || !g) {
            roBox.style.display = 'none';
            if (roEmail) roEmail.textContent = '';
            if (roPhone) roPhone.textContent = '';
            if (roIdType) roIdType.textContent = '';
            if (roIdNumber) roIdNumber.textContent = '';
            return;
        }

        if (roEmail) roEmail.textContent = norm(g.email);
        if (roPhone) roPhone.textContent = norm(g.phone);
        if (roIdType) roIdType.textContent = norm(g.id_type);
        if (roIdNumber) roIdNumber.textContent = norm(g.id_number);

        roBox.style.display = 'block';
    }

    if (guestSelect) {
        guestSelect.addEventListener('change', updateReadonly);
        updateReadonly();
    }

    function syncBlacklistFields(){
        if (!blacklistToggle || !blacklistFields) return;
        blacklistFields.style.display = blacklistToggle.checked ? 'block' : 'none';
    }

    if (blacklistToggle) {
        blacklistToggle.addEventListener('change', syncBlacklistFields);
        syncBlacklistFields();
    }

    function showQuickAddError(messages){
        if (!quickAddErrBox) return;
        var msgs = Array.isArray(messages) ? messages : [messages];
        quickAddErrBox.innerHTML = msgs.map(function(m){
            return '<div>' + escapeHtml(m) + '</div>';
        }).join('');
        quickAddErrBox.style.display = 'block';
    }

    function clearQuickAddError(){
        if (!quickAddErrBox) return;
        quickAddErrBox.innerHTML = '';
        quickAddErrBox.style.display = 'none';
    }

    function upsertGuestOption(id, label){
        if (!guestSelect) return;

        var idStr = String(id);
        var existing = null;
        Array.from(guestSelect.options || []).some(function(o){
            if (o.value === idStr) {
                existing = o;
                return true;
            }
            return false;
        });

        if (existing) {
            existing.textContent = label;
            return;
        }

        var opt = document.createElement('option');
        opt.value = idStr;
        opt.textContent = label;
        guestSelect.appendChild(opt);
    }

    if (quickAddForm) {
        quickAddForm.addEventListener('submit', async function(e){
            e.preventDefault();
            clearQuickAddError();

            var submitBtn = quickAddForm.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            try {
                var resp = await fetch(@json(route('admin.api.guests.store')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json'
                    },
                    body: new FormData(quickAddForm)
                });

                var json = await resp.json().catch(function(){ return null; });

                if (!resp.ok) {
                    if (json && json.errors) {
                        var msgs = Object.values(json.errors).flat();
                        showQuickAddError(msgs.length ? msgs : ['Validation error']);
                    } else {
                        showQuickAddError('Unable to create guest.');
                    }
                    return;
                }

                if (!json || !json.success || !json.guest) {
                    showQuickAddError('Unexpected response from server.');
                    return;
                }

                var g = json.guest;
                var fullName = (g.full_name || ((g.first_name || '') + ' ' + (g.last_name || ''))).trim();
                var idNumber = (g.id_number || '').trim();

                guestsIndex[String(g.id)] = {
                    email: g.email || null,
                    phone: g.phone || null,
                    id_type: g.id_type || null,
                    id_number: g.id_number || null,
                };

                var label = (fullName || ('Guest #' + g.id)) + ' — ' + (idNumber !== '' ? idNumber : '-');
                upsertGuestOption(g.id, label);

                if (guestSelect) {
                    guestSelect.value = String(g.id);
                    guestSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }

                var dismiss = document.querySelector('#quick_add_guest_modal [data-kt-modal-dismiss="true"]');
                if (dismiss) dismiss.click();

                quickAddForm.reset();
                syncBlacklistFields();
            } catch (err) {
                showQuickAddError('Network error.');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }
})();
</script>
@endpush
@endsection
