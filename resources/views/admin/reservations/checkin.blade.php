@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Check-in</h3>
        <div class="text-sm text-secondary-foreground">ID verification and arrival processing</div>
    </div>
    <div class="kt-card-content p-4">
        @if($reservation)
            <div class="grid lg:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-secondary-foreground">Guest</p>
                    <p class="font-medium">{{ $reservation->guest_name ?? '-' }}</p>
                    <p class="text-sm text-secondary-foreground mt-2">Room</p>
                    <p class="font-medium">{{ $reservation->room_number ?? '-' }}</p>
                </div>
                <div>
                    <form method="post" action="#" class="grid gap-3">
                        <div>
                            <label class="text-sm text-secondary-foreground">ID Document Number</label>
                            <input class="kt-input w-full" name="id_number" />
                        </div>
                        <div>
                            <label class="text-sm text-secondary-foreground">Notes</label>
                            <textarea class="kt-input w-full" name="notes"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button class="kt-btn kt-btn-primary">Confirm Check-in</button>
                            <a class="kt-btn" href="{{ route('admin.reservations.show', $reservation->id) }}">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div>No reservation found.</div>
        @endif
    </div>
</div>
@endsection
