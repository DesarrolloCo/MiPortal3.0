<!-- .modal for add task -->
<div class="modal fade" id="Add_Roles" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(array('route' => 'Roles.store','method'=>'POST')) !!}
                    <div class="form-group">
                        <label>Tipo de mantenimiento</label>
                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Permisos</label>
                                @foreach ($Permissions as $Permission )
                                    <div class="custom-control custom-checkbox">
                                        {!! Form::checkbox('permissions[]', $Permission->id, false, array('class' => '')) !!}
                                        <label>{{ $Permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
