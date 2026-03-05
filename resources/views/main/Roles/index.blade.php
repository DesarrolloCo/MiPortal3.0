@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Usuarios</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>

                        </ol>

                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_Roles" data-bs-target="#Add_Roles"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        {{-- <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- column -->

                                                <div class="table-responsive">
                                                    <table class="table no-wrap display responsive nowrap" id="roles">
                                                        <thead>
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Nombre de rol</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($Roles as $Rol)
                                                                    <tr>
                                                                    <td>{{ $Rol->id }}</td>
                                                                    <td>{{ $Rol->name }}</td>
                                                                    <td>
                                                                        @can('editar-rol')
                                                                        <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-bs-toggle="modal" data-target="#Edit_Rol{{ $Rol->id }}" data-bs-target="#Edit_Rol{{ $Rol->id }}">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        @endcan

                                                                        {{-- Modal de edición --}}

                                                                        @php
                                                                            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$Rol->id)
                                                                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                                                                ->all();
                                                                        @endphp

                                                                            <!-- .modal for add task -->
                                                                            <div class="modal fade" id="Edit_Rol{{ $Rol->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                           <h1 class="modal-title fs-5" id="exampleModalLabel">Editar permisos</h1>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            {!! Form::model($Roles, ['method' => 'PATCH','route' => ['Roles.update', $Rol->id]]) !!}
                                                                                            <div class="row">

                                                                                                <div class="form-group">
                                                                                                    <label>Nombre del Rol:</label>
                                                                                                    {!! Form::text('name', $Rol->name, array('class' => 'form-control')) !!}
                                                                                                </div>

                                                                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                                    <div class="form-group">
                                                                                                        <label for="">Permisos para este Rol:</label>
                                                                                                        <br/>
                                                                                                        @foreach($Permissions as $value)
                                                                                                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                                                                            {{ $value->name }}</label>
                                                                                                        <br/>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                            {!! Form::close() !!}
                                                                                        </div>

                                                                                    </div>
                                                                                    <!-- /.modal-content -->
                                                                                </div>
                                                                                <!-- /.modal-dialog -->
                                                                            </div>
                                                                            <!-- /.modal -->
                                                                        {{-- Modal de edición --}}

                                                                        @can('borrar-rol')
                                                                            <form action="{{ route('Roles.destroy', $Rol->id) }}" method="POST"
                                                                                style="display: inline-block; ">
                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button type="submit" class="btn btn-danger" rel="tooltip"
                                                                                    onclick="return confirm('Seguro que quiere eliminar este Rol?') ">
                                                                                    <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                                                </button>

                                                                            </form>
                                                                        @endcan
                                                                    </td>
                                                                </tr>
                                                               {{--  @include('main.Roles.edit') --}}
                                                            @endforeach

                                                            {{-- <tr>
                                                                <td><a href="javascript:void(0)">Order #26589</a></td>
                                                                <td>Herman Beck</td>
                                                                <td><span class="text-muted"><i class="far fa-clock"></i> Oct 16, 2019</span> </td>
                                                                <td>$45.00</td>
                                                                <td>
                                                                    <div class="label label-table label-success">Paid</div>
                                                                </td>
                                                                <td>EN</td>
                                                            </tr> --}}

                                                        </tbody>
                                                    </table>
                                                </div>
                                <!-- column -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->


                @include('main.Roles.create')

@endsection
