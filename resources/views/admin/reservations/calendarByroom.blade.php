@extends('admin.layouts.app')
@section('title', 'Room Calendar')

@push('scripts')
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
	<style>
		/* Disable/grey out past dates (visual) */
		#room_calendar .fc-day-past {
			opacity: 0.45;
			filter: grayscale(0.2);
		}

		/* Month view: hide event boxes/text (we mark booked days via overlay icon) */
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-event-harness,
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-event {
			display: none;
		}

		/* Booked icon overlay inside each booked day cell */
		#room_calendar .fc-daygrid-day-frame {
			position: relative;
		}
		#room_calendar .fc-daygrid-day-top {
			position: relative;
			z-index: 2;
		}
		#room_calendar .room-booked-day-icon {
			position: absolute;
			inset: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			opacity: 0.95;
			z-index: 1;
		}
		#room_calendar .room-booked-day-icon i {
			font-size: 150px;
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var calendarEl = document.getElementById('room_calendar');
			if (!calendarEl || typeof FullCalendar === 'undefined') return;

			var events = @json($roomCalendarEvents ?? $calendarEvents ?? []);
			var initialDate = @json($initialDate ?? null);
			var reservationShowUrlTemplate = @json(route('admin.reservations.show', ['id' => '__ID__']));

			function toIsoDate(dateObj) {
				var y = dateObj.getFullYear();
				var m = String(dateObj.getMonth() + 1).padStart(2, '0');
				var d = String(dateObj.getDate()).padStart(2, '0');
				return y + '-' + m + '-' + d;
			}

			function parseIsoDate(dateStr) {
				if (!dateStr) return null;
				var d = new Date(String(dateStr) + 'T00:00:00');
				return isNaN(d.getTime()) ? null : d;
			}

			function addDays(dateObj, days) {
				var d = new Date(dateObj.getTime());
				d.setDate(d.getDate() + days);
				return d;
			}

			function buildBookedDateSet(eventList) {
				var set = new Set();
				(eventList || []).forEach(function (ev) {
					if (!ev || !ev.start) return;
					var start = parseIsoDate(ev.start);
					var end = parseIsoDate(ev.end);
					if (!start) return;
					// FullCalendar 'end' is exclusive; mark [start, end)
					if (!end) {
						set.add(toIsoDate(start));
						return;
					}
					for (var d = new Date(start.getTime()); d < end; d = addDays(d, 1)) {
						set.add(toIsoDate(d));
					}
				});
				return set;
			}

			function buildBookedDateToReservationIdMap(eventList) {
				var map = Object.create(null);
				(eventList || []).forEach(function (ev) {
					if (!ev || !ev.start || !ev.id) return;
					var start = parseIsoDate(ev.start);
					var end = parseIsoDate(ev.end);
					if (!start) return;
					var rid = String(ev.id);
					if (!end) {
						var one = toIsoDate(start);
						if (!map[one]) map[one] = rid;
						return;
					}
					for (var d = new Date(start.getTime()); d < end; d = addDays(d, 1)) {
						var iso = toIsoDate(d);
						if (!map[iso]) map[iso] = rid;
					}
				});
				return map;
			}

			var bookedDates = buildBookedDateSet(events);
			var bookedDateToReservationId = buildBookedDateToReservationIdMap(events);

			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				initialDate: initialDate || undefined,
				dayCellDidMount: function (info) {
					if (!info || !info.view || info.view.type !== 'dayGridMonth') return;
					var iso = toIsoDate(info.date);
					if (!bookedDates || !bookedDates.has(iso)) return;

					var frame = info.el && info.el.querySelector
						? (info.el.querySelector('.fc-daygrid-day-frame') || info.el)
						: info.el;
					if (!frame) return;
					if (frame.querySelector && frame.querySelector('[data-booked-icon="1"]')) return;

					var wrap = document.createElement('div');
					wrap.className = 'room-booked-day-icon';
					wrap.setAttribute('data-booked-icon', '1');

					var icon = document.createElement('i');
					icon.className = 'fa-thin fa-xmark';
					icon.style.color = 'rgb(211, 18, 22)';
					wrap.appendChild(icon);

					frame.appendChild(wrap);
				},
				dateClick: function (info) {
					// Guard: treat past dates as disabled
					var clicked = info && info.date ? new Date(info.date) : null;
					if (!clicked) return;
					var today = new Date();
					today.setHours(0, 0, 0, 0);
					clicked.setHours(0, 0, 0, 0);
					if (clicked < today) return;

					// If this date is booked, open its reservation details
					var iso = toIsoDate(clicked);
					var reservationId = bookedDateToReservationId && bookedDateToReservationId[iso]
						? String(bookedDateToReservationId[iso])
						: '';
					if (reservationId) {
						var showUrl = reservationShowUrlTemplate.replace('__ID__', reservationId);
						window.location.href = showUrl;
					}
				},
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'timeGridWeek,timeGridDay,dayGridMonth'
				},
				dayMaxEvents: true,
				events: events,
				eventClick: function (info) {
					var reservationId = info.event && info.event.id ? String(info.event.id) : '';
					if (!reservationId) return;
					var showUrl = reservationShowUrlTemplate.replace('__ID__', reservationId);
					window.location.href = showUrl;
				}
			});

			calendar.render();
		});
	</script>
@endpush

@section('content')
@php($rooms = $rooms ?? collect())
@php($selectedRoomId = old('room_id', request('room_id')))
@php($selectedMonth = old('month', request('month')))

<div class="kt-card">
	<div class="kt-card-header flex items-center justify-between">
		<div>
			<h3 class="kt-card-title">Room Calendar</h3>
			<div class="text-sm text-secondary-foreground">View reservations for a single room</div>
		</div>

		<div class="flex gap-2">
			<a class="kt-btn" href="{{ route('admin.reservations.walkin') }}">Walk-in</a>
			<a class="kt-btn" href="{{ route('admin.reservations.calendar') }}">All Calendar</a>
		</div>
	</div>

	<div class="kt-card-content p-4">
		<form method="GET" action="{{ url()->current() }}" class="grid gap-3 grid-cols-1 lg:grid-cols-3">
			<div>
				<label class="text-sm text-secondary-foreground">Room</label>
				<select class="kt-input w-full" name="room_id">
					<option value="">Select room</option>
					@foreach($rooms as $room)
						<option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>
							{{ $room->room_number ?? ('Room #' . $room->id) }}
							@if($room->roomType?->name)
								- {{ $room->roomType->name }}
							@endif
						</option>
					@endforeach
				</select>
			</div>

			<div>
				<label class="text-sm text-secondary-foreground">Month (optional)</label>
				<input type="month" class="kt-input w-full" name="month" value="{{ $selectedMonth ?? '' }}" />
			</div>

			<div class="flex items-end gap-2">
				<button type="submit" class="kt-btn kt-btn-primary">Search</button>
				<a class="kt-btn kt-btn-secondary" href="{{ url()->current() }}">Clear</a>
			</div>
		</form>

		<div class="mt-4 flex flex-wrap items-center gap-2">
			<span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Click event = open reservation</span>
			<span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-destructive">
				<i class="fa-thin fa-xmark" style="color: rgb(211, 18, 22);"></i>
				Booked
			</span>
			@if(empty($selectedRoomId))
				<span class="text-sm text-secondary-foreground">Select a room to load its calendar events.</span>
			@endif
		</div>

		<div class="mt-4">
			<div id="room_calendar"></div>
		</div>
	</div>
</div>
@endsection

