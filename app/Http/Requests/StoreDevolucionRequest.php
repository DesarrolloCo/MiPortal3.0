<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDevolucionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Usuario debe estar autenticado
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'DEV_FECHA_DEVOLUCION' => 'required|date|before_or_equal:today',
            'DEV_RECIBIDO_POR' => 'nullable|exists:empleados,EMP_ID',
            'DEV_ESTADO_EQUIPO' => 'required|in:Bueno,Regular,Malo',
            'DEV_HARDWARE_COMPLETO' => 'nullable|boolean',
            'DEV_SOFTWARE_COMPLETO' => 'nullable|boolean',
            'DEV_OBSERVACIONES' => 'nullable|string|max:1000',
            'DEV_DANOS_REPORTADOS' => 'nullable|string|max:1000',
            'DEV_FALTANTES' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'DEV_FECHA_DEVOLUCION.required' => 'La fecha de devolución es requerida.',
            'DEV_FECHA_DEVOLUCION.date' => 'La fecha de devolución debe ser una fecha válida.',
            'DEV_FECHA_DEVOLUCION.before_or_equal' => 'La fecha de devolución no puede ser futura.',
            'DEV_RECIBIDO_POR.exists' => 'El empleado seleccionado no existe.',
            'DEV_ESTADO_EQUIPO.required' => 'El estado del equipo es requerido.',
            'DEV_ESTADO_EQUIPO.in' => 'El estado del equipo debe ser: Bueno, Regular o Malo.',
            'DEV_OBSERVACIONES.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
            'DEV_DANOS_REPORTADOS.max' => 'Los daños reportados no pueden exceder 1000 caracteres.',
            'DEV_FALTANTES.max' => 'Los faltantes no pueden exceder 1000 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'DEV_FECHA_DEVOLUCION' => 'fecha de devolución',
            'DEV_RECIBIDO_POR' => 'recibido por',
            'DEV_ESTADO_EQUIPO' => 'estado del equipo',
            'DEV_OBSERVACIONES' => 'observaciones',
            'DEV_DANOS_REPORTADOS' => 'daños reportados',
            'DEV_FALTANTES' => 'faltantes',
        ];
    }
}
