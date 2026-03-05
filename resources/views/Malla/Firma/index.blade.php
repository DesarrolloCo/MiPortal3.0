@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Gestionar firma</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gestionar firma</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Edit_text"><i class="mdi mdi-plus-circle"></i> Texto</button>
                        <div class="dropdown float-right mr-2 hidden-sm-down">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Edit_img"><i class="fas fa-sync"></i></i>   Firma</button>
                    </div>
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
                                <div class="card mb-4">
                                    <div class="card-body container" style="text-align: center;">
                                        @if ($firma_foto[0]->PAR_VALOR == null)
                                            <img src="{{ asset('img/sin_firma.jpg') }}" width="600px" height="400px">
                                        @else
                                            <img src="{{ asset($firma_foto[0]->PAR_VALOR) }}" width="600px" height="400px">
                                        @endif
                                        @if ($firma_texto[0]->PAR_VALOR == null)
                                            <h4>Director General</h4>
                                        @else
                                            <h4>{{ $firma_texto[0]->PAR_VALOR }}</h4>
                                        @endif
                                    </div>
                                </div>
                                <!-- column -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
                @include('Malla.Firma.change_text')
                @include('Malla.Firma.change_img')
@endsection
