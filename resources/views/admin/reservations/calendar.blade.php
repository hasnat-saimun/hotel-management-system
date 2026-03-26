@extends('admin.layouts.app')
@section('title', 'Booking Calendar')

@push('scripts')
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script>    
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendarEvents = @json($calendarEvents ?? []);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // initialView: 'dayGridMonth',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay,dayGridMonth'
            },
            dayMaxEvents: true,
            events: calendarEvents,
            eventClick: function(info) {
                alert('Event: ' + info.event.title);
                // You can also navigate to a reservation detail page here
            }
        });
        calendar.render();
      });
    </script>
@endpush
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Booking Calendar</h3>
            <div class="text-sm text-secondary-foreground">Room availability and bookings</div>
        </div>
        <div class="flex gap-2">
            <a class="kt-btn" href="{{ route('admin.reservations.index') }}">List</a>
            <a class="kt-btn kt-btn-primary" href="">New Walk-in</a>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <div id="calendar"></div>
    </div>
</div>
@endsection
