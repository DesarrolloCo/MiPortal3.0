@extends('layouts.main')
{{--
@section('iframe_responsive')
    <style>
    .container_iframe {
    position: relative;
    overflow: hidden;
    width: 100%;
    padding-top: 56.25%; /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
    }

    /* Then style the iframe to fit in the container div with full height and width */
    .responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    }
    </style>
@endsection --}}

@section('main')
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Informes (  @if(isset($informe[0]->INF_NOMBRE)) {{ $informe[0]->INF_NOMBRE }} @else  No hay ningún empleado asignado @endif )</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Informe.index') }}">Informes</a></li>
                            <li class="breadcrumb-item active">{{ $informe[0]->INF_NOMBRE }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        {{--<button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Informe"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                         <button class="right-side-toggle waves-effect waves-light btn-info btn-circle btn-sm float-right ml-2"><i class="ti-settings text-white"></i></button>
                        <button class="btn float-right hidden-sm-down btn-success"><i class="mdi mdi-plus-circle"></i> Create</button>
                        <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">


                                <iframe title="{{ $informe[0]->INF_NOMBRE }}" width="100%" height="700" src="{{ $informe[0]->INF_URL }}" frameborder="0" allowfullscreen="true"></iframe>


                            </div>
                        </div>
                    </div>
                </div>
@endsection


