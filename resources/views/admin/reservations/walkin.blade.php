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

            
            
             <div class=" lg:block">
                <label class="text-sm text-secondary-foreground">Select (s)</label>
                    <input type="checkbox" id="select-all" />
                    <label for="select-all" class="text-sm">Select all</label>

                <div class="room-multiselect relative">
                    <div class="border rounded p-2 flex flex-wrap items-center gap-2" id="rooms-multiselect-box">
                        <div id="selected-rooms" class="flex flex-wrap gap-2"></div>
                        <input id="rooms-search-input" type="text" class="kt-input flex-1 min-w-[150px]" placeholder="Type to search rooms...">
                    </div>
                    <ul id="rooms-options" class="absolute z-50 bg-white border rounded mt-1 w-full max-h-48 overflow-auto hidden"></ul>
                </div>

                <div id="rooms-hidden-inputs">
                    
                </div>

                <input type="hidden" name="room_number" value="" />
            </div>
            
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="kt-btn kt-btn-primary">Search</button>
            </div>
        </form>

        <div class="mt-6">
            @php($reservationRooms = $reservationRooms ?? collect())

            @if($reservationRooms->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto kt-table">
                        <thead>
                            <tr class="text-sm text-secondary-foreground bg-muted/20">
                                <th class="px-4 py-3 text-left">Sl</th>
                                <th class="px-4 py-3 text-left">Reservation</th>
                                <th class="px-4 py-3 text-left">Guest</th>
                                <th class="px-4 py-3 text-left">Room</th>
                                <th class="px-4 py-3 text-left">Room Type</th>
                                <th class="px-4 py-3 text-left">Check-in</th>
                                <th class="px-4 py-3 text-left">Check-out</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Rate Plan</th>
                                <th class="px-4 py-3 text-left">Nightly</th>
                                <th class="px-4 py-3 text-left">Discount</th>
                                <th class="px-4 py-3 text-left">Tax</th>
                                <th class="px-4 py-3 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($reservationRooms as $rr)
                                <tr class="border-b border-input hover:bg-accent/10">
                                    <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $rr->reservation?->reservation_code ?? ('#' . ($rr->reservation_id ?? '-')) }}
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $rr->reservation?->guest?->first_name ?? '-' }} {{ $rr->reservation?->guest?->last_name ?? '' }}
                                        <div class="text-xs text-secondary-foreground">{{ $rr->reservation?->guest?->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-top">{{ $rr->room?->room_number ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">{{ $rr->roomType?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $rr->reservation?->check_in_date ? \Carbon\Carbon::parse($rr->reservation->check_in_date)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        {{ $rr->reservation?->check_out_date ? \Carbon\Carbon::parse($rr->reservation->check_out_date)->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top">{{ ucfirst($rr->status ?? '-') }}</td>
                                    <td class="px-4 py-3 align-top">{{ $rr->rate_plan_named ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">{{ number_format((float)($rr->nightly_rate ?? 0), 2) }}</td>
                                    <td class="px-4 py-3 align-top">{{ number_format((float)($rr->discount_amount ?? 0), 2) }}</td>
                                    <td class="px-4 py-3 align-top">{{ number_format((float)($rr->tax_amount ?? 0), 2) }}</td>
                                    <td class="px-4 py-3 align-top">{{ number_format((float)($rr->total_amount ?? 0), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
// JavaScript for room multiselect with search
</script>
@endpush
