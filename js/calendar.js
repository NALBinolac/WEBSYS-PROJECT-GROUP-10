/* ==========================================================================
   YOUTH FOR JUST FOOD SYSTEMS - EVENTS CALENDAR ENGINE
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            unselectAuto: false,
            selectable: true,
            headerToolbar: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },

            // Dynamically fetch events from the database via the API endpoint
            events: {
                url: 'api/get-events.php',
                method: 'GET',
                failure: function() {
                    console.error('Failed to load events from the server. Check api/get-events.php.');
                }
            },

            // New hook: Triggers when an event element is rendered into the DOM
            eventDidMount: function(info) {
                // Find the parent day cell wrapper and add a custom class
                var dayCell = info.el.closest('.fc-daygrid-day');
                if (dayCell) {
                    dayCell.classList.add('has-event');
                }
                
                // Optional: If you only want a clean dot/highlight and want to hide 
                // the standard full-width text event bars in this small sidebar:
                info.el.style.display = 'none'; 
            },

            // Triggers when clicking anywhere on a date cell
            dateClick: function(info) {
                var clickedDate = info.dateStr;
                var allEvents = calendar.getEvents();
                var dayEvents = [];

                allEvents.forEach(function(event) {
                    var eventDate = event.startStr.split('T')[0];
                    if (eventDate === clickedDate) {
                        dayEvents.push(event.title);
                    }
                });

                if (dayEvents.length > 0) {
                    alert('Events scheduled for ' + clickedDate + ':\n\n• ' + dayEvents.join('\n• '));
                } else {
                    alert('No events scheduled for ' + clickedDate + '.');
                }
            },

            // Triggers when clicking directly on an event pill
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                var props = info.event.extendedProps;
                var message = 'Event: ' + info.event.title;
                if (props.venue)       message += '\nVenue: '       + props.venue;
                if (props.category)    message += '\nCategory: '    + props.category;
                if (props.description) message += '\nDescription: ' + props.description;
                alert(message);
            },
        });

        calendar.render();
    }
});