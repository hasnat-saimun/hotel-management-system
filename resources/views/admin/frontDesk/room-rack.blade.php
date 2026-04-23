@extends('admin.layouts.app')

@section('content')
    @php
        $rackDate = $rackDate ?? \Carbon\Carbon::today();
        $generatedAt = $generatedAt ?? now();
        $counts = $counts ?? [];
        $floors = $floors ?? [];

        $totalRooms = array_sum($counts);

        $statusLegend = [
            'available' => ['label' => 'Available', 'dot' => '#22c55e'],
            'occupied' => ['label' => 'Occupied', 'dot' => '#ef4444'],
            'reserved' => ['label' => 'Reserved', 'dot' => '#0ea5e9'],
            'dirty' => ['label' => 'Dirty', 'dot' => '#f59e0b'],
            'out_of_order' => ['label' => 'Out of Order', 'dot' => '#94a3b8'],
        ];
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
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="kt-badge kt-badge-outline kt-badge-info">Total: {{ $totalRooms }}</span>
                <span class="kt-badge border" style="background-color:#dcfce7;color:#166534;border-color:#bbf7d0;">Available: {{ (int) ($counts['available'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#fee2e2;color:#b91c1c;border-color:#fecaca;">Occupied: {{ (int) ($counts['occupied'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#e0f2fe;color:#0369a1;border-color:#bae6fd;">Reserved: {{ (int) ($counts['reserved'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#fef3c7;color:#92400e;border-color:#fde68a;">Dirty: {{ (int) ($counts['dirty'] ?? 0) }}</span>
                <span class="kt-badge border" style="background-color:#f1f5f9;color:#334155;border-color:#cbd5e1;">Out of Order: {{ (int) ($counts['out_of_order'] ?? 0) }}</span>
            </div>

            <div class="mb-5 rounded-lg border border-input/70 bg-muted/20 px-3 py-2">
                <div class="text-xs uppercase tracking-wide text-secondary-foreground mb-2">Status Legend</div>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach($statusLegend as $legend)
                        <div class="inline-flex items-center gap-2 text-xs text-foreground/90">
                            <span class="inline-block h-2.5 w-2.5 rounded-full" style="background-color: {{ $legend['dot'] }};"></span>
                            <span>{{ $legend['label'] }}</span>
                        </div>
                    @endforeach
                </div>
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
                                        $statusKey = $room['rack_status'] ?? 'available';
                                        $roomNumber = $room['room_number'] ?? '-';
                                        $roomType = $room['room_type'] ?? '-';
                                        $statusLabel = $room['rack_status_label'] ?? '-';

                                        $guestName = $room['guest_name'] ?? null;
                                        $reservationCode = $room['reservation_code'] ?? null;
                                        $checkInDate = $room['check_in_date'] ?? null;
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

                                        $stayDuration = null;
                                        if ($statusKey === 'occupied' && $checkInDate) {
                                            try {
                                                $nights = \Carbon\Carbon::parse($checkInDate)->startOfDay()->diffInDays(now()->startOfDay());
                                                $stayDuration = $nights . ' night' . ($nights === 1 ? '' : 's');
                                            } catch (\Throwable $e) {
                                                $stayDuration = null;
                                            }
                                        }

                                        $statusTone = [
                                            'available' => [
                                                'badge_style' => 'background-color:#dcfce7;color:#166534;border-color:#bbf7d0;',
                                                'card_style' => 'border-color:#bbf7d0;background-color:#f0fdf4;',
                                                'bar_style' => 'background-color:#22c55e;',
                                            ],
                                            'occupied' => [
                                                'badge_style' => 'background-color:#fee2e2;color:#b91c1c;border-color:#fecaca;',
                                                'card_style' => 'border-color:#fecaca;background-color:#fef2f2;',
                                                'bar_style' => 'background-color:#ef4444;',
                                            ],
                                            'reserved' => [
                                                'badge_style' => 'background-color:#e0f2fe;color:#0369a1;border-color:#bae6fd;',
                                                'card_style' => 'border-color:#bae6fd;background-color:#f0f9ff;',
                                                'bar_style' => 'background-color:#0ea5e9;',
                                            ],
                                            'dirty' => [
                                                'badge_style' => 'background-color:#fef3c7;color:#92400e;border-color:#fde68a;',
                                                'card_style' => 'border-color:#fde68a;background-color:#fffbeb;',
                                                'bar_style' => 'background-color:#f59e0b;',
                                            ],
                                            'out_of_order' => [
                                                'badge_style' => 'background-color:#f1f5f9;color:#334155;border-color:#cbd5e1;',
                                                'card_style' => 'border-color:#cbd5e1;background-color:#f8fafc;',
                                                'bar_style' => 'background-color:#94a3b8;',
                                            ],
                                        ][$statusKey] ?? [
                                            'badge_style' => 'background-color:#f1f5f9;color:#334155;border-color:#cbd5e1;',
                                            'card_style' => 'border-color:#e2e8f0;background-color:#ffffff;',
                                            'bar_style' => 'background-color:#94a3b8;',
                                        ];
                                    @endphp

                                    <div class="relative overflow-hidden rounded-xl border p-3 transition-all duration-200 hover:shadow-sm" style="{{ $statusTone['card_style'] }}">
                                        <span class="absolute left-0 top-0 h-full w-1" style="{{ $statusTone['bar_style'] }}"></span>
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0 pl-1">
                                                <div class="text-base font-semibold text-mono truncate">{{ $roomNumber }}</div>
                                                <div class="text-xs text-secondary-foreground truncate">{{ $roomType }}</div>
                                            </div>
                                            <span class="kt-badge kt-badge-sm border" style="{{ $statusTone['badge_style'] }}">{{ $statusLabel }}</span>
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
                                            @if($stayDuration)
                                                <div class="truncate">Stay: {{ $stayDuration }}</div>
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
