<script>
    function ojito() {
    const checkbox = document.getElementById('check_fecha');
    const jornadaDiv = document.getElementById('jornada');
    const horaDiv = document.getElementById('for_fecha');
    const hiddenInput = document.getElementById('checkJorOrHor');

    if (checkbox.checked) {
        // Activado: usar hora inicial y final
        jornadaDiv.style.display = 'none';
        horaDiv.style.display = 'flex';
        hiddenInput.value = 1;
    } else {
        // Desactivado: usar jornada
        jornadaDiv.style.display = 'flex';
        horaDiv.style.display = 'none';
        hiddenInput.value = 0;
    }
}

    // NOTA: La tabla #table_equipos ahora se inicializa en su vista específica con configuración optimizada
    // Ver: resources/views/Malla/Empleado/index.blade.php

    $(document).ready( function () {
        $('#table_mantenimiento').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_cargo').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_individual').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_campana').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_cargos').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_jornadas').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_uni_cli').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_unidad_negocio').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_cliente').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_hardware').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_software').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_mantenimiento_details').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#users').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#roles').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_change').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    } );
    $(document).ready( function () {
        $('#table_novedades').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "order": [[ 0, "desc" ]],
            "pageLength": 10,
            "scrollX": true,
            "responsive": false,
            "columnDefs": [
                { "orderable": false, "targets": 9 }
            ]
        });
    } );
    $(document).ready( function () {
        $('#table_gestion_novedades').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "order": [[ 1, "asc" ]],
            "pageLength": 20,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [0, 8] }
            ]
        });
    } );
    $(document).ready( function () {
        $('#table_tipos_novedades').DataTable({
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "order": [[ 0, "asc" ]],
            "pageLength": 10,
            "scrollX": true,
            "responsive": false,
            "columnDefs": [
                { "orderable": false, "targets": 4 }
            ]
        });
    } );
</script>
