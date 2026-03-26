<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservation Calendar</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // initialView: 'dayGridMonth',
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay,dayGridMonth'
            },
            events: [
                {
                title: 'Reserved',
                start: '2026-03-22T10:00:00',
                end: '2026-03-22T12:00:00',
                },
            ],
            ever
        });
        calendar.render();
      });

    </script>
</head>
<body class="bg-gray-100 p-6">
<div id="calendar"></div>

</body>
</html>