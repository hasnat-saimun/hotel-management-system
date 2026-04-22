@extends('admin.layouts.app')

@section('content')
    @php
        $filters = $filters ?? [];
        $q = (string) ($filters['q'] ?? request('q', ''));
        $roomTypeId = $filters['room_type_id'] ?? request('room_type_id');
        $floorId = $filters['floor_id'] ?? request('floor_id');
        $status = (string) ($filters['status'] ?? request('status', 'all'));
    @endphp

    <div class="kt-card">
        <div class="kt-card-header flex items-center justify-between">
            <div>
                <h3 class="kt-card-title">In-House Guests</h3>
                <div class="text-sm text-secondary-foreground">Front Desk</div>
            </div>
            <div class="text-sm text-secondary-foreground">Active stays: {{ $stays->total() ?? 0 }}</div>
        </div>

        <div class="kt-card-content p-4">
            <div class="sticky top-0 z-10 -mx-4 px-4 py-3 bg-background/95 backdrop-blur border-b border-input">
                <form id="inhouse-filter-form" method="GET" action="{{ route('admin.front-desk.in-house') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-5">
                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground">Search</label>
                        <input id="inhouse-search" type="text" name="q" class="kt-input w-full" placeholder="Search guest name / phone" value="{{ $q }}" autocomplete="off" />
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Room type</label>
                        <select id="inhouse-room-type" name="room_type_id" class="kt-input w-full">
                            <option value="">All</option>
                            @foreach(($roomTypes ?? []) as $type)
                                <option value="{{ $type->id }}" {{ (string) $roomTypeId === (string) $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-secondary-foreground">Floor</label>
                        <select id="inhouse-floor" name="floor_id" class="kt-input w-full">
                            <option value="">All</option>
                            @foreach(($floors ?? []) as $floor)
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

                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="text-sm text-secondary-foreground">Status</label>
                            <select id="inhouse-status" name="status" class="kt-input w-full">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All (In-House)</option>
                                <option value="overstay" {{ $status === 'overstay' ? 'selected' : '' }}>Overstay</option>
                                <option value="vip" {{ $status === 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>

                        @if($q || $roomTypeId || $floorId || ($status && $status !== 'all'))
                            <a class="kt-btn" href="{{ route('admin.front-desk.in-house') }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.getElementById('inhouse-filter-form');
                    const input = document.getElementById('inhouse-search');
                    const roomType = document.getElementById('inhouse-room-type');
                    const floor = document.getElementById('inhouse-floor');
                    const status = document.getElementById('inhouse-status');

                    if (!form) return;

                    let debounceId;
                    if (input) {
                        input.addEventListener('input', function () {
                            window.clearTimeout(debounceId);
                            debounceId = window.setTimeout(function () {
                                form.submit();
                            }, 350);
                        });
                    }

                    [roomType, floor, status].forEach(function (el) {
                        if (!el) return;
                        el.addEventListener('change', function () {
                            form.submit();
                        });
                    });
                });
            </script>
        @endpush

            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-secondary-foreground">Showing {{ $stays->count() }} of {{ $stays->total() }}</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="text-sm text-secondary-foreground">
                            <th class="p-2">Guest</th>
                            <th class="p-2">Room</th>
                            <th class="p-2">Room type</th>
                            <th class="p-2">Check-in</th>
                            <th class="p-2">Nights</th>
                            <th class="p-2">Expected check-out</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stays as $stay)
                            @php
                                $guest = $stay->reservation?->guest;
                                $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
                                $guestName = $guestName !== '' ? $guestName : '-';
                                $guestPhone = $guest?->phone ?? null;

                                $roomNumber = $stay->room?->room_number ?? '-';
                                $roomTypeName = $stay->room?->roomType?->name ?? '-';

                                $checkIn = $stay->check_in_time ? \Carbon\Carbon::parse($stay->check_in_time) : null;
                                $expectedOut = $stay->expected_check_out_date ? \Carbon\Carbon::parse($stay->expected_check_out_date) : null;

                                $isOverstay = (bool) ($stay->is_overstay ?? false);
                                $isVip = (bool) ($stay->is_vip ?? false);
                                $nights = (int) ($stay->nights_stayed ?? 0);
                            @endphp

                            <tr class="border-t hover:bg-muted/10 {{ $isOverstay ? 'bg-destructive/5' : '' }}">
                                <td class="p-2">
                                    <div class="font-medium">{{ $guestName }}</div>
                                    <div class="flex items-center gap-2">
                                        @if($guestPhone)
                                            <span class="text-xs text-secondary-foreground">{{ $guestPhone }}</span>
                                        @endif
                                        @if($isVip)
                                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-2">{{ $roomNumber }}</td>
                                <td class="p-2">{{ $roomTypeName }}</td>
                                <td class="p-2">{{ $checkIn ? $checkIn->format('M d, Y h:i A') : '-' }}</td>
                                <td class="p-2">{{ $nights }}</td>
                                <td class="p-2">{{ $expectedOut ? $expectedOut->format('M d, Y') : '-' }}</td>
                                <td class="p-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">In-House</span>
                                        @if($isOverstay)
                                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">Overstay</span>
                                        @endif
                                        @if($isVip)
                                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-secondary-foreground">No in-house guests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $stays->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@endsection
