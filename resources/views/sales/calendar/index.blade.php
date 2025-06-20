<x-sales-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        {{ __('Event Calendar') }}
    </h2>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div id='calendar'></div>
    </div>
    
    {{-- Tambahkan styling untuk calendar --}}
    <style>
        .fc-event {
            cursor: pointer;
        }
    </style>

    {{-- Import FullCalendar JS --}}
    <script type="module">
        import { Calendar } from '@fullcalendar/core';
        import dayGridPlugin from '@fullcalendar/daygrid';
        import timeGridPlugin from '@fullcalendar/timegrid';

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin, timeGridPlugin ],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '{{ route("sales.calendar.events") }}', // URL untuk mengambil data event
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Mencegah browser mengikuti link
                    if (info.event.url) {
                        window.open(info.event.url, "_self"); // Buka link di tab yang sama
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-sales-layout>