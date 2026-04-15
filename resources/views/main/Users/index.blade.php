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
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#AddtUser"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        {{-- <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Alertas de mensajes -->
                @if(session('rgcmessage'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-check-circle"></i> Éxito!</strong> {{ session('rgcmessage') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('msjdelete'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-alert"></i> Atención!</strong> {{ session('msjdelete') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-alert-circle"></i> Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- column -->

                                                <div class="table-responsive">
                                                    <table class="table no-wrap display responsive nowrap" id="users">
                                                        <thead>
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Nombre</th>
                                                                <th>Email</th>
                                                                <th>Rol</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($Users as $User)
                                                                <tr>
                                                                    <td>{{ $User->id }}</td>
                                                                    <td>{{ $User->name }}</td>
                                                                    <td>{{ $User->email }}</td>
                                                                    <td>
                                                                        @if(!empty($User->getRoleNames()))
                                                                            @foreach($User->getRoleNames() as $rolNombre)
                                                                            <span>{{ $rolNombre }}</span>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $User->id }}"><i class="fas fa-edit"></i></button>
                                                                        {{-- Eliminar usuarios --}}
                                                                <form action="{{ route('BorrarUser', $User->id) }}" method="POST"
                                                                    style="display: inline-block; ">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit" class="btn btn-danger" rel="tooltip"
                                                                        onclick="return confirm('Seguro que quiere eliminar este Usuario?') ">
                                                                        <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                                    </button>

                                                                </form>
                                                                    </td>
                                                                </tr>

                                                                {{-- Edit User --}}

                                                                <!-- .modal for add task -->
                                                                <div class="modal fade" id="editModal{{ $User->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Editar usuario</h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {!! Form::model($User, ['method' => 'PATCH','route' => ['UpdateUser', $User->id]]) !!}
                                                                                <div class="row">
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="name">Nombre</label>
                                                                                            {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="email">E-mail</label>
                                                                                            {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="password">Password</label>
                                                                                            {!! Form::password('password', array('class' => 'form-control')) !!}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="confirm-password">Confirmar Password</label>
                                                                                            {!! Form::password('confirm-password', array('class' => 'form-control')) !!}
                                                                                        </div>
                                                                                    </div>
                                                                                    @php
                                                                                         $userRole = $User->roles->pluck('name','name')->all();
                                                                                    @endphp
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Roles</label>
                                                                                            {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control')) !!}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                    </div>
                                                                                </div>
                                                                                {!! Form::close() !!}
                                                                            </div>

                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->



                                                            @endforeach

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


                @include('main.Users.create')

@endsection
