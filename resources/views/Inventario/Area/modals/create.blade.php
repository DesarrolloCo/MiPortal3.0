{{-- Modal optimizado para crear área usando componentes reutilizables --}}

<x-modals.crud-modal
    modal-id="Add_Areas"
    title="Agregar área"
    action="{{ route('Area.create') }}"
    method="POST"
    size=""
    submit-text="Guardar"
    submit-class="btn-success"
    cancel-text="Cerrar">

    <x-forms.text-field
        name="ARE_NOMBRE"
        label="Nombre"
        placeholder="Ingrese el nombre del área"
        required
        help-text="Nombre descriptivo del área" />

    <x-forms.textarea-field
        name="ARE_DESCRIPCION"
        label="Observaciones"
        placeholder="Ingrese observaciones sobre el área"
        rows="3"
        help-text="Descripción adicional del área (opcional)" />

</x-modals.crud-modal>