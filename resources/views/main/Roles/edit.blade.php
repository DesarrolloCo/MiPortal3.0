{{-- @php
    $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $Roles->id)
    ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
    ->all();
@endphp --}}

<!-- .modal for add task -->
<div class="modal fade" id="Edit_Rol{{ $Roles->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::model($Roles, ['method' =>'PATCH','route' =>['Roles.update', $Roles->id]]) !!}
                    <div class="form-group">
                        <label>Tipo de mantenimiento</label>
                        {!! Form::text('name', $Rol->name, array('class' => 'form-control')) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Permisos</label>
                                @foreach ($Permissions as $Permission )
                                    <div class="custom-control custom-checkbox">
                                        {!! Form::checkbox('permission[]', $Permission->id, in_array($Permission->id, $rolePermissions) ? true : false, array('class' => '')) !!}
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
