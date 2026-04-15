@extends('layouts.main')

@section('styles')
@if($esCumpleanos)
<style>
    /* Overlay de cumpleaños */
    #birthday-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Tarjeta de felicitación */
    .birthday-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 50px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 10000;
        max-width: 500px;
        animation: slideDown 0.8s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .birthday-card h1 {
        color: #fff;
        font-size: 48px;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .birthday-card p {
        color: #fff;
        font-size: 20px;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .birthday-card .emoji {
        font-size: 60px;
        margin: 20px 0;
        display: inline-block;
        animation: rotate 3s ease-in-out infinite;
    }

    @keyframes rotate {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    .close-birthday {
        background: #fff;
        color: #667eea;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .close-birthday:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Globos flotantes */
    .balloon {
        position: absolute;
        width: 80px;
        height: 100px;
        border-radius: 50%;
        bottom: -150px;
        animation: float 6s ease-in infinite;
    }

    .balloon:before {
        content: "";
        position: absolute;
        width: 2px;
        height: 100px;
        background: rgba(255, 255, 255, 0.3);
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
    }

    @keyframes float {
        0% {
            bottom: -150px;
            opacity: 1;
        }
        100% {
            bottom: 120%;
            opacity: 0;
        }
    }

    .balloon-1 {
        background: radial-gradient(circle at 30% 30%, #ff6b6b, #c92a2a);
        left: 10%;
        animation-delay: 0s;
        animation-duration: 8s;
    }

    .balloon-2 {
        background: radial-gradient(circle at 30% 30%, #51cf66, #2b8a3e);
        left: 25%;
        animation-delay: 1s;
        animation-duration: 7s;
    }

    .balloon-3 {
        background: radial-gradient(circle at 30% 30%, #339af0, #1864ab);
        left: 40%;
        animation-delay: 2s;
        animation-duration: 9s;
    }

    .balloon-4 {
        background: radial-gradient(circle at 30% 30%, #ffd43b, #e67700);
        left: 55%;
        animation-delay: 1.5s;
        animation-duration: 7.5s;
    }

    .balloon-5 {
        background: radial-gradient(circle at 30% 30%, #da77f2, #9c36b5);
        left: 70%;
        animation-delay: 0.5s;
        animation-duration: 8.5s;
    }

    .balloon-6 {
        background: radial-gradient(circle at 30% 30%, #ff8787, #fa5252);
        left: 85%;
        animation-delay: 2.5s;
        animation-duration: 6.5s;
    }

    /* Confetti */
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #f39c12;
        animation: confetti-fall 5s ease-in infinite;
    }

    @keyframes confetti-fall {
        0% {
            top: -10%;
            opacity: 1;
        }
        100% {
            top: 110%;
            opacity: 0;
            transform: rotate(720deg);
        }
    }
</style>
@endif
@endsection

@section('main')

@if($esCumpleanos)
<!-- Overlay de Feliz Cumpleaños -->
<div id="birthday-overlay">
    <!-- Globos flotantes -->
    <div class="balloon balloon-1"></div>
    <div class="balloon balloon-2"></div>
    <div class="balloon balloon-3"></div>
    <div class="balloon balloon-4"></div>
    <div class="balloon balloon-5"></div>
    <div class="balloon balloon-6"></div>

    <!-- Tarjeta de felicitación -->
    <div class="birthday-card">
        <div class="emoji">🎉</div>
        <h1>¡Feliz Cumpleaños!</h1>
        <p>{{ Auth::user()->name }}</p>
        <p style="font-size: 16px;">
            Que este día esté lleno de alegría, bendiciones y momentos especiales.<br>
            ¡Todo el equipo te desea un feliz cumpleaños!
        </p>
        <div class="emoji">🎂🎈🎁</div>
        <br><br>
        <button class="close-birthday" onclick="closeBirthdayOverlay()">
            ¡Gracias! 😊
        </button>
    </div>
</div>
@endif

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
        <!-- Widget 1: Cumpleaños de {{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} y {{ \Carbon\Carbon::now()->addMonth()->locale('es')->isoFormat('MMMM') }} del {{ \Carbon\Carbon::now()->year }} -->
        <div class="col-lg-4 col-md-6">
            @include('extranet.widgets.cumpleanos')
        </div>

        <!-- Widget 2: Aniversarios Laborales de {{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} y {{ \Carbon\Carbon::now()->addMonth()->locale('es')->isoFormat('MMMM') }} del {{ \Carbon\Carbon::now()->year }} -->
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

    <!-- Widgets de Módulos Nuevos: Encuestas y Reconocimientos -->
    <div class="row">
        @can('ver-encuestas')
        <div class="col-lg-6">
            @include('extranet.widgets.encuestas-pendientes')
        </div>
        @endcan

        @can('ver-reconocimientos')
        <div class="col-lg-6">
            @include('extranet.widgets.reconocimientos-recientes')
        </div>
        @endcan
    </div>

    <!-- Widgets de Documentos y Muro Social -->
    <div class="row">
        @can('ver-documentos')
        <div class="col-lg-6">
            @include('extranet.widgets.documentos-destacados')
        </div>
        @endcan

        @can('ver-muro')
        <div class="col-lg-6">
            @include('extranet.widgets.muro-social')
        </div>
        @endcan
    </div>

    <!-- Widget de Galería Reciente -->
    @can('ver-galeria')
    <div class="row">
        <div class="col-lg-12">
            @include('extranet.widgets.galeria-reciente')
        </div>
    </div>
    @endcan
</div>
@endsection

@section('scripts')
@if($esCumpleanos)
<script>
    function closeBirthdayOverlay() {
        const overlay = document.getElementById('birthday-overlay');
        overlay.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 500);
    }

    // Agregar animación de fade out
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Crear confetti dinámicamente
    function createConfetti() {
        const colors = ['#f39c12', '#e74c3c', '#3498db', '#2ecc71', '#9b59b6', '#1abc9c'];
        const overlay = document.getElementById('birthday-overlay');

        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.animationDuration = (Math.random() * 3 + 4) + 's';
            overlay.appendChild(confetti);
        }
    }

    // Ejecutar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        createConfetti();

        // Auto cerrar después de 10 segundos si el usuario no lo hace
        setTimeout(() => {
            const overlay = document.getElementById('birthday-overlay');
            if (overlay && overlay.style.display !== 'none') {
                closeBirthdayOverlay();
            }
        }, 10000);
    });
</script>
@endif
@endsection
