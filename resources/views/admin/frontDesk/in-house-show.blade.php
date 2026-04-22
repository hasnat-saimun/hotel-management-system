@extends('admin.layouts.app')

@section('content')
    @php
        $reservation = $stay->reservation;
        $guest = $reservation?->guest;
        $room = $stay->room;

        $guestName = trim(($guest?->first_name ?? '') . ' ' . ($guest?->last_name ?? ''));
        $guestName = $guestName !== '' ? $guestName : '-';

        $floorName = $room?->floor?->name;
        $roomNumber = $room?->room_number ?? '-';
        $roomTypeName = $room?->roomType?->name ?? '-';

        $checkInDisplay = $reservation?->check_in_date ? \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') : '-';
        $checkOutDisplay = $reservation?->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') : '-';

        $checkInTimeDisplay = $stay->check_in_time ? \Carbon\Carbon::parse($stay->check_in_time)->format('M d, Y h:i A') : '-';
        $status = strtolower((string) ($stay->status ?? ''));
    @endphp

    <div class="grid gap-5">
        <div class="kt-card">
            <div class="kt-card-header flex items-center justify-between">
                <div>
                    <h3 class="kt-card-title">In-House Guest Details</h3>
                    <div class="text-sm text-secondary-foreground">Stay, guest, room and actions</div>
                </div>
                <div class="flex gap-2">
                    <a class="kt-btn" href="{{ route('admin.front-desk.in-house') }}">Back to In-House</a>
                </div>
            </div>

            <div class="kt-card-content p-4">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Guest</div>
                        <div class="mt-1 text-base font-semibold text-mono break-words">{{ $guestName }}</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Phone</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->phone ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Email</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $guest?->email ?? '-' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">VIP</dt>
                                    <dd>
                                        @if($isVip)
                                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                        @else
                                            <span class="text-sm text-secondary-foreground">No</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Floor / Room</div>
                        <div class="mt-1 text-base font-semibold text-mono break-words">
                            @if($floorName)
                                <span class="text-sm font-normal text-secondary-foreground">{{ $floorName }}</span>
                            @endif
                            <span class="text-sm font-normal text-secondary-foreground"> • </span>
                            {{ $roomNumber }}
                        </div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-xs text-secondary-foreground">Room Type</dt>
                                    <dd class="col-span-2 text-sm font-medium text-mono break-words">{{ $roomTypeName }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Room Status</dt>
                                    <dd class="text-sm font-medium text-mono">{{ ucfirst((string) ($room?->status ?? '-')) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Stay Dates</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Check-in</dt>
                                    <dd class="text-sm font-medium text-mono">{{ $checkInDisplay }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Expected Check-out</dt>
                                    <dd class="text-sm font-medium text-mono">{{ $checkOutDisplay }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Nights Stayed</dt>
                                    <dd class="text-sm font-medium text-mono">{{ (int) $nightsStayed }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Overstay</dt>
                                    <dd>
                                        @if($isOverstay)
                                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">Overstay</span>
                                        @else
                                            <span class="text-sm text-secondary-foreground">No</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="kt-card p-4 h-full flex flex-col">
                        <div class="text-xs font-semibold text-secondary-foreground">Stay Status</div>
                        <div class="mt-1 text-base font-semibold text-mono break-words">{{ $status !== '' ? ucfirst($status) : '-' }}</div>

                        <div class="mt-3 pt-3 border-t border-border">
                            <dl class="grid gap-2">
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-xs text-secondary-foreground">Checked in at</dt>
                                    <dd class="text-sm font-medium text-mono">{{ $checkInTimeDisplay }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <div id="check-out" class="kt-card p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold">Check-Out</div>
                                <div class="text-xs text-secondary-foreground">Marks stay as checked out and sets room to dirty</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.front-desk.in-house.check-out', $stay) }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="confirm" value="1" />
                            <button type="submit" class="kt-btn kt-btn-destructive" onclick="return confirm('Check out this guest?');" {{ ($stay->status ?? null) !== 'in_house' ? 'disabled' : '' }}>
                                Check-Out
                            </button>
                        </form>
                    </div>

                    <div id="extend" class="kt-card p-4">
                        <div>
                            <div class="text-sm font-semibold">Extend Stay</div>
                            <div class="text-xs text-secondary-foreground">Updates reservation check-out date</div>
                        </div>

                        <form method="POST" action="{{ route('admin.front-desk.in-house.extend', $stay) }}" class="mt-3 grid gap-3 sm:grid-cols-3">
                            @csrf
                            <div class="sm:col-span-2">
                                <label class="text-sm text-secondary-foreground">New check-out date</label>
                                <input type="date" name="check_out_date" class="kt-input w-full" value="{{ old('check_out_date', $reservation?->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->format('Y-m-d') : '') }}" required />
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="kt-btn w-full" {{ ($stay->status ?? null) !== 'in_house' ? 'disabled' : '' }}>Save</button>
                            </div>
                        </form>
                    </div>

                    <div id="change-room" class="kt-card p-4">
                        <div>
                            <div class="text-sm font-semibold">Change Room</div>
                            <div class="text-xs text-secondary-foreground">Assigns a new room after availability validation</div>
                        </div>

                        <form method="POST" action="{{ route('admin.front-desk.in-house.change-room', $stay) }}" class="mt-3 grid gap-3 sm:grid-cols-3">
                            @csrf
                            <input type="hidden" name="confirm" value="1" />
                            <div class="sm:col-span-2">
                                <label class="text-sm text-secondary-foreground">New room</label>
                                <select name="new_room_id" class="kt-input w-full" required>
                                    <option value="">Select an available room</option>
                                    @foreach($availableRooms as $availableRoom)
                                        @php
                                            $labelFloor = trim((string) ($availableRoom->floor?->name ?? ''));
                                            $labelType = trim((string) ($availableRoom->roomType?->name ?? ''));
                                            $parts = [
                                                $availableRoom->room_number,
                                                $labelType !== '' ? $labelType : null,
                                                $labelFloor !== '' ? $labelFloor : null,
                                            ];
                                            $parts = array_values(array_filter($parts, fn ($v) => $v !== null && $v !== ''));
                                            $label = implode(' • ', $parts);
                                        @endphp
                                        <option value="{{ $availableRoom->id }}" {{ (string) old('new_room_id') === (string) $availableRoom->id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="kt-btn w-full" onclick="return confirm('Change room for this stay?');" {{ ($stay->status ?? null) !== 'in_house' ? 'disabled' : '' }}>Change</button>
                            </div>
                        </form>
                    </div>

                    <div id="add-note" class="kt-card p-4">
                        <div>
                            <div class="text-sm font-semibold">Add Note</div>
                            <div class="text-xs text-secondary-foreground">Stored in the guest profile notes</div>
                        </div>

                        <form method="POST" action="{{ route('admin.front-desk.in-house.note', $stay) }}" class="mt-3 grid gap-3">
                            @csrf
                            <div>
                                <label class="text-sm text-secondary-foreground">Note</label>
                                <textarea name="note" class="kt-input w-full" rows="4" placeholder="Write a note about this guest..." required>{{ old('note') }}</textarea>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-xs text-secondary-foreground">
                                    Current notes: {{ $guest?->notes ? 'Yes' : 'No' }}
                                </div>
                                <button type="submit" class="kt-btn">Add Note</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($guest?->notes)
                    <div class="kt-card mt-5">
                        <div class="kt-card-header">
                            <h3 class="kt-card-title">Guest Notes</h3>
                        </div>
                        <div class="kt-card-content p-4">
                            <div class="text-sm whitespace-pre-line text-mono">{{ $guest->notes }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
