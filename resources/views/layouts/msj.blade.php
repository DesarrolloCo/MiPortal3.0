<!---msjs de actualizar registro correctamente-->
@if(Session::has('msjupdate'))
<script>
    $.toast({
        heading: '',
        text: '{{ Session::get("msjupdate") }}',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'success',
        hideAfter: 3000,
        stack: 6
    });
    </script>
@endif

<!---msj de registrado correctamente -->
@if(Session::has('rgcmessage'))
<script>
    $.toast({
        heading: '',
        text: '{{ Session::get("rgcmessage") }}',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'success',
        hideAfter: 3000,
        stack: 6
    });
    </script>
@endif

@if(Session::has('msjdelete'))
<script>
    $.toast({
        heading: '',
        text: '{{ Session::get("msjdelete") }}',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'error',
        hideAfter: 3500,
        stack: 6
    });
    </script>
@endif

<!--msj de warning --->
@if (Session::has('reportewar'))
<script>
    $.toast({
        heading: '',
        text: '{{ Session::get('reportewar') }}',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'warning',
        hideAfter: 3000,
        stack: 6
    });
    </script>
@endif
