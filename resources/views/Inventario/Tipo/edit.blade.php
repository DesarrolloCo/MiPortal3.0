<!-- .modal for add task -->
<div class="modal fade" id="Edit_TIPO{{ $tipo_mantenimiento->TIP_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar equipo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" method="POST" action="{{ route('Tipo.update', $tipo_mantenimiento->TIP_ID) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-12">
                        <div class="form-group">
                        <label for="TIP_TIPO" class="form-label">Tipo de mantenimiento</label>
                        <select class="form-control" name="TIP_TIPO" id="TIP_TIPO">
                            <option value="" disabled>-- Seleccione --</option>
                            <option value="Logico" @if ($tipo_mantenimiento->TIP_TIPO == 'Logico') selected @endif>Logico</option>
                            <option value="Fisico" @if ($tipo_mantenimiento->TIP_TIPO == 'Fisico') selected @endif>Fisico</option>
                        </select>
                        <div class="invalid-feedback">Completa los datos</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="TIP_NOMBRE" class="form-label">Serial</label>
                        <input type="text" class="form-control" value="{{ $tipo_mantenimiento->TIP_NOMBRE }}" id="TIP_NOMBRE" name="TIP_NOMBRE" required>
                        <div class="invalid-feedback">Completa los datos</div>
                    </div>
                    <div class="col-md-12">
                        <label for="TIP_DESCRIPCION" class="form-label">Descripción</label>
                        <textarea class="form-control" value="" name="TIP_DESCRIPCION" id="TIP_DESCRIPCION" cols="20" rows="5" >{{ $tipo_mantenimiento->TIP_DESCRIPCION }}</textarea>
                        <div class="invalid-feedback">Completa los datos</div>
                        <br>
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
