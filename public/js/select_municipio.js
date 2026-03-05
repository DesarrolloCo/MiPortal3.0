
/* SELECT DINAMICO */

    /* --variables para llamar a los select por el id */
    let $select_departamento = document.getElementById('DEP_ID')
    let $select_campana = document.getElementById('MUN_ID')

    /* CARGAR CAMPAnA */
    function cargarMunicipios(sendDatos) {

        $.ajax({
            url: '../../select/mun',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function (response) {
                const respuestas = response.data;

                let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

                respuestas.forEach(respuesta => {
                    template += `<option class="form-control" value="${respuesta.MUN_ID}">${respuesta.MUN_NOMBRE}</option>`;
                })

                $select_campana.innerHTML = template;
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_departamento.addEventListener('change', () => {
        const DEP_ID = $select_departamento.value

        const sendDatos = {
            'DEP_ID': DEP_ID
        }

        cargarMunicipios(sendDatos)

    })


/* SELECT DINAMICO */

    /* --variables para llamar a los select por el id */
    let $select_departamento_edit = document.getElementById('DEP_ID_EDIT')
    let $select_campana_edit = document.getElementById('MUN_ID_EDIT')

    /* CARGAR CAMPAnA */
    function cargarMunicipios_EDIT(sendDatos) {

        $.ajax({
            url: '../../select/mun',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function (response) {
                const respuestas = response.data;

                let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

                respuestas.forEach(respuesta => {
                    template += `<option class="form-control" value="${respuesta.MUN_ID}">${respuesta.MUN_NOMBRE}</option>`;
                })

                $select_campana_edit.innerHTML = template;
            },
            error: function (jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_departamento_edit.addEventListener('change', () => {
        const DEP_ID = $select_departamento_edit.value

        const sendDatos = {
            'DEP_ID': DEP_ID
        }

        cargarMunicipios_EDIT(sendDatos)

    })
