window.onload = function () {

    let $select_area = document.getElementById('ARE_ID')
    let $select_equipo = document.getElementById('EQU_ID')

    /* SELECT DINAMICO */

    /* CARGAR EQUIPO */
    function cargarEquipos(sendDatos) {

        $.ajax({
            url: '../Select',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function (response) {
                const respuestas = response.data;

                let template = ''

                respuestas.forEach(respuesta => {
                    template += `<option value="${respuesta.EQU_ID}">${respuesta.EQU_NOMBRE}</option>`;
                })

                $select_equipo.innerHTML = template;
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_area.addEventListener('change', () => {
        const ARE_ID = $select_area.value

        const sendDatos = {
            'ARE_ID': ARE_ID
        }

        cargarEquipos(sendDatos)

    })

}
