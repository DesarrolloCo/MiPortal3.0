<!DOCTYPE html>
<html>
<head>
    <title>Redirigiendo...</title>
</head>
<body>
    <div style="text-align: center; padding: 50px;">
        <p>Redirigiendo a los horarios...</p>
        <div class="spinner-border" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>

    <form id="redirectForm" method="POST" action="{{ route('Individual.edit') }}" style="display: none;">
        @csrf
        <input type="hidden" name="EMP_ID" value="{{ $empId }}">
        <input type="hidden" name="FECHA" value="{{ $fecha }}">
    </form>

    <script>
        // Auto-submit el formulario
        document.getElementById('redirectForm').submit();
    </script>
</body>
</html>