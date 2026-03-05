<!-- .modal for add task -->
<div class="modal fade" id="Add_Equ_asignado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar asignacion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Asignacion_equipo.create') }}" method="POST"novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filtrar</label>
                                <input type="text" id="search-input-empleado"  placeholder="Escribe para buscar..." oninput="filterEmpleado()" class="form-control" />
                                <label>Empleado</label>
                                <select name="EMP_ID" id="select-box-empleado" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($exc_emp as $list_emp)
                                    <option value="{{ $list_emp->EMP_ID }}">{{ $list_emp->EMP_NOMBRES }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Filtrar</label>
                                <input type="text" id="search-input-equipo"  placeholder="Escribe para buscar..." oninput="filterEquipo()" class="form-control" />
                                <label>Equipo</label>
                                <select name="EQU_ID" id="select-box-equipo" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($exc_equ as $list_equ)
                                    <option value="{{ $list_equ->EQU_ID }}">{{ $list_equ->EQU_SERIAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Fecha de entrega</label>
                        <input type="date" name="EAS_FECHA_ENTREGA" class="form-control" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    function filterEmpleado() {
      const input = document.getElementById("search-input-empleado").value.toLowerCase();
      const select = document.getElementById("select-box-empleado");
      const options = select.options;

      for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const text = option.text.toLowerCase();
        if (text.includes(input)) {
          option.style.display = "";
        } else {
          option.style.display = "none";
        }
      }
    }
    
    function filterEquipo() {
      const input = document.getElementById("search-input-equipo").value.toLowerCase();
      const select = document.getElementById("select-box-equipo");
      const options = select.options;

      for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const text = option.text.toLowerCase();
        if (text.includes(input)) {
          option.style.display = "";
        } else {
          option.style.display = "none";
        }
      }
    }
  </script>
