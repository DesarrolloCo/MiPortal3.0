{{-- Modal optimizado para editar área usando componentes reutilizables --}}

<x-modals.crud-modal
    modal-id="Edit_Area{{ $area->ARE_ID }}"
    title="Editar área"
    action="{{ route('Area.update', $area->ARE_ID) }}"
    method="PUT"
    size=""
    submit-text="Actualizar"
    submit-class="btn-primary"
    cancel-text="Cerrar">

    <x-forms.text-field
        name="ARE_NOMBRE"
        label="Nombre"
        value="{{ $area->ARE_NOMBRE }}"
        placeholder="Ingrese el nombre del área"
        required
        help-text="Nombre descriptivo del área" />

    <x-forms.textarea-field
        name="ARE_DESCRIPCION"
        label="Observaciones"
        value="{{ $area->ARE_DESCRIPCION }}"
        placeholder="Ingrese observaciones sobre el área"
        rows="3"
        help-text="Descripción adicional del área (opcional)" />

</x-modals.crud-modal>