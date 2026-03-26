@extends('admin.layouts.app')
@section('title', 'Booking Calendar')

@push('scripts')
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <style>
        @keyframes reservationDetailsModalPop {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.985);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        #reservation_details_modal.open .kt-modal-content {
            animation: reservationDetailsModalPop 180ms ease-out;
        }
    </style>
    <script>    
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendarEvents = @json($calendarEvents ?? []);
                var reservationShowUrlTemplate = @json(route('admin.reservations.show', ['id' => '__ID__']));
                var reservationModalUrlTemplate = @json(route('admin.reservations.calendar-modal', ['id' => '__ID__']));
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
                var reservationId = info.event.id;
                var modalFetchUrl = reservationModalUrlTemplate.replace('__ID__', reservationId);
                var showUrl = reservationShowUrlTemplate.replace('__ID__', reservationId);

                var modalTitleEl = document.getElementById('reservation_details_modal_title');
                var modalBodyEl = document.getElementById('reservation_details_modal_body');
                var modalOpenLink = document.getElementById('reservation_details_modal_open_link');
                var modalToggleBtn = document.getElementById('reservation_details_modal_toggle');

                if (modalOpenLink) {
                    modalOpenLink.href = showUrl;
                }

                if (modalTitleEl) {
                    modalTitleEl.textContent = 'Reservation Details';
                }

                if (modalBodyEl) {
                    modalBodyEl.innerHTML = '<div class="text-sm text-secondary-foreground">Loading…</div>';
                }

                fetch(modalFetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                    return response.json();
                })
                .then(function(payload) {
                    if (modalTitleEl && payload && payload.title) {
                        modalTitleEl.textContent = payload.title;
                    }
                    if (modalBodyEl) {
                        modalBodyEl.innerHTML = (payload && payload.html) ? payload.html : '<div class="text-sm text-secondary-foreground">No details found.</div>';
                    }
                })
                .catch(function() {
                    if (modalBodyEl) {
                        modalBodyEl.innerHTML = '<div class="text-sm text-destructive">Failed to load reservation details.</div>';
                    }
                });

                if (modalToggleBtn) {
                    modalToggleBtn.click();
                }
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

<button id="reservation_details_modal_toggle" class="hidden" type="button" data-kt-modal-toggle="#reservation_details_modal"></button>

<div class="kt-modal" data-kt-modal="true" id="reservation_details_modal">
    <div class="kt-modal-content w-full lg:w-[25vw] lg:min-w-[360px] max-w-none top-5 lg:top-[10%]">
        <div class="kt-modal-header">
            <h3 class="kt-modal-title" id="reservation_details_modal_title">Reservation Details</h3>
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true" type="button">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
        <div class="kt-modal-body grid gap-4 max-h-[75vh]" id="reservation_details_modal_body"></div>
        <div class="kt-modal-footer justify-end gap-2">
            <button class="kt-btn" data-kt-modal-dismiss="true" type="button">Close</button>
            <a class="kt-btn kt-btn-primary" id="reservation_details_modal_open_link" href="#">Open Details</a>
        </div>
    </div>
</div>
@endsection
