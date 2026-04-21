@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-col">
            <h1 class="text-xl font-medium">Front Desk · Arrivals</h1>
            <div class="text-sm text-secondary-foreground">
                Today's arrivals ({{ $today->format('M d, Y') }})
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form id="arrivals-search-form" method="GET" action="{{ route('admin.front-desk.arrivals') }}" class="flex items-center gap-2">
                <input id="arrivals-search" type="text" name="q" class="kt-input" placeholder="Search guest / phone / room" value="{{ request('q') }}" autocomplete="off" />
            </form>
            @if(request('q'))
                <a class="kt-btn kt-btn-outline" href="{{ route('admin.front-desk.arrivals') }}">Clear</a>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('arrivals-search-form');
                const input = document.getElementById('arrivals-search');
                if (!form || !input) return;

                let debounceId;
                input.addEventListener('input', function () {
                    window.clearTimeout(debounceId);
                    debounceId = window.setTimeout(function () {
                        form.submit();
                    }, 350);
                });
            });
        </script>
    @endpush

    <div class="grid gap-5">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <h3 class="kt-card-title">Arrivals</h3>
                <div class="text-sm text-secondary-foreground">
                    Total: {{ $reservations->count() }}
                </div>
            </div>
            <div class="kt-card-content p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto kt-table">
                        <thead>
                            <tr class="text-sm text-secondary-foreground bg-muted/20">
                                <th class="px-4 py-3 text-left">Sl</th>
                                <th class="px-4 py-3 text-left">Guest</th>
                                <th class="px-4 py-3 text-left">Phone</th>
                                <th class="px-4 py-3 text-left">Room</th>
                                <th class="px-4 py-3 text-left">Check-in</th>
                                <th class="px-4 py-3 text-left">Nights</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($reservations as $r)
                                @php
                                    $status = strtolower($r->status ?? 'booked');
                                    $isCheckedIn = $r->reservationRooms && $r->reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');
                                    $nights = 0;
                                    if ($r->check_in_date && $r->check_out_date) {
                                        try {
                                            $nights = $r->check_in_date->diffInDays($r->check_out_date);
                                        } catch (Throwable $e) {
                                            $nights = 0;
                                        }
                                    }
                                @endphp
                                <tr class="border-b border-input hover:bg-accent/10">
                                    <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $r->guest?->first_name ?? '-' }} {{ $r->guest?->last_name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">{{ $r->guest?->phone ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        @php
                                            $roomNumbers = $r->reservationRooms
                                                ? $r->reservationRooms->pluck('room.room_number')->filter()->values()
                                                : collect();
                                        @endphp
                                        @if($roomNumbers->isEmpty())
                                            -
                                        @else
                                            {{ $roomNumbers->join(', ') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $r->check_in_date ? $r->check_in_date->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">{{ $nights }}</td>
                                    <td class="px-4 py-3 align-top">
                                        @if($isCheckedIn)
                                            <span class="kt-badge kt-badge-outline kt-badge-success">In-House</span>
                                        @elseif($status === 'confirmed')
                                            <span class="kt-badge kt-badge-outline kt-badge-success">Confirmed</span>
                                        @elseif($status === 'booked')
                                            <span class="kt-badge kt-badge-outline kt-badge-info">Booked</span>
                                        @else
                                            <span class="kt-badge kt-badge-outline kt-badge-info">{{ ucfirst($r->status ?? 'Booked') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex items-center gap-2">
                                            @if($isCheckedIn)
                                                <button class="kt-btn kt-btn-sm kt-btn-outline" type="button" disabled>Checked In</button>
                                            @elseif($roomNumbers->isEmpty())
                                                <button class="kt-btn kt-btn-sm kt-btn-outline" type="button" disabled>No Room</button>
                                            @else
                                                <a class="kt-btn kt-btn-sm kt-btn-primary" href="{{ route('admin.front-desk.arrivals.check-in', $r->id) }}">Check-In</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-6 text-center text-secondary-foreground" colspan="8">
                                        No arrivals for today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
