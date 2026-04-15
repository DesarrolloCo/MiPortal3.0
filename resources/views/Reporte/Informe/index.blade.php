@extends('layouts.main')

@section('main')
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-chart-bar"></i> Informes y Dashboards
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Informes</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        @can('data_infome')
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Informe">
                            <i class="mdi mdi-plus-circle"></i> Nuevo Informe
                        </button>
                        @endcan
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Mensajes de alerta -->
                @if(session('rgcmessage'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-check-circle"></i> Éxito!</strong> {{ session('rgcmessage') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('msjdelete'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-alert-circle"></i> Eliminado!</strong> {{ session('msjdelete') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('msjupdate'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-check-circle"></i> Actualizado!</strong> {{ session('msjupdate') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Filtros de búsqueda -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('Informe.index') }}" class="form-inline">
                                            <div class="form-group mr-2 mb-2">
                                                <input type="text" class="form-control" name="buscar"
                                                       placeholder="Buscar por nombre o URL..."
                                                       value="{{ request('buscar') }}" style="width: 250px;">
                                            </div>

                                            <div class="form-group mr-2 mb-2">
                                                <select name="proyecto" class="form-control" style="width: 200px;">
                                                    <option value="">Todos los proyectos</option>
                                                    @foreach($campanas as $cam)
                                                        <option value="{{ $cam->CAM_ID }}"
                                                            {{ request('proyecto') == $cam->CAM_ID ? 'selected' : '' }}>
                                                            {{ $cam->CAM_NOMBRE }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @can('data_infome')
                                            <div class="form-group mr-2 mb-2">
                                                <select name="cliente" class="form-control" style="width: 200px;">
                                                    <option value="">Todos los clientes</option>
                                                    @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}"
                                                            {{ request('cliente') == $cliente->id ? 'selected' : '' }}>
                                                            {{ $cliente->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endcan

                                            <button type="submit" class="btn btn-primary mr-2 mb-2">
                                                <i class="mdi mdi-magnify"></i> Buscar
                                            </button>

                                            @if(request()->hasAny(['buscar', 'proyecto', 'cliente']))
                                                <a href="{{ route('Informe.index') }}" class="btn btn-secondary mb-2">
                                                    <i class="mdi mdi-refresh"></i> Limpiar
                                                </a>
                                            @endif
                                        </form>
                                    </div>
                                </div>

                                <!-- Tabla de informes -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="mdi mdi-folder-outline"></i> Proyecto</th>
                                                <th><i class="mdi mdi-chart-bar"></i> Nombre del Dashboard</th>
                                                <th><i class="mdi mdi-account"></i> Cliente</th>
                                                <th><i class="mdi mdi-link-variant"></i> URL</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($informes as $list)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $list->campana->CAM_NOMBRE ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $list->INF_NOMBRE }}</strong>
                                                    </td>
                                                    <td>
                                                        @if($list->cliente)
                                                            <span class="badge badge-info">
                                                                <i class="mdi mdi-account"></i> {{ $list->cliente->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted" title="{{ $list->INF_URL }}">
                                                            {{ Str::limit($list->INF_URL, 40) }}
                                                        </small>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('Informe.reportes', $list->INF_ID) }}"
                                                               class="btn btn-success btn-sm"
                                                               title="Ver dashboard">
                                                                <i class="fas fa-eye"></i>
                                                            </a>

                                                            @can('data_infome')
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                    title="Editar informe"
                                                                    data-toggle="modal"
                                                                    data-target="#EditCampana{{ $list->INF_ID }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <form action="{{ route('Informe.delete', $list->INF_ID) }}"
                                                                  method="POST"
                                                                  style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        title="Eliminar informe"
                                                                        onclick="return confirm('¿Seguro que desea eliminar este informe?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                                @include('Reporte.Informe.edit')
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-5">
                                                        <i class="mdi mdi-chart-bar-stacked" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No hay informes registrados</p>
                                                        @can('data_infome')
                                                        <button class="btn btn-success mt-2" data-toggle="modal" data-target="#Add_Informe">
                                                            <i class="mdi mdi-plus-circle"></i> Crear Primer Informe
                                                        </button>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                @if($informes->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            Mostrando {{ $informes->firstItem() ?? 0 }} a {{ $informes->lastItem() ?? 0 }}
                                            de {{ $informes->total() }} informes
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-right">
                                            {{ $informes->links() }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                @include('Reporte.Informe.create')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection
