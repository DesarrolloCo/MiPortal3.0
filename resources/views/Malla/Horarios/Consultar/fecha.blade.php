<!-- sample modal content -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="look_for_date" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Agregar empleado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Supervisor.look_for') }}" method="GET" name="form-data">
                    @csrf

                    @php
                        use App\Models\cliente;
                        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();
                    @endphp

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select ame="CLI_ID" id="CLI_ID3" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($clientes as $cli)
                                        <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Campañas</label>
                                <select name="CAM_ID" id="CAM_ID3" class="form-control">
                                    {{-- SELECT CAMPAÑA --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha inicial</label>
                                <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha final</label>
                                <input type="date" name="FECHA_FINAL" id="FECHA_FINAL" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    /* --variables para llamar a los select por el id */
    let $select_cliente3 = document.getElementById('CLI_ID3')
    let $select_campana3 = document.getElementById('CAM_ID3')

    /* CARGAR CAMPAnA */
    function cargarCampana3(sendDatos) {

        $.ajax({
            url: '../../select/cli',
            type: 'GET',
            dataType: 'json',
            data: sendDatos,
            success: function(response) {
                const respuestas = response.campana;

                let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

                respuestas.forEach(respuesta => {
                    template +=
                        `<option class="form-control" value="${respuesta.CAM_ID}">${respuesta.CAM_NOMBRE}</option>`;
                })

                $select_campana3.innerHTML = template;
            },
            error: function(jqXHR) {
                console.log('error!');
            }
        });

    }

    $select_cliente3.addEventListener('change', () => {
        const CLI_ID2 = $select_cliente3.value

        const sendDatos = {
            'CLI_ID': CLI_ID2
        }

        cargarCampana3(sendDatos)

    })
</script>
