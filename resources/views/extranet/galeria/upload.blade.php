@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.galeria.index') }}">Galería</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.galeria.show', $galeria->id) }}">{{ $galeria->titulo }}</a></li>
                    <li class="breadcrumb-item active">Subir Fotos</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="mdi mdi-upload"></i> Subir Fotos a "{{ $galeria->titulo }}"</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('extranet.galeria.store-fotos', $galeria->id) }}" method="POST"
                          enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <!-- Zona de Drop -->
                        <div class="form-group">
                            <label>Seleccionar Fotos</label>
                            <div class="drop-zone" id="dropZone">
                                <div class="drop-zone-content">
                                    <i class="mdi mdi-cloud-upload mdi-72px text-muted"></i>
                                    <h5>Arrastra y suelta tus fotos aquí</h5>
                                    <p class="text-muted">o haz clic para seleccionar archivos</p>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        <i class="mdi mdi-folder-open"></i> Seleccionar Archivos
                                    </button>
                                </div>
                                <input type="file" id="fileInput" name="fotos[]" accept="image/*" multiple style="display: none;">
                            </div>
                            <small class="form-text text-muted">
                                Formatos aceptados: JPG, PNG, GIF. Tamaño máximo por archivo: 5MB.
                            </small>
                        </div>

                        <!-- Vista Previa de Fotos -->
                        <div id="previewContainer" class="row mt-4" style="display: none;">
                            <div class="col-12 mb-3">
                                <h5><i class="mdi mdi-image-multiple"></i> Vista Previa (<span id="photoCount">0</span> fotos)</h5>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                <i class="mdi mdi-upload"></i> Subir Fotos
                            </button>
                            <a href="{{ route('extranet.galeria.show', $galeria->id) }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Cancelar
                            </a>
                        </div>

                        <!-- Barra de Progreso -->
                        <div id="progressBar" class="progress" style="display: none; height: 30px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                 role="progressbar" style="width: 0%">0%</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.drop-zone {
    border: 3px dashed #ccc;
    border-radius: 10px;
    padding: 50px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background-color: #f8f9fa;
}

.drop-zone:hover, .drop-zone.dragover {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.drop-zone-content {
    pointer-events: none;
}

.preview-item {
    position: relative;
    margin-bottom: 15px;
}

.preview-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
}

.preview-item .remove-btn {
    position: absolute;
    top: 5px;
    right: 15px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.preview-item .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
    transform: scale(1.1);
}

.preview-item .descripcion-input {
    margin-top: 5px;
}
</style>

<script>
let selectedFiles = [];
const fileInput = document.getElementById('fileInput');
const dropZone = document.getElementById('dropZone');
const previewContainer = document.getElementById('previewContainer');
const photoCount = document.getElementById('photoCount');
const submitBtn = document.getElementById('submitBtn');

// Click en la zona de drop
dropZone.addEventListener('click', (e) => {
    if (e.target !== dropZone && !e.target.closest('.drop-zone-content')) return;
    fileInput.click();
});

// Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');

    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    handleFiles(files);
});

// Selección de archivos
fileInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    handleFiles(files);
});

function handleFiles(files) {
    // Agregar nuevos archivos
    selectedFiles = selectedFiles.concat(files);

    // Actualizar vista previa
    updatePreview();

    // Habilitar botón de submit
    submitBtn.disabled = selectedFiles.length === 0;
}

function updatePreview() {
    previewContainer.innerHTML = '<div class="col-12 mb-3"><h5><i class="mdi mdi-image-multiple"></i> Vista Previa (<span id="photoCount">' + selectedFiles.length + '</span> fotos)</h5></div>';
    previewContainer.style.display = 'flex';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            const col = document.createElement('div');
            col.className = 'col-lg-3 col-md-4 col-sm-6';
            col.innerHTML = `
                <div class="preview-item">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-btn" onclick="removeFile(${index})">
                        <i class="mdi mdi-close"></i>
                    </button>
                    <input type="text" class="form-control form-control-sm descripcion-input"
                           name="descripciones[]" placeholder="Descripción (opcional)">
                </div>
            `;
            previewContainer.appendChild(col);
        };

        reader.readAsDataURL(file);
    });

    photoCount.textContent = selectedFiles.length;
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updatePreview();
    submitBtn.disabled = selectedFiles.length === 0;

    // Actualizar fileInput
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
}

// Submit con progreso
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();

    // Agregar archivos
    selectedFiles.forEach((file, index) => {
        formData.append('fotos[]', file);
    });

    // Agregar descripciones
    const descripciones = document.querySelectorAll('.descripcion-input');
    descripciones.forEach(input => {
        formData.append('descripciones[]', input.value);
    });

    // Mostrar barra de progreso
    const progressBar = document.getElementById('progressBar');
    progressBar.style.display = 'block';
    submitBtn.disabled = true;

    // Upload con AJAX y progreso
    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            const progressBarInner = progressBar.querySelector('.progress-bar');
            progressBarInner.style.width = percentComplete + '%';
            progressBarInner.textContent = percentComplete + '%';
        }
    });

    xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
            window.location.href = '{{ route("extranet.galeria.show", $galeria->id) }}';
        } else {
            alert('Error al subir las fotos. Por favor, intenta de nuevo.');
            submitBtn.disabled = false;
            progressBar.style.display = 'none';
        }
    });

    xhr.addEventListener('error', () => {
        alert('Error de conexión. Por favor, intenta de nuevo.');
        submitBtn.disabled = false;
        progressBar.style.display = 'none';
    });

    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.send(formData);
});
</script>
@endsection
