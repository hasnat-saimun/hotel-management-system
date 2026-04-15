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
                <div class="text-xs text-secondary-foreground mt-0.5">Enter guest info and confirm reservation</div>

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
                            <div class="text-xs font-semibold text-secondary-foreground">Guest</div>
                            <button type="button" class="kt-btn kt-btn-sm" data-kt-modal-toggle="#quick_add_guest_modal">Quick Add Guest</button>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground required-label">First name</label>
                        <input type="text" class="kt-input w-full" name="guest_first_name" value="{{ old('guest_first_name') }}" required placeholder="First name" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Last name</label>
                        <input type="text" class="kt-input w-full" name="guest_last_name" value="{{ old('guest_last_name') }}" required placeholder="Last name" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground required-label">Email</label>
                        <input type="email" class="kt-input w-full" name="guest_email" value="{{ old('guest_email') }}" required placeholder="guest@example.com" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Phone</label>
                        <input type="text" class="kt-input w-full" name="guest_phone" value="{{ old('guest_phone') }}" placeholder="Phone number" />
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Address</label>
                        <textarea class="kt-input w-full" name="guest_address" rows="2" placeholder="Street, city, country">{{ old('guest_address') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">ID Type</label>
                        <select class="kt-input w-full" name="guest_id_type">
                            <option value="">Select</option>
                            @php($idType = old('guest_id_type'))
                            <option value="passport" @selected($idType === 'passport')>Passport</option>
                            <option value="driver_license" @selected($idType === 'driver_license')>Driver license</option>
                            <option value="national_id" @selected($idType === 'national_id')>National ID</option>
                            <option value="other" @selected($idType === 'other')>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">ID Number</label>
                        <input type="text" class="kt-input w-full" name="guest_id_number" value="{{ old('guest_id_number') }}" placeholder="Document number" />
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

@push('scripts')
<script>
(function(){
    var form = document.getElementById('quick_add_guest_form');
    var errBox = document.getElementById('quick_add_guest_error');
    if (!form) return;

    function showError(html){
        if (!errBox) return;
        errBox.innerHTML = html;
        errBox.style.display = 'block';
    }

    function clearError(){
        if (!errBox) return;
        errBox.innerHTML = '';
        errBox.style.display = 'none';
    }

    form.addEventListener('submit', async function(e){
        e.preventDefault();
        clearError();

        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        try {
            var resp = await fetch(@json(route('admin.api.guests.store')), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            var json = await resp.json().catch(function(){ return null; });

            if (!resp.ok) {
                if (json && json.errors) {
                    var msgs = Object.values(json.errors).flat().map(function(m){ return '<div>' + m + '</div>'; }).join('');
                    showError(msgs || 'Validation error');
                } else {
                    showError('Unable to create guest.');
                }
                return;
            }

            if (!json || !json.success || !json.guest) {
                showError('Unexpected response from server.');
                return;
            }

            var g = json.guest;
            var setVal = function(name, val){
                var el = document.querySelector('[name="' + name + '"]');
                if (el) el.value = val || '';
            };

            setVal('guest_first_name', g.first_name);
            setVal('guest_last_name', g.last_name);
            setVal('guest_email', g.email);
            setVal('guest_phone', g.phone);
            setVal('guest_address', g.address);

            // Close modal by triggering any dismiss button.
            var dismiss = document.querySelector('#quick_add_guest_modal [data-kt-modal-dismiss="true"]');
            if (dismiss) dismiss.click();
            form.reset();
        } catch (err) {
            showError('Network error.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
})();
</script>
@endpush
@endsection
