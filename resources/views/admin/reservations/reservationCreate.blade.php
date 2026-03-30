@extends('admin.layouts.app')
@section('title', 'Create Reservation')

@section('content')
@php($rooms = $rooms ?? collect())
@php($roomId = old('room_id', request('room_id', $roomId ?? '')))
@php($checkInDate = old('check_in_date', request('check_in_date', $checkInDate ?? '')))
@php($checkOutDate = old('check_out_date', request('check_out_date', $checkOutDate ?? '')))
@php($selectedDates = $selectedDates ?? collect())
@php($selectedRoom = $roomId ? $rooms->firstWhere('id', (int) $roomId) : null)

<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Create Reservation</h3>
            <div class="text-sm text-secondary-foreground">Prefilled from calendar selection</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.reservations.calendar-by-room', ['room_id' => $roomId]) }}">Back to Calendar</a>
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
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-xs font-semibold text-secondary-foreground">Check-in</div>
                            <div class="text-sm text-foreground mt-1">{{ $checkInDate ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-secondary-foreground">Check-out</div>
                            <div class="text-sm text-foreground mt-1">{{ $checkOutDate ?: '-' }}</div>
                        </div>
                    </div>

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
                    <input type="hidden" name="check_in_date" value="{{ $checkInDate }}" />
                    <input type="hidden" name="check_out_date" value="{{ $checkOutDate }}" />

                    <div class="lg:col-span-2">
                        <div class="text-xs font-semibold text-secondary-foreground">Guest</div>
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
                        <button type="submit" class="kt-btn kt-btn-primary">Confirm Reservation</button>
                        <a class="kt-btn" href="{{ route('admin.reservations.calendar-by-room', ['room_id' => $roomId]) }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
