<!-- .modal for add task -->
<div class="modal fade" id="Pdf_{{$con->EMC_ID}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Descargar Certificado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Contrato.pdf', $con->EMC_ID) }}" method="GET">
                    @csrf
                    <div class="row">

                        <div class="col-md-3">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="check_salario_{{$con->EMC_ID}}" name="check_salario_{{$con->EMC_ID}}" value="1">
                                <label class="custom-control-label" for="check_salario_{{$con->EMC_ID}}">Salario</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="check_funciones_{{$con->EMC_ID}}" name="check_funciones_{{$con->EMC_ID}}" value="1">
                                <label class="custom-control-label" for="check_funciones_{{$con->EMC_ID}}">Funciones</label>
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
