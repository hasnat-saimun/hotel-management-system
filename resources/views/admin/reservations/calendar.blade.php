@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Booking Calendar</h3>
            <div class="text-sm text-secondary-foreground">Room availability and bookings</div>
        </div>
        <div class="flex gap-2">
            <a class="kt-btn" href="{{ route('admin.reservations.index') }}">List</a>
            <a class="kt-btn kt-btn-primary" href="{{ route('admin.reservations.walkin') }}">New Walk-in</a>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <!-- Placeholder for calendar widget (e.g., FullCalendar) -->
        <div class="h-80 flex items-center justify-center text-muted">Calendar widget goes here</div>
    </div>
</div>
@endsection
