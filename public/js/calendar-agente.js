
window.onload = function () {

    /* CALENDARIO DEL AGENTE */
    var id_empleado = $("#id_empleado").val();

    if (!id_empleado) {
        console.log('No hay ID de empleado disponible');
        return;
    }

    var data = {
        'id_empleado': id_empleado
    }

    $.ajax({
        url: "calendario/agente",
        type: 'GET',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            console.log('Cargando calendario para agente:', id_empleado);
        },
        success: function (response) {
            console.log('Datos recibidos del servidor:', response);
            var data = response.evento;

            // Configurar colores de borde basados en el color principal
            data.forEach(function(event) {
                if (event.color === '#dc3545') {
                    // Rojo - inactivo
                    event.borderColor = '#c82333';
                    event.textColor = '#ffffff';
                } else if (event.color === '#fd7e14') {
                    // Naranja - novedad
                    event.borderColor = '#e8680d';
                    event.textColor = '#ffffff';
                } else {
                    // Azul/Verde - activo normal
                    event.borderColor = event.color === '#007bff' ? '#0056b3' : '#1e7e34';
                    event.textColor = '#ffffff';
                }
            });

            console.log('Total de eventos a mostrar:', data.length);
            console.log('Eventos procesados:', data);

            var calendarEl = document.getElementById('calendario_agente');

            if (!calendarEl) {
                console.error('ERROR: No se encontró el elemento calendario_agente');
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay,dayGridMonth'
                },
                locale: 'es',
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                expandRows: true,
                nowIndicator: true,
                events: data,
                eventContent: function(arg) {
                    // Personalizar el contenido del evento de forma compacta
                    var event = arg.event;
                    var html = '<div class="custom-event-content">';

                    // Mostrar campaña y horario
                    html += '<div class="event-campaign">' + event.extendedProps.CAM_NOMBRE + '</div>';
                    html += '<div class="event-time">' + event.extendedProps.MAL_INICIO + '-' + event.extendedProps.MAL_FINAL + '</div>';

                    html += '</div>';

                    return { html: html };
                },
                eventDidMount: function(info) {
                    // Agregar tooltip con jQuery/Bootstrap
                    var estadoTexto = '';
                    if (info.event.extendedProps.MAL_ESTADO == 0) {
                        estadoTexto = '🚫 Bloqueado por novedad';
                    } else if (info.event.extendedProps.MAL_ESTADO == 2) {
                        estadoTexto = '🍽️ Almuerzo';
                    } else {
                        estadoTexto = '✅ Trabajo activo';
                    }

                    var tooltipText =
                        '🏢 Campaña: ' + info.event.extendedProps.CAM_NOMBRE + '\n' +
                        '⏰ Horario: ' + info.event.extendedProps.MAL_INICIO + ' - ' + info.event.extendedProps.MAL_FINAL + '\n' +
                        '📊 Estado: ' + estadoTexto;

                    $(info.el).attr('title', tooltipText);
                    $(info.el).css('cursor', 'pointer');
                },
                eventClick: function(info) {
                    // Mostrar detalles del evento al hacer click
                    var event = info.event;
                    var estadoDetalle = '';
                    if (event.extendedProps.MAL_ESTADO == 0) {
                        estadoDetalle = '🚫 Bloqueado por novedad';
                    } else if (event.extendedProps.MAL_ESTADO == 2) {
                        estadoDetalle = '🍽️ Tiempo de almuerzo';
                    } else {
                        estadoDetalle = '✅ Horario de trabajo activo';
                    }

                    var detalle = '📋 MI HORARIO\n\n';
                    detalle += '🏢 Campaña: ' + event.extendedProps.CAM_NOMBRE + '\n';
                    detalle += '⏰ Horario: ' + event.extendedProps.MAL_INICIO + ' - ' + event.extendedProps.MAL_FINAL + '\n';
                    detalle += '📊 Estado: ' + estadoDetalle + '\n';
                    detalle += '🆔 ID: ' + event.id;

                    alert(detalle);
                }
            });

            console.log('Renderizando calendario del agente...');
            calendar.render();
            console.log('Calendario del agente renderizado exitosamente');

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('ERROR al cargar calendario del agente:');
            console.error('Status:', textStatus);
            console.error('Error:', errorThrown);
            console.error('Response:', jqXHR.responseText);

            // Mostrar mensaje de error al usuario
            var calendarEl = document.getElementById('calendario_agente');
            if (calendarEl) {
                calendarEl.innerHTML = '<div class="alert alert-danger text-center"><i class="mdi mdi-alert-circle"></i> Error al cargar el calendario. Por favor, recarga la página.</div>';
            }
        }
    });
}

