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

		/* Booked day cells: visually disabled */
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-day.room-booked-day {
			cursor: not-allowed;
		}
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-day.room-booked-day .fc-daygrid-day-frame {
			opacity: 0.75;
		}

		/* Multi-selected (unbooked) dates */
		#room_calendar .fc-daygrid-day.room-multi-selected .fc-daygrid-day-frame {
			background-color: var(--muted);
		}
		#room_calendar .fc-daygrid-day.room-multi-selected .fc-daygrid-day-top {
			z-index: 3;
		}

		/* Month view: hide event boxes/text (we mark booked days via overlay icon) */
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-event-harness,
		#room_calendar .fc-dayGridMonth-view .fc-daygrid-event {
			display: none;
		}

		/* Booked icon overlay inside each booked day cell */
		#room_calendar .fc-daygrid-day-frame {
			position: relative;
			overflow: hidden;
		}
		#room_calendar .fc-daygrid-day-top {
			position: relative;
			z-index: 4;
		}
		/* Month view: booked label as diagonal corner ribbon */
		#room_calendar .room-booked-day-badge {
			position: absolute;
			top: 48px;
			left: -23px;
			width: 185px;
			z-index: 3;
			pointer-events: none;
			text-transform: uppercase;
			letter-spacing: 0.50em;
			font-size: 13px;
			line-height: 1.2;
			padding: 6px 0;
			text-align: center;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			transform: rotate(-40deg);
			transform-origin: center;
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var calendarEl = document.getElementById('room_calendar');
			if (!calendarEl || typeof FullCalendar === 'undefined') return;
			var selectedDatesInput = document.getElementById('room_calendar_selected_dates');
			var bookingBtn = document.getElementById('room_calendar_booking_btn');

			var events = @json($roomCalendarEvents ?? $calendarEvents ?? []);
			var initialDate = @json($initialDate ?? null);
			var reservationShowUrlTemplate = @json(route('admin.reservations.show', ['id' => '__ID__']));
			var reservationCreateUrl = @json(route('admin.reservations.create'));
			var selectedRoomId = @json(old('room_id', request('room_id')));
			var preselectedDatesRaw = @json(old('dates', request('dates')));

			function toIsoDate(dateObj) {
				var y = dateObj.getFullYear();
				var m = String(dateObj.getMonth() + 1).padStart(2, '0');
				var d = String(dateObj.getDate()).padStart(2, '0');
				return y + '-' + m + '-' + d;
			}

			function parseIsoDate(dateValue) {
				if (!dateValue) return null;
				if (dateValue instanceof Date) {
					var copy = new Date(dateValue.getTime());
					return isNaN(copy.getTime()) ? null : copy;
				}
				var s = String(dateValue).trim();
				var d;
				// Prefer local-midnight parsing for date-only strings to avoid UTC day shifts
				if (/^\d{4}-\d{2}-\d{2}$/.test(s)) {
					d = new Date(s + 'T00:00:00');
					return isNaN(d.getTime()) ? null : d;
				}
				// Normalize "YYYY-MM-DD HH:MM:SS" to ISO-like
				if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}/.test(s)) {
					s = s.replace(' ', 'T');
				}
				d = new Date(s);
				if (!isNaN(d.getTime())) return d;
				// Last fallback: if we can at least extract the date portion
				var m = s.match(/^(\d{4}-\d{2}-\d{2})/);
				if (m) {
					d = new Date(m[1] + 'T00:00:00');
					return isNaN(d.getTime()) ? null : d;
				}
				return null;
			}

			function addDays(dateObj, days) {
				var d = new Date(dateObj.getTime());
				d.setDate(d.getDate() + days);
				return d;
			}

			function parseDateList(raw) {
				if (!raw) return [];
				if (Array.isArray(raw)) return raw;
				return String(raw)
					.split(',')
					.map(function (s) { return String(s || '').trim(); })
					.filter(function (s) { return /^\d{4}-\d{2}-\d{2}$/.test(s); });
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
			var selectedDates = new Set(parseDateList(preselectedDatesRaw));

			function sanitizeSelectedDates() {
				if (!selectedDates || selectedDates.size === 0) return;
				var today = new Date();
				today.setHours(0, 0, 0, 0);
				Array.from(selectedDates).forEach(function (iso) {
					var d = parseIsoDate(iso);
					if (!d) {
						selectedDates.delete(iso);
						return;
					}
					d.setHours(0, 0, 0, 0);
					if (d < today) {
						selectedDates.delete(iso);
						return;
					}
					if (bookedDates && bookedDates.has(iso)) {
						selectedDates.delete(iso);
						return;
					}
				});
			}

			sanitizeSelectedDates();

			function syncBookingButton() {
				if (!bookingBtn) return;
				var arr = Array.from(selectedDates);
				arr.sort();
				if (arr.length === 0) {
					bookingBtn.classList.add('hidden');
					bookingBtn.setAttribute('aria-hidden', 'true');
					bookingBtn.setAttribute('tabindex', '-1');
					bookingBtn.removeAttribute('href');
					return;
				}

				var url = reservationCreateUrl
					+ '?dates=' + encodeURIComponent(arr.join(','));
				if (selectedRoomId !== null && selectedRoomId !== '') {
					url += '&room_id=' + encodeURIComponent(String(selectedRoomId));
				}

				bookingBtn.setAttribute('href', url);
				bookingBtn.classList.remove('hidden');
				bookingBtn.removeAttribute('aria-hidden');
				bookingBtn.removeAttribute('tabindex');
			}

			function syncSelectedDatesField() {
				if (!selectedDatesInput) return;
				var arr = Array.from(selectedDates);
				arr.sort();
				selectedDatesInput.value = arr.join(', ');
				syncBookingButton();
			}

			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				initialDate: initialDate || undefined,
				dayCellDidMount: function (info) {
					if (!info || !info.view || info.view.type !== 'dayGridMonth') return;
					var iso = toIsoDate(info.date);
					if (selectedDates && selectedDates.has(iso) && info.el && info.el.classList) {
						info.el.classList.add('room-multi-selected');
					}
					if (!bookedDates || !bookedDates.has(iso)) return;

					if (info.el && info.el.classList) {
						info.el.classList.add('room-booked-day');
						info.el.setAttribute('aria-disabled', 'true');
					}

					var frame = info.el && info.el.querySelector
						? (info.el.querySelector('.fc-daygrid-day-frame') || info.el)
						: info.el;
					if (!frame) return;
					if (frame.querySelector && frame.querySelector('[data-booked-badge="1"]')) return;

					var badge = document.createElement('span');
					badge.className = 'kt-badge kt-badge-destructive room-booked-day-badge';
					badge.setAttribute('data-booked-badge', '1');
					badge.textContent = 'BOOKED';
					frame.appendChild(badge);
				},
				dateClick: function (info) {
					// Guard: treat past dates as disabled
					var clicked = info && info.date ? new Date(info.date) : null;
					if (!clicked) return;
					var today = new Date();
					today.setHours(0, 0, 0, 0);
					clicked.setHours(0, 0, 0, 0);
					if (clicked < today) return;

					// Guard: booked dates are disabled (no click action)
					var iso = toIsoDate(clicked);
					if (bookedDates && bookedDates.has(iso)) return;

					// Multi-select future, unbooked dates (month view)
					if (info && info.view && info.view.type === 'dayGridMonth') {
						var dayEl = info.dayEl || info.el;
						if (!dayEl || !dayEl.classList) return;
						if (selectedDates.has(iso)) {
							selectedDates.delete(iso);
							dayEl.classList.remove('room-multi-selected');
						} else {
							selectedDates.add(iso);
							dayEl.classList.add('room-multi-selected');
						}
						syncSelectedDatesField();
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
			syncSelectedDatesField();
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
				Booked
			</span>
			@if(empty($selectedRoomId))
				<span class="text-sm text-secondary-foreground">Select a room to load its calendar events.</span>
			@endif
		</div>

		<div class="mt-4">
			<label class="text-sm text-secondary-foreground">Selected dates</label>
			<input id="room_calendar_selected_dates" type="text" class="kt-input w-full" readonly placeholder="Click dates on the calendar" />
		</div>

		<div class="mt-4">
			<a id="room_calendar_booking_btn" class="kt-btn kt-btn-primary hidden" href="{{ route('admin.reservations.create') }}" aria-hidden="true" tabindex="-1">Booking</a>
		</div>

		<div class="mt-4">
			<div id="room_calendar"></div>
		</div>
	</div>
</div>
@endsection

