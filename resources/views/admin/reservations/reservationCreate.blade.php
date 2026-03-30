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
        <div class="rounded border border-input bg-background p-4">
            <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
                <div class="space-y-2">
                    <div class="text-xs font-semibold text-secondary-foreground">Room</div>
                    <div class="text-sm text-foreground">
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

                <div class="space-y-2">
                    <div class="text-xs font-semibold text-secondary-foreground">Check-in</div>
                    <div class="text-sm text-foreground">
                        {{ $checkInDate ?: '-' }}
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="text-xs font-semibold text-secondary-foreground">Check-out</div>
                    <div class="text-sm text-foreground">
                        {{ $checkOutDate ?: '-' }}
                    </div>
                </div>
            </div>

            <div class="mt-4">
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
</div>
@endsection
