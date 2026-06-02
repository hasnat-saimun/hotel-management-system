@props([
    'rooms' => [],
    'selectedRoomId' => null,
    'loading' => false,
])

@php
    $demoRooms = [
        [
            'id' => 1,
            'room_number' => '101',
            'room_type' => 'Standard Queen',
            'capacity' => '2 adults',
            'status' => 'Available',
            'price' => '120.00',
            'tone' => 'success',
        ],
        [
            'id' => 2,
            'room_number' => '202',
            'room_type' => 'Deluxe Twin',
            'capacity' => '2 adults • 1 child',
            'status' => 'Occupied',
            'price' => '165.00',
            'tone' => 'danger',
        ],
        [
            'id' => 3,
            'room_number' => '305',
            'room_type' => 'Family Suite',
            'capacity' => '4 adults',
            'status' => 'Dirty',
            'price' => '220.00',
            'tone' => 'warning',
        ],
        [
            'id' => 4,
            'room_number' => '410',
            'room_type' => 'Executive King',
            'capacity' => '2 adults',
            'status' => 'Out Of Order',
            'price' => '275.00',
            'tone' => 'muted',
        ],
    ];

    $roomList = is_countable($rooms) && count($rooms) > 0 ? $rooms : $demoRooms;

    $filters = [
        ['label' => 'Room Type', 'placeholder' => 'All room types'],
        ['label' => 'Floor', 'placeholder' => 'All floors'],
        ['label' => 'Capacity', 'placeholder' => 'Any capacity'],
    ];

    $statusStyles = [
        'Available' => 'kt-badge-outline kt-badge-success',
        'Occupied' => 'kt-badge-outline kt-badge-destructive',
        'Dirty' => 'kt-badge-outline kt-badge-warning',
        'Out Of Order' => 'kt-badge-outline',
    ];

    $toneStyles = [
        'success' => 'border-success/20 bg-success/5',
        'danger' => 'border-danger/20 bg-danger/5',
        'warning' => 'border-warning/20 bg-warning/5',
        'muted' => 'border-border bg-muted/20',
    ];
@endphp

<section class="kt-card">
    <div class="kt-card-header flex items-center justify-between gap-4">
        <div>
            <h3 class="kt-card-title">Room Selection</h3>
            <div class="text-sm text-secondary-foreground">Filter rooms and select an available option for the walk-in stay.</div>
        </div>

        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">Section 3</span>
    </div>

    <div class="kt-card-content p-4 sm:p-5">
        <div class="grid gap-5 xl:grid-cols-12">
            <div class="xl:col-span-4">
                <div class="kt-card p-4 h-full">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Filters</div>
                            <div class="mt-1 text-sm text-secondary-foreground">Narrow room options before selection.</div>
                        </div>
                        <span class="kt-badge kt-badge-sm kt-badge-outline">Refine</span>
                    </div>

                    <div class="mt-4 grid gap-4">
                        @foreach($filters as $filter)
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-secondary-foreground">{{ $filter['label'] }}</label>
                                <select class="kt-input w-full">
                                    <option>{{ $filter['placeholder'] }}</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" class="kt-btn kt-btn-outline">Apply Filters</button>
                        <button type="button" class="kt-btn kt-btn-outline">Reset</button>
                    </div>

                    <div class="mt-5 rounded-2xl border border-dashed border-border bg-muted/20 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">UI Architecture</div>
                        <div class="mt-2 text-sm text-secondary-foreground">
                            Use a 4/8 split on desktop, collapse filters above results on mobile, and keep room cards tappable with clear status badges.
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-8">
                @if($loading)
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @for($i = 0; $i < 6; $i++)
                            <div class="rounded-2xl border border-border bg-muted/20 p-4 animate-pulse">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-2">
                                        <div class="h-4 w-16 rounded bg-muted"></div>
                                        <div class="h-3 w-24 rounded bg-muted"></div>
                                    </div>
                                    <div class="h-6 w-20 rounded-full bg-muted"></div>
                                </div>
                                <div class="mt-4 space-y-3">
                                    <div class="h-3 w-full rounded bg-muted"></div>
                                    <div class="h-3 w-5/6 rounded bg-muted"></div>
                                    <div class="h-3 w-2/3 rounded bg-muted"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @elseif(empty($roomList))
                    <div class="kt-card p-4 h-full">
                        <div class="flex min-h-[300px] flex-col items-center justify-center rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-background shadow-sm">
                                <i class="ki-filled ki-home text-xl text-secondary-foreground"></i>
                            </div>
                            <div class="mt-4 space-y-2">
                                <h4 class="text-lg font-semibold text-foreground">No rooms available</h4>
                                <p class="max-w-md text-sm text-secondary-foreground">
                                    Adjust filters or refresh room availability to continue the selection workflow.
                                </p>
                            </div>
                            <div class="mt-5 flex flex-wrap justify-center gap-2">
                                <button type="button" class="kt-btn kt-btn-primary">Refresh Availability</button>
                                <button type="button" class="kt-btn kt-btn-outline">Clear Filters</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($roomList as $room)
                            @php
                                $roomId = $room['id'] ?? null;
                                $isSelected = $selectedRoomId !== null && (string) $selectedRoomId === (string) $roomId;
                                $status = $room['status'] ?? 'Available';
                                $statusClass = $statusStyles[$status] ?? 'kt-badge-outline';
                                $toneClass = $toneStyles[$room['tone'] ?? 'muted'] ?? $toneStyles['muted'];
                            @endphp

                            <button
                                type="button"
                                class="group relative overflow-hidden rounded-2xl border p-4 text-left transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/50 {{ $toneClass }} {{ $isSelected ? 'ring-2 ring-primary/60 shadow-sm' : '' }}"
                            >
                                <span class="absolute left-0 top-0 h-full w-1 {{ $room['tone'] === 'danger' ? 'bg-danger' : ($room['tone'] === 'warning' ? 'bg-warning' : ($room['tone'] === 'success' ? 'bg-success' : 'bg-border')) }}"></span>

                                <div class="flex items-start justify-between gap-3 pl-1">
                                    <div class="min-w-0">
                                        <div class="text-lg font-semibold text-foreground">{{ $room['room_number'] ?? '-' }}</div>
                                        <div class="text-xs text-secondary-foreground">{{ $room['room_type'] ?? '-' }}</div>
                                    </div>

                                    <span class="kt-badge kt-badge-sm {{ $statusClass }}">{{ $status }}</span>
                                </div>

                                <div class="mt-4 grid gap-3 text-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-xs text-secondary-foreground">Capacity</span>
                                        <span class="font-medium text-foreground">{{ $room['capacity'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-xs text-secondary-foreground">Price</span>
                                        <span class="font-medium text-foreground">{{ $room['price'] ?? '0.00' }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3 border-t border-border pt-3">
                                    <span class="text-xs text-secondary-foreground">Tap to select</span>
                                    @if($isSelected)
                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Selected</span>
                                    @else
                                        <span class="kt-badge kt-badge-sm kt-badge-outline">Selectable</span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>