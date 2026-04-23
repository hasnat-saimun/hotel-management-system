@extends('admin.layouts.app')

@section('content')
    @php
        $rackDate = $rackDate ?? \Carbon\Carbon::today();
        $generatedAt = $generatedAt ?? now();
        $counts = $counts ?? [];
        $floors = $floors ?? [];

        $totalRooms = array_sum($counts);
    @endphp

    <div class="kt-card">
        <div class="kt-card-header flex items-center justify-between">
            <div>
                <h3 class="kt-card-title">Room Rack</h3>
                <div class="text-sm text-secondary-foreground">Front Desk • Live room control dashboard</div>
            </div>

            <div class="text-sm text-secondary-foreground text-right">
                <div>Date: <span class="text-foreground font-medium">{{ $rackDate->format('M d, Y') }}</span></div>
                <div>Updated: {{ $generatedAt->format('h:i A') }}</div>
            </div>
        </div>

        <div class="kt-card-content p-4">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="kt-badge kt-badge-outline kt-badge-info">Total: {{ $totalRooms }}</span>
                <span class="kt-badge kt-badge-success">Available: {{ (int) ($counts['available'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-warning">Occupied: {{ (int) ($counts['occupied'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-outline kt-badge-info">Reserved: {{ (int) ($counts['reserved'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-destructive">Dirty: {{ (int) ($counts['dirty'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-outline kt-badge-warning">Housekeeping: {{ (int) ($counts['housekeeping'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-outline kt-badge-warning">Maintenance: {{ (int) ($counts['maintenance'] ?? 0) }}</span>
                <span class="kt-badge kt-badge-outline kt-badge-destructive">Out of Service: {{ (int) ($counts['out_of_service'] ?? 0) }}</span>
            </div>

            @if(empty($floors))
                <div class="p-6 text-center text-secondary-foreground">No rooms found.</div>
            @else
                <div class="grid gap-6">
                    @foreach($floors as $floor)
                        @php
                            $floorLabel = $floor['floor_label'] ?? 'Floor';
                            $rooms = $floor['rooms'] ?? [];
                        @endphp

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-semibold">{{ $floorLabel }}</div>
                                <div class="text-xs text-secondary-foreground">Rooms: {{ is_array($rooms) ? count($rooms) : 0 }}</div>
                            </div>

                            <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-7">
                                @foreach($rooms as $room)
                                    @php
                                        $roomNumber = $room['room_number'] ?? '-';
                                        $roomType = $room['room_type'] ?? '-';
                                        $statusLabel = $room['rack_status_label'] ?? '-';
                                        $badgeClass = $room['rack_badge_class'] ?? 'kt-badge-outline kt-badge-info';

                                        $guestName = $room['guest_name'] ?? null;
                                        $reservationCode = $room['reservation_code'] ?? null;
                                        $checkOutDate = $room['check_out_date'] ?? null;

                                        $isVip = (bool) ($room['is_vip'] ?? false);
                                        $isOverstay = (bool) ($room['is_overstay'] ?? false);

                                        $hkStatus = $room['housekeeping_status'] ?? null;
                                        $hkPriority = $room['housekeeping_priority'] ?? null;

                                        $datesLine = null;
                                        if ($checkOutDate) {
                                            try {
                                                $datesLine = 'Check-out: ' . \Carbon\Carbon::parse($checkOutDate)->format('M d');
                                            } catch (\Throwable $e) {
                                                $datesLine = null;
                                            }
                                        }
                                    @endphp

                                    <div class="rounded-md border border-input p-3 hover:bg-muted/10">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <div class="text-base font-semibold text-mono truncate">{{ $roomNumber }}</div>
                                                <div class="text-xs text-secondary-foreground truncate">{{ $roomType }}</div>
                                            </div>
                                            <span class="kt-badge kt-badge-sm {{ $badgeClass }}">{{ $statusLabel }}</span>
                                        </div>

                                        <div class="mt-2 min-h-[1.25rem]">
                                            @if($guestName)
                                                <div class="text-sm font-medium truncate">{{ $guestName }}</div>
                                            @else
                                                <div class="text-xs text-secondary-foreground">—</div>
                                            @endif
                                        </div>

                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @if($isVip)
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                            @endif
                                            @if($isOverstay)
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">Overstay</span>
                                            @endif
                                        </div>

                                        <div class="mt-2 grid gap-1 text-xs text-secondary-foreground">
                                            @if($reservationCode)
                                                <div class="truncate">Res: {{ $reservationCode }}</div>
                                            @endif
                                            @if($datesLine)
                                                <div class="truncate">{{ $datesLine }}</div>
                                            @endif
                                            @if($hkStatus)
                                                <div class="truncate">HK: {{ str_replace('_', ' ', $hkStatus) }}{{ $hkPriority ? ' • ' . $hkPriority : '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Lightweight auto-refresh to behave like a live control dashboard.
            // Keeps UX minimal while reflecting live DB changes.
            document.addEventListener('DOMContentLoaded', function () {
                window.setTimeout(function () {
                    window.location.reload();
                }, 60000);
            });
        </script>
    @endpush
@endsection
