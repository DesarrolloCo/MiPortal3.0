@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Título de la página -->
    <div class="row page-titles">
        <div class="col-md-6 align-self-center">
            <h3 class="text-themecolor">Dashboard Extranet</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Extranet</li>
            </ol>
        </div>
    </div>

    <!-- Widget de Estadísticas Generales -->
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-account-multiple mdi-36px text-info"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Empleados</h6>
                            <h2 class="m-t-0">{{ $estadisticas['total_empleados'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-bullhorn mdi-36px text-primary"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Comunicados</h6>
                            <h2 class="m-t-0">{{ $estadisticas['comunicados_activos'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-calendar mdi-36px text-warning"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Eventos</h6>
                            <h2 class="m-t-0">{{ $estadisticas['eventos_proximos'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-briefcase mdi-36px text-success"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Proyectos</h6>
                            <h2 class="m-t-0">{{ $estadisticas['proyectos_activos'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-poll mdi-36px text-info"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Encuestas</h6>
                            <h2 class="m-t-0">{{ $estadisticas['encuestas_activas'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><i class="mdi mdi-trophy mdi-36px text-danger"></i></div>
                        <div class="align-self-center">
                            <h6 class="text-muted m-t-10 m-b-0">Reconocimientos</h6>
                            <h2 class="m-t-0">{{ $estadisticas['reconocimientos_mes'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de widgets principales -->
    <div class="row">
        <!-- Widget 1: Cumpleaños del Mes -->
        <div class="col-lg-4 col-md-6">
            @include('extranet.widgets.cumpleanos')
        </div>

        <!-- Widget 2: Aniversarios Laborales -->
        <div class="col-lg-4 col-md-6">
            @include('extranet.widgets.aniversarios')
        </div>

        <!-- Widget 3: Nuevos Empleados -->
        <div class="col-lg-4 col-md-6">
            @include('extranet.widgets.nuevos-empleados')
        </div>
    </div>

    <div class="row">
        <!-- Widget 4: Eventos Próximos -->
        <div class="col-lg-6">
            @include('extranet.widgets.eventos-proximos')
        </div>

        <!-- Widget 5: Proyectos Activos -->
        <div class="col-lg-6">
            @include('extranet.widgets.proyectos-activos')
        </div>
    </div>

    <!-- Comunicados Fijados -->
    @if($comunicadosFijados->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-pin"></i> Comunicados Importantes</h4>
                    @foreach($comunicadosFijados as $comunicado)
                    <div class="alert alert-{{ $comunicado->prioridad_color }} alert-dismissible fade show" role="alert">
                        <strong>{{ $comunicado->titulo }}</strong>
                        <p class="m-t-10">{!! Str::limit(strip_tags($comunicado->contenido), 200) !!}</p>
                        <small class="text-muted">{{ $comunicado->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
