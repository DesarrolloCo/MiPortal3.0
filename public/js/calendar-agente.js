
      /* document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      }); */



window.onload = function () {

    var id_empleado = $("#id_empleado").val();

    data = {
        'id_empleado': id_empleado
    }

    $.ajax({
        url: "calendario/agente",
        type: 'GET',
        dataType: 'json',
        data: data,
        success: function (response) {
            data = response.evento;
            var calendarEl = document.getElementById('calendario_agente');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                locales: 'es',
                events: data,
            });
            calendar.render();
            calendar.setOption('locale', 'es');
        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });
}

