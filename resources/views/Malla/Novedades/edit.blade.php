@extends('layouts.main')

@section('main')

    <!-- Bread crumb and right sidebar toggle -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor mb-0 mt-0">Editar Novedad #{{ $novedad->NOV_ID }}</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('Novedades.index') }}">Novedades</a></li>
                <li class="breadcrumb-item"><a href="{{ route('Novedades.show', $novedad->NOV_ID) }}">Detalle</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
        <div class="col-md-6 col-4 align-self-center">
            <div class="d-flex justify-content-end">
                <a href="{{ route('Novedades.show', $novedad->NOV_ID) }}" class="btn btn-info btn-sm mr-2">
                    <i class="mdi mdi-eye"></i> Ver Detalle
                </a>
                <a href="{{ route('Novedades.index') }}" class="btn btn-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    @if ($novedad->NOV_ESTADO_APROBACION === 'aprobada')
                        <div class="alert alert-danger border-left-danger">
                            <i class="mdi mdi-block-helper me-2"></i>
                            <strong>Error:</strong> Esta novedad ya ha sido aprobada y no puede editarse.
                        </div>
                    @elseif ($novedad->NOV_ESTADO_APROBACION === 'rechazada')
                        <div class="alert alert-info border-left-info">
                            <i class="mdi mdi-information-outline me-2"></i>
                            <strong>Reenvío de novedad:</strong> Esta novedad fue rechazada anteriormente.
                            Puede editarla y volver a enviarla para aprobación.
                            @if ($novedad->NOV_OBSERVACIONES)
                                <br><strong>Motivo del rechazo:</strong> {{ $novedad->NOV_OBSERVACIONES }}
                             @endif
                        </div>
                    @elseif (in_array($novedad->NOV_ESTADO_APROBACION, ['pendiente', 'rechazada']))
                        <form method="POST" action="{{ route('Novedades.update', $novedad->NOV_ID) }}"
                            enctype="multipart/form-data" id="novedadForm">
                            @csrf
                            @method('PUT')

                            <!-- Sección 1: Información Principal -->
                            <div class="form-section mb-4">
                                <div class="section-header mb-3">
                                    <h5 class="section-title">
                                        <i class="mdi mdi-account-circle text-primary me-2"></i>
                                        Información Principal
                                    </h5>
                                    <hr class="section-divider">
                                </div>

                                <div class="row">
                                    <!-- Empleado -->
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="EMP_ID" class="form-label required">
                                                <i class="mdi mdi-account me-1"></i>Empleado
                                            </label>
                                            <select name="EMP_ID" id="EMP_ID"
                                                class="form-control form-control-lg @error('EMP_ID') is-invalid @enderror"
                                                required>
                                                <option value="">-- Seleccione el empleado --</option>
                                                @foreach ($empleados as $empleado)
                                                    <option value="{{ $empleado->EMP_ID }}"
                                                        {{ (old('EMP_ID') ?? $novedad->EMP_ID) == $empleado->EMP_ID ? 'selected' : '' }}>
                                                        {{ $empleado->EMP_NOMBRES }} {{ $empleado->EMP_APELLIDOS ?? '' }}
                                                        - {{ $empleado->EMP_CEDULA }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('EMP_ID')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tipo de Novedad -->
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="TIN_ID" class="form-label required">
                                                <i class="mdi mdi-format-list-bulleted-type me-1"></i>Tipo de Novedad
                                            </label>
                                            <select name="TIN_ID" id="TIN_ID"
                                                class="form-control form-control-lg @error('TIN_ID') is-invalid @enderror"
                                                required>
                                                <option value="">-- Seleccione el tipo --</option>
                                                @foreach ($tiposNovedades as $tipo)
                                                    <option value="{{ $tipo->TIN_ID }}"
                                                        {{ (old('TIN_ID') ?? $novedad->TIN_ID) == $tipo->TIN_ID ? 'selected' : '' }}>
                                                        {{ $tipo->TIN_NOMBRE }} ({{ $tipo->tipo_texto }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('TIN_ID')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="NOV_DESCRIPCION" class="form-label required">
                                                <i class="mdi mdi-text-long me-1"></i>Descripción de la Novedad
                                            </label>
                                            <textarea name="NOV_DESCRIPCION" id="NOV_DESCRIPCION" rows="4"
                                                class="form-control form-control-lg @error('NOV_DESCRIPCION') is-invalid @enderror"
                                                placeholder="Describa detalladamente la novedad..." required>{{ old('NOV_DESCRIPCION') ?? $novedad->NOV_DESCRIPCION }}</textarea>
                                            <div class="form-text">
                                                <i class="mdi mdi-information-outline"></i>
                                                Proporcione todos los detalles relevantes sobre la novedad
                                            </div>
                                            @error('NOV_DESCRIPCION')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Horarios del empleado -->
                            <div class="form-section mb-4" id="horarios-section">
                                <div class="section-header mb-3">
                                    <h5 class="section-title">
                                        <i class="mdi mdi-calendar-multiple-check text-primary me-2"></i>
                                        Horarios del empleado
                                    </h5>
                                    <hr class="section-divider">
                                </div>

                                @error('horarios')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div id="horarios-message" class="alert alert-info">
                                    Selecciona un empleado y, opcionalmente, ajusta la fecha para filtrar los horarios
                                    disponibles.
                                </div>

                                <div class="row align-items-end mb-3">
                                    <div class="col-lg-3 col-md-6">
                                        <label for="schedule-date-start" class="form-label">
                                            <i class="mdi mdi-calendar-start me-1"></i>Fecha desde
                                        </label>
                                        <input type="date" id="schedule-date-start" class="form-control"
                                            value="{{ old('NOV_FECHA_INICIO', \Carbon\Carbon::now('America/Bogota')->toDateString()) }}">
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <label for="schedule-date-end" class="form-label">
                                            <i class="mdi mdi-calendar-end me-1"></i>Fecha hasta
                                        </label>
                                        <input type="date" id="schedule-date-end" class="form-control"
                                            value="{{ old('NOV_FECHA_FIN', \Carbon\Carbon::now('America/Bogota')->toDateString()) }}">
                                    </div>
                                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            id="schedule-date-apply">
                                            <i class="mdi mdi-magnify me-1"></i> Filtrar rango
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                                        <div class="schedule-actions">
                                            <button type="button" class="btn btn-outline-success btn-sm action-btn"
                                                id="select-all-schedules">
                                                <i class="mdi mdi-checkbox-multiple-marked-outline me-1"></i>
                                                <span>Seleccionar todo</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm action-btn"
                                                id="clear-all-schedules">
                                                <i class="mdi mdi-close-box-outline me-1"></i>
                                                <span>Limpiar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive d-none" id="horarios-table-wrapper">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center" style="width: 60px;">Seleccionar</th>
                                                <th>Fecha</th>
                                                <th>Hora inicio</th>
                                                <th>Hora fin</th>
                                                <th>Cliente</th>
                                                <th>Campaña</th>
                                                <th class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="horarios-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Sección 3: Documentos -->
                            <div class="form-section mb-4 mt-4">
                                <div class="section-header mb-3">
                                    <h5 class="section-title">
                                        <i class="mdi mdi-paperclip text-primary me-2"></i>
                                        Documentos de Soporte
                                    </h5>
                                    <hr class="section-divider">
                                </div>

                                <!-- Archivos actuales -->
                                @php
                                    $archivos_actuales = $novedad->archivos_lista ?? [];
                                @endphp

                                @if ($archivos_actuales && count($archivos_actuales) > 0)
                                    <div class="form-group mb-4">
                                        <label class="form-label">
                                            <i class="mdi mdi-file-check me-1"></i>Archivos Actuales
                                        </label>
                                        <div class="files-current">
                                            @foreach ($archivos_actuales as $index => $archivo)
                                                <div class="file-item-edit">
                                                    <div class="file-icon">
                                                        <i class="mdi mdi-file-document text-primary"></i>
                                                    </div>
                                                    <div class="file-info">
                                                        <div class="file-name">
                                                            {{ $archivo['nombre_original'] ?? 'archivo_' . $index }}</div>
                                                        <div class="file-size">
                                                            {{ number_format(($archivo['size'] ?? 0) / 1024, 1) }} KB</div>
                                                    </div>
                                                    <div class="file-actions">
                                                        <a href="{{ route('Novedades.verArchivo', [$novedad->NOV_ID, $index]) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary me-1"
                                                            title="Ver archivo">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="eliminarArchivo({{ $novedad->NOV_ID }}, {{ $index }})"
                                                            title="Eliminar archivo">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Nuevos archivos adjuntos -->
                                <div class="form-group">
                                    <label for="archivos" class="form-label">
                                        <i class="mdi mdi-file-plus me-1"></i>Nuevos Archivos Adjuntos
                                        <span class="badge badge-secondary ms-1">Opcional</span>
                                    </label>

                                    <div class="upload-area">
                                        <div class="upload-content">
                                            <i class="mdi mdi-cloud-upload upload-icon"></i>
                                            <h6 class="upload-title">Seleccionar Archivos</h6>
                                            <p class="upload-subtitle">PDF, Imágenes, Word • Máximo 5MB por archivo</p>
                                            <input type="file" name="archivos[]" id="archivos"
                                                class="upload-input @error('archivos.*') is-invalid @enderror" multiple
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        </div>
                                    </div>

                                    @error('archivos.*')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror

                                    <div id="file-preview" class="file-preview mt-3"></div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="form-actions">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('Novedades.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="mdi mdi-arrow-left me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="mdi mdi-content-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // ===== MANEJO DE HORARIOS =====
        const horariosSection = document.getElementById('horarios-section');
        if (horariosSection) {
            const empleadosSelect = document.getElementById('EMP_ID');
            const fechaInicioInput = document.getElementById('schedule-date-start');
            const fechaFinInput = document.getElementById('schedule-date-end');
            const aplicarFiltroBtn = document.getElementById('schedule-date-apply');
            const selectAllBtn = document.getElementById('select-all-schedules');
            const clearAllBtn = document.getElementById('clear-all-schedules');
            const horariosMessage = document.getElementById('horarios-message');
            const horariosWrapper = document.getElementById('horarios-table-wrapper');
            const horariosBody = document.getElementById('horarios-body');
            const horariosEndpointTemplate = "{{ route('Novedades.horariosEmpleado', ['empleado' => '__ID__']) }}";
            let isLoadingHorarios = false;
            const selectedHorarios = new Set(@json(array_map('strval', old('horarios', []))));

            function snapshotCurrentSelection() {
                const marcados = document.querySelectorAll('input[name=\"horarios[]\"]:checked');
                if (marcados.length > 0) {
                    selectedHorarios.clear();
                    marcados.forEach((checkbox) => selectedHorarios.add(checkbox.value));
                }
            }

            async function loadHorarios() {
                if (isLoadingHorarios) {
                    return;
                }

                const empleadoId = empleadosSelect.value;
                const fechaInicio = fechaInicioInput?.value;
                const fechaFin = fechaFinInput?.value;

                snapshotCurrentSelection();

                horariosBody.innerHTML = '';
                horariosWrapper.classList.add('d-none');
                horariosMessage.classList.remove('alert-danger', 'alert-success');
                horariosMessage.classList.add('alert-info');

                if (!empleadoId) {
                    horariosMessage.textContent = 'Selecciona un empleado para ver sus horarios disponibles.';
                    return;
                }

                if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                    horariosMessage.textContent = 'La fecha inicial no puede ser mayor que la fecha final.';
                    horariosMessage.classList.remove('alert-info');
                    horariosMessage.classList.add('alert-danger');
                    return;
                }

                horariosMessage.textContent = 'Cargando horarios disponibles...';
                isLoadingHorarios = true;

                try {
                    const endpoint = horariosEndpointTemplate.replace('__ID__', encodeURIComponent(empleadoId));
                    const params = new URLSearchParams();
                    if (fechaInicio) {
                        params.append('fecha_inicio', fechaInicio);
                    }
                    if (fechaFin) {
                        params.append('fecha_fin', fechaFin);
                    }
                    const url = `${endpoint}${params.toString() ? `?${params.toString()}` : ''}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('No se pudo obtener la información de horarios');
                    }

                    const payload = await response.json();
                    const horarios = Array.isArray(payload.data) ? payload.data : [];
                    renderHorarios(horarios);
                } catch (error) {
                    console.error(error);
                    horariosMessage.textContent = 'No fue posible cargar los horarios del empleado.';
                    horariosMessage.classList.remove('alert-info');
                    horariosMessage.classList.add('alert-danger');
                } finally {
                    isLoadingHorarios = false;
                }
            }

            function renderHorarios(horarios) {
                if (!horarios.length) {
                    horariosMessage.textContent = 'No se encontraron horarios para la combinación seleccionada.';
                    horariosWrapper.classList.add('d-none');
                    return;
                }

                horariosMessage.textContent = 'Selecciona los horarios que deseas asociar a la novedad.';
                horariosMessage.classList.remove('alert-danger', 'alert-success');
                horariosMessage.classList.add('alert-info');
                horariosWrapper.classList.remove('d-none');

                const idsPresentes = new Set();
                horarios.forEach((horario) => {
                    idsPresentes.add(String(horario.id ?? horario.MAL_ID));
                });
                Array.from(selectedHorarios).forEach((id) => {
                    if (!idsPresentes.has(id)) {
                        selectedHorarios.delete(id);
                    }
                });

                horariosBody.innerHTML = horarios.map((horario) => {
                    const id = String(horario.id ?? horario.MAL_ID);
                    const checked = selectedHorarios.has(id) ? 'checked' : '';
                    const estado = horario.estado ?? horario.MAL_ESTADO ?? 0;
                    const estadoTexto = estado === 1 ? 'Activo' : 'Inactivo';
                    const estadoBadge = estado === 1 ? 'success' : 'danger';
                    const fecha = horario.fecha_formateada ?? horario.fecha ?? horario.MAL_DIA ?? 'N/A';
                    const inicio = horario.hora_inicio_formateada ?? horario.hora_inicio ?? horario.MAL_INICIO ??
                        'N/A';
                    const fin = horario.hora_fin_formateada ?? horario.hora_fin ?? horario.MAL_FINAL ?? 'N/A';
                    const cliente = horario.cliente ?? 'N/A';
                    const campana = horario.campana ?? 'N/A';

                    return `
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input" name="horarios[]" value="${id}" ${checked}>
                    </td>
                    <td>${fecha}</td>
                    <td>${inicio}</td>
                    <td>${fin}</td>
                    <td>${cliente}</td>
                    <td>${campana}</td>
                    <td class="text-center">
                        <span class="badge badge-${estadoBadge}">${estadoTexto}</span>
                    </td>
                </tr>
            `;
                }).join('');
            }

            horariosBody?.addEventListener('change', (event) => {
                if (event.target && event.target.matches('input[name="horarios[]"]')) {
                    if (event.target.checked) {
                        selectedHorarios.add(event.target.value);
                    } else {
                        selectedHorarios.delete(event.target.value);
                    }
                }
            });

            empleadosSelect?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            aplicarFiltroBtn?.addEventListener('click', () => {
                loadHorarios();
            });

            fechaInicioInput?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            fechaFinInput?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            selectAllBtn?.addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('#horarios-body input[name=\"horarios[]\"]');
                if (!checkboxes.length) {
                    horariosMessage.textContent = 'No hay horarios cargados para seleccionar.';
                    horariosMessage.classList.remove('alert-danger');
                    horariosMessage.classList.add('alert-info');
                    return;
                }

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = true;
                    selectedHorarios.add(checkbox.value);
                });

                horariosMessage.textContent = 'Todos los horarios visibles fueron seleccionados.';
                horariosMessage.classList.remove('alert-danger');
                horariosMessage.classList.add('alert-success');
            });

            clearAllBtn?.addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('#horarios-body input[name=\"horarios[]\"]');
                if (!checkboxes.length) {
                    horariosMessage.textContent = 'No hay horarios cargados para limpiar.';
                    horariosMessage.classList.remove('alert-danger');
                    horariosMessage.classList.add('alert-info');
                    return;
                }

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                selectedHorarios.clear();

                horariosMessage.textContent = 'Se limpiaron las selecciones de horarios.';
                horariosMessage.classList.remove('alert-danger');
                horariosMessage.classList.add('alert-info');
            });

            if (empleadosSelect?.value) {
                loadHorarios();
            }
        }

        // ===== PREVIEW DE ARCHIVOS =====
        document.getElementById('archivos').addEventListener('change', function() {
            const files = Array.from(this.files);
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            const maxSize = 5 * 1024 * 1024; // 5MB

            files.forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert(`El archivo ${file.name} no es un tipo válido`);
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert(`El archivo ${file.name} es demasiado grande (máximo 5MB)`);
                    this.value = '';
                    return;
                }
            });
        });

        // Función para eliminar archivo
        function eliminarArchivo(novedadId, indiceArchivo) {
            if (confirm('¿Está seguro de que desea eliminar este archivo?')) {
                fetch(`/Novedades/${novedadId}/archivo/${indiceArchivo}/eliminar`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.success);
                            } else {
                                alert(data.success);
                            }
                            location.reload();
                        } else if (data.error) {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(data.error);
                            } else {
                                alert(data.error);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Error al eliminar el archivo');
                        } else {
                            alert('Error al eliminar el archivo');
                        }
                    });
            }
        }
    </script>
@endsection

@section('styles')
    <style>
        /* ===== ESTILOS PARA FORMULARIO DE EDICIÓN DE NOVEDADES ===== */

        /* Card principal */
        .card.shadow-lg {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        /* Secciones del formulario */
        .form-section {
            margin-bottom: 2rem;
        }

        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        .section-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, #007bff, transparent);
            margin: 0;
        }

        /* Labels mejorados */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .form-label.required::after {
            content: '*';
            color: #dc3545;
            margin-left: 0.25rem;
            font-weight: bold;
        }

        .form-label i {
            color: #6c757d;
        }

        /* Form controls mejorados */
        .form-control,
        .form-control-lg {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-control-lg:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transform: translateY(-1px);
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        /* Upload area */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
            position: relative;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .upload-content {
            pointer-events: none;
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
            display: block;
        }

        .upload-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .upload-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        .upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Files current */
        .files-current {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .file-item-edit {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #fafafa;
        }

        .file-item-edit .file-icon {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
            flex-shrink: 0;
        }

        .file-item-edit .file-info {
            flex: 1;
        }

        .file-item-edit .file-name {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .file-item-edit .file-size {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .file-item-edit .file-actions {
            display: flex;
            gap: 0.25rem;
        }

        /* File preview */
        .file-preview {
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        /* File upload wrapper (create style) */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-wrapper input[type="file"] {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            min-height: 120px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .file-upload-wrapper input[type="file"]:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .file-upload-wrapper::before {
            content: '📎 Arrastra archivos aquí o haz clic para seleccionar';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
            pointer-events: none;
            z-index: 1;
            transition: opacity 0.3s ease;
        }

        .file-upload-wrapper.has-files::before {
            opacity: 0;
        }

        /* Estilos para la lista de archivos */
        #file-list .alert {
            margin-top: 1rem;
            border: 1px solid #bee5eb;
            background-color: #d1ecf1;
        }

        #file-list ul {
            list-style: none;
            padding-left: 0;
        }

        #file-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        #file-list li:last-child {
            border-bottom: none;
        }

        /* Botones de acción */
        .form-actions {
            margin-top: 2rem;
        }

        /* Action buttons */
        .action-buttons .btn-lg {
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        /* Border alerts */
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        /* Badges para campos opcionales */
        .badge.badge-secondary {
            background-color: #6c757d;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Form text mejorado */
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-text i {
            margin-right: 0.5rem;
        }

        /* Estilos para tabla de horarios */
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .thead-light th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Botones de acción para horarios */
        .schedule-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-width: 1.5px;
            padding: 0.5rem 0.75rem;
            min-width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .action-btn i {
            font-size: 0.875rem;
        }

        .action-btn span {
            font-size: 0.875rem;
        }

        .btn-outline-success.action-btn:hover {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-outline-secondary.action-btn:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Utility classes */
        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .me-3 {
            margin-right: 1rem !important;
        }

        .ms-1 {
            margin-left: 0.25rem !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-buttons .btn-lg {
                width: 100%;
            }

            .schedule-actions {
                justify-content: center;
                flex-direction: column;
            }

            .action-btn {
                min-width: auto;
            }
        }
    </style>
@endsection
