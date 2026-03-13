@extends('admin.layouts.app')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-col">
        <h1 class="text-xl font-medium">Reservations</h1>
        <div class="text-sm text-secondary-foreground">List, filter and manage bookings</div>
    </div>
        <div class="flex items-center gap-2">
            <a class="kt-btn" href="{{ route('admin.reservations.calendar') }}">Calendar</a>
            <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.walkin') }}">New Walk-in</a>
        </div>
</div>

<div class="grid gap-5">
    <div class="kt-card">
        <div class="kt-card-header flex items-center justify-between">
            <h3 class="kt-card-title">Reservations</h3>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('admin.reservations.index') }}" id="reservations-search-form" class="flex items-center gap-2">
                    <input name="q" id="reservations-search-input" type="text" class="kt-input" placeholder="Search guest or room" value="{{ request('q') }}" />
                    <select class="kt-select" name="status">
                    <option value="">All statuses</option>
                    <option>Pending</option>
                    <option>Confirmed</option>
                    <option>Checked-in</option>
                    <option>Checked-out</option>
                    <option>Cancelled</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="kt-card-content p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto kt-table">
                    <thead>
                        <tr class="text-sm text-secondary-foreground bg-muted/20">
                            <th class="px-4 py-3 text-left">Sl</th>
                            <th class="px-4 py-3 text-left">Guest</th>
                            <th class="px-4 py-3 text-left">Room</th>
                            <th class="px-4 py-3 text-left">Check-in</th>
                            <th class="px-4 py-3 text-left">Check-out</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($reservations as $r)
                        <tr class="border-b border-input hover:bg-accent/10">
                            <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 align-top">
                               
                                       {{ $r->guest?->first_name ?? '-' }} {{ $r->guest?->last_name ?? '' }}
                                        <div class="text-xs text-secondary-foreground">{{ $r->guest?->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                @foreach($r->rooms as $room)
                                    <div class="text-xs">{{ $room->room_number }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 align-top">{{ $r->check_in_date ? \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') : '-' }}</td>
                            <td class="px-4 py-3 align-top">{{ $r->check_out_date ? \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') : '-' }}</td>
                            
                            <td class="px-4 py-3 align-top">
                                @php
                                    $status = strtolower($r->status ?? 'pending');
                                @endphp
                                @if($status == 'confirmed')
                                <span class="kt-badge kt-badge-outline kt-badge-success">Confirmed</span>
                                @elseif($status == 'pending')
                                    <span class="kt-badge kt-badge-outline kt-badge-info">Pending</span>
                                @elseif($status == 'checked-in' || $status == 'checkedin' || $status == 'checked_in')
                                    <span class="kt-badge kt-badge-outline kt-badge-primary">Checked-in</span>
                                    @elseif($status == 'checked-out' || $status == 'checkedout' || $status == 'checked_out')
                                    <span class="kt-badge kt-badge-outline kt-badge-secondary">Checked-out</span>
                                @elseif($status == 'cancelled')
                                    <span class="kt-badge kt-badge-outline kt-badge-destructive">Cancelled</span>
                                
                                @elseif($status == 'no-show' || $status == 'noshow')
                                    <span class="kt-badge kt-badge-outline kt-badge-warning">No-show</span>
                                @elseif($status == 'booked' )
                                    <span class="kt-badge kt-badge-outline kt-badge-info">Booked</span>
                                @else
                                    <span class="kt-badge kt-badge-outline kt-badge-info">{{ ucfirst($r->reservation?->status ?? 'Pending') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center gap-2">
                                    <a class="kt-btn kt-btn-sm kt-btn-ghost" href="{{ route('admin.reservations.show', $r->id) }}">Details</a>
                                    @if(($status ?? '') === 'confirmed')
                                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.reservations.checkin', $r->id) }}">Check-in</a>
                                    @elseif(in_array(($status ?? ''), ['checked-in', 'checkedin', 'checked_in'], true))
                                        <a class="kt-btn kt-btn-destructive kt-btn-sm" onclick="return confirm('Are you sure you want to check out this reservation?')" href="{{ route('admin.reservations.checkout', $r->id) }}">Check-out</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-6 text-center text-secondary-foreground">No reservations found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
    (function(){
        var form = document.getElementById('reservations-search-form');
        var input = document.getElementById('reservations-search-input');
        if (!form || !input) return;
        var timeout = null;
        input.addEventListener('input', function(){
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });

        // submit when status select changes
        var selects = form.querySelectorAll('select');
        selects.forEach(function(s){ s.addEventListener('change', function(){ form.submit(); }); });
    })();
    </script>
@endpush
