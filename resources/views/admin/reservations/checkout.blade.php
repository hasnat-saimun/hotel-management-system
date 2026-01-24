@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Check-out</h3>
        <div class="text-sm text-secondary-foreground">Finalize bills, payments and departure processing</div>
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
                    <div class="kt-card p-3">
                        <p class="text-sm text-secondary-foreground">Invoice Summary</p>
                        <p class="mt-2">Subtotal: --</p>
                        <p>Payments: --</p>
                        <p class="mt-3"><strong>Balance Due: --</strong></p>
                        <div class="mt-3 flex gap-2">
                            <button class="kt-btn kt-btn-primary">Process Check-out</button>
                            <a class="kt-btn" href="{{ route('admin.reservations.show', $reservation->id) }}">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div>No reservation found.</div>
        @endif
    </div>
</div>
@endsection
