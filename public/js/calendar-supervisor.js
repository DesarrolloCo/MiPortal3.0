window.onload = function () {

    /* CALENDARIO */
    var id_empleado = $("#agente_info").find("[name=id_empleado]").val();

    data = {
        'id_empleado': id_empleado
    }

    $.ajax({
        url: '/supervisor/calendario/agente',
        type: 'GET',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            console.log('Cargando calendario para empleado:', id_empleado);
        },
        success: function (response) {
            console.log('Datos recibidos del servidor:', response);
            data = response.evento;
            // Los colores ya vienen configurados desde el servidor
            // Solo agregar configuración adicional si es necesario
            data.forEach(function(event) {
                // Configurar colores de borde y texto basados en el color principal
                if (event.color === '#dc3545') {
                    // Rojo - inactivo
                    event.borderColor = '#c82333';
                    event.textColor = '#ffffff';
                } else if (event.color === '#fd7e14') {
                    // Naranja - novedad
                    event.borderColor = '#e8680d';
                    event.textColor = '#ffffff';
                } else {
                    // Verde - activo normal
                    event.borderColor = '#1e7e34';
                    event.textColor = '#ffffff';
                }
            });

            console.log('Total de eventos a mostrar:', data.length);
            console.log('Eventos procesados:', data);

            var calendarE1 = document.getElementById('calendario_supervisor');

            if (!calendarE1) {
                console.error('ERROR: No se encontró el elemento calendario_supervisor');
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarE1, {
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
                    let html = '<div class="custom-event-content">';

                    // Solo mostrar el nombre de la campaña de forma compacta
                    html += '<div class="event-campaign">' + arg.event.extendedProps.CAM_NOMBRE + '</div>';

                    // Mostrar horario en formato compacto
                    html += '<div class="event-time">' + arg.event.extendedProps.MAL_INICIO + '-' + arg.event.extendedProps.MAL_FINAL + '</div>';

                    // Si está inactivo (bloqueado), mostrar indicador con tipo de novedad
                    if (arg.event.extendedProps.MAL_ESTADO == 0) {
                        let novedadText = '🚫 BLOQUEADO';
                        if (arg.event.extendedProps.tipos_novedad) {
                            novedadText += ': ' + arg.event.extendedProps.tipos_novedad;
                        }
                        html += '<div class="event-time" style="color: #fff; font-weight: bold; font-size: 8px;">' + novedadText + '</div>';
                    }

                    html += '</div>';

                    return { html: html };
                },
                eventDidMount: function(info) {
                    // Agregar tooltip con jQuery/Bootstrap
                    var estadoTexto = '';
                    if (info.event.extendedProps.MAL_ESTADO == 0) {
                        estadoTexto = '🚫 Inactivo (Bloqueado por novedad)';
                    } else if (info.event.extendedProps.MAL_ESTADO == 2) {
                        estadoTexto = '🍽️ Tiempo de almuerzo';
                    } else {
                        estadoTexto = '✅ Horario de trabajo activo';
                    }

                    var tooltipText =
                        '🏢 Campaña: ' + info.event.extendedProps.CAM_NOMBRE + '\n' +
                        '⏰ Horario: ' + info.event.extendedProps.MAL_INICIO + ' - ' + info.event.extendedProps.MAL_FINAL + '\n' +
                        '📊 Estado: ' + estadoTexto;

                    if (info.event.extendedProps.MAL_ESTADO == 0 && info.event.extendedProps.tipos_novedad) {
                        tooltipText += '\n🚨 Tipo: ' + info.event.extendedProps.tipos_novedad;
                        if (info.event.extendedProps.descripcion_novedad) {
                            tooltipText += '\n📝 Detalle: ' + info.event.extendedProps.descripcion_novedad;
                        }
                    }

                    $(info.el).attr('title', tooltipText);
                    $(info.el).css('cursor', 'pointer');
                },
                eventClick: function(info) {
                    // Mostrar detalles del evento al hacer click
                    var event = info.event;
                    var estadoDetalle = '';
                    if (event.extendedProps.MAL_ESTADO == 0) {
                        estadoDetalle = '🚫 Inactivo (Bloqueado por novedad)';
                    } else if (event.extendedProps.MAL_ESTADO == 2) {
                        estadoDetalle = '🍽️ Tiempo de almuerzo';
                    } else {
                        estadoDetalle = '✅ Horario de trabajo activo';
                    }

                    var detalle = '📋 DETALLES DEL HORARIO\n\n';
                    detalle += '🏢 Campaña: ' + event.extendedProps.CAM_NOMBRE + '\n';
                    detalle += '⏰ Horario: ' + event.extendedProps.MAL_INICIO + ' - ' + event.extendedProps.MAL_FINAL + '\n';
                    detalle += '📊 Estado: ' + estadoDetalle + '\n';

                    if (event.extendedProps.MAL_ESTADO == 0 && event.extendedProps.tipos_novedad) {
                        detalle += '🚨 NOVEDAD: ' + event.extendedProps.tipos_novedad + '\n';
                        if (event.extendedProps.descripcion_novedad) {
                            detalle += '📝 Detalle: ' + event.extendedProps.descripcion_novedad + '\n';
                        }
                    }

                    detalle += '🆔 ID: ' + event.id;

                    alert(detalle);
                }
            });

            console.log('Renderizando calendario...');
            calendar.render();
            console.log('Calendario renderizado exitosamente');

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('ERROR al cargar calendario:');
            console.error('Status:', textStatus);
            console.error('Error:', errorThrown);
            console.error('Response:', jqXHR.responseText);
            alert('Error al cargar el calendario. Revise la consola del navegador (F12) para más detalles.');
        }
    });

    /* SELECT DINAMICO */

    /* --variables para llamar a los select por el id */
    let $select_cliente = document.getElementById('CLI_ID')
    let $select_campana = document.getElementById('CAM_ID')

    // Verificar que los elementos existan antes de agregar listeners
    if (!$select_cliente || !$select_campana) {
        console.log('Selects de cliente/campaña no encontrados en esta vista');
        return;
    }

    /* CARGAR CAMPAÑA */
    function cargarCampana(sendDatos) {

        $.ajax({
            url: '../../select/cli',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function (response) {
                const respuestas = response.campana;

                let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

                respuestas.forEach(respuesta => {
                    template += `<option class="form-control" value="${respuesta.CAM_ID}">${respuesta.CAM_NOMBRE}</option>`;
                })

                $select_campana.innerHTML = template;
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_cliente.addEventListener('change', () => {
        const CLI_ID = $select_cliente.value

        const sendDatos = {
            'CLI_ID': CLI_ID
        }

        cargarCampana(sendDatos)

    })


    /* SELECT DINAMICO 2 */

    /* --variables para llamar a los select por el id */
    let $select_cliente2 = document.getElementById('CLI_ID2')
    let $select_campana2 = document.getElementById('CAM_ID2')

    // Verificar que los elementos existan antes de agregar listeners
    if (!$select_cliente2 || !$select_campana2) {
        console.log('Selects secundarios de cliente/campaña no encontrados en esta vista');
        return;
    }

    /* CARGAR CAMPAÑA */
    function cargarCampana2(sendDatos2) {

        $.ajax({
            url: '../../select/cli',
            type: 'GET',
            dataType: 'json',
            data: sendDatos2,
            success: function (response) {
                const respuestas = response.campana;

                let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

                respuestas.forEach(respuesta => {
                    template += `<option class="form-control" value="${respuesta.CAM_ID}">${respuesta.CAM_NOMBRE}</option>`;
                })

                $select_campana2.innerHTML = template;
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_cliente2.addEventListener('change', () => {
        const CLI_ID = $select_cliente2.value

        const sendDatos2 = {
            'CLI_ID': CLI_ID
        }

        cargarCampana2(sendDatos2)

    })



}
