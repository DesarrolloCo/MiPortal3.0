@extends('layouts.main')

@section('main')
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-chart-bar"></i> {{ $informe->INF_NOMBRE }}
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Informe.index') }}">Informes</a></li>
                            <li class="breadcrumb-item active">{{ $informe->INF_NOMBRE }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <a href="{{ route('Informe.index') }}" class="btn btn-secondary float-right hidden-sm-down">
                            <i class="mdi mdi-arrow-left"></i> Volver a Informes
                        </a>
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
                            <div class="card-body p-2">
                                <!-- Información del dashboard -->
                                <div class="row mb-2 px-3 py-2 bg-light">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="mdi mdi-folder-outline"></i> Proyecto:
                                            <strong>{{ $informe->campana->CAM_NOMBRE ?? 'N/A' }}</strong>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small class="text-muted">
                                            <i class="mdi mdi-link-variant"></i>
                                            <a href="{{ $informe->INF_URL }}" target="_blank" rel="noopener noreferrer">
                                                Abrir en nueva ventana <i class="mdi mdi-open-in-new"></i>
                                            </a>
                                        </small>
                                    </div>
                                </div>

                                <!-- Dashboard iframe -->
                                <div class="embed-responsive" style="height: 700px;">
                                    <iframe
                                        title="{{ $informe->INF_NOMBRE }}"
                                        class="embed-responsive-item"
                                        src="{{ $informe->INF_URL }}"
                                        frameborder="0"
                                        allowfullscreen="true"
                                        style="width: 100%; height: 100%; border: none;">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
@endsection
