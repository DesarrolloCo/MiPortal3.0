<!-- .modal for add task -->
<div class="modal fade" id="Edit_asg_equ{{ $row->EAS_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar evidencia</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Asignacion_equipo.create2') }}" method="POST"novalidate enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="EAS_ID" value="{{ $row->EAS_ID }}">
                    <div class="col-md-12">
                        <label for="EVI_NOMBRE" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="EVI_NOMBRE" name="EVI_NOMBRE" required>
                        <div class="invalid-feedback">Completa los datos</div>
                    </div>
                    <div class="col-md-12">
                        <label for="EVI_FECHA" class="form-label">Fecha de entrega</label>
                        <input type="date" class="form-control" id="EVI_FECHA" name="EVI_FECHA" required>
                        <div class="invalid-feedback">Completa los datos</div>
                    </div>
                    <div class="form-group">
                        <label>Evidencia</label>
                        <input type="file" name="EVI_EVIDENCIA" class="form-control" >
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
