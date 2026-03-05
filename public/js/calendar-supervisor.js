window.onload = function () {

    /* CALENDARIO */
    var id_empleado = $("#agente_info").find("[name=id_empleado]").val();

    data = {
        'id_empleado': id_empleado
    }

    $.ajax({
        url: '../../supervisor/calendario/agente',
        type: 'GET',
        dataType: 'json',
        data: data,
        /* beforeSend: function () {
            console.log('enviada');
        },
        complete: function () {
            console.log('completada');
        }, */
        success: function (response) {
            data = response.evento;
            // Agregar color basado en el estado
            data.forEach(function(event) {
                if (event.MAL_ESTADO == 1) {
                    event.backgroundColor = '#28a745'; // Verde para activos
                    event.borderColor = '#1e7e34';
                    event.textColor = '#ffffff';
                } else {
                    event.backgroundColor = '#dc3545'; // Rojo para inactivos
                    event.borderColor = '#c82333';
                    event.textColor = '#ffffff';
                    event.title = event.title + ' (Inactivo)'; // Agregar indicador en el título
                }
            });

            var calendarE1 = document.getElementById('calendario_supervisor');
            var calendar = new FullCalendar.Calendar(calendarE1, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                locales: 'es',
                events: data
            });
            calendar.render();

        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });

    /* SELECT DINAMICO */

    /* --variables para llamar a los select por el id */
    let $select_cliente = document.getElementById('CLI_ID')
    let $select_campana = document.getElementById('CAM_ID')

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
    /* SELECT DINAMICO */

    /* --variables para llamar a los select por el id */
    let $select_cliente2 = document.getElementById('CLI_ID2')
    let $select_campana2 = document.getElementById('CAM_ID2')

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
