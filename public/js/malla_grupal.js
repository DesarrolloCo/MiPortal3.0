window.onload = function () {

    let $select_campana = document.getElementById('CAM_ID')
    let $select_cliente = document.getElementById('CLI_ID')

    /* CARGAR CAMPAÑA */
    function llenarTabla(sendDatos) {

        $.ajax({
            url: '../mallagrupal/select/cam',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function (response) {

                var resp = response;
                var data = resp.data;
                var listado = $("[name=tablaempleados]");
                listado.empty();
                if (data.length < 1 || resp.success == false) {
                    listado.append(
                        '<tr><td colspan="2" >No hay agentes asignados a esta campaña</td></tr>'
                    );
                } else {

                    for (var i = 0; i < data.length; i++) {

                        var item = data[i];

                        listado.append(
                            '<tr>' +
                            '<td>' + item['EMP_CEDULA'] + '</td>' +
                            '<td>' + item['EMP_NOMBRES'] + '</td>' +
                            '</tr>'
                        );
                    }
                }
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_campana.addEventListener('change', () => {
        const CAM_ID = $select_campana.value

        const sendDatos = {
            'CAM_ID': CAM_ID
        }

        llenarTabla(sendDatos)

    })

    /* SELECT DINAMICO */

    /* CARGAR CAMPAnA */
    function cargarCampana(sendDatos) {

        $.ajax({
            url: '../select/cli',
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

}
