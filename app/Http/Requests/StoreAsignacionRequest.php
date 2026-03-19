<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\equ_asignado;

class StoreAsignacionRequest extends FormRequest
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
            'EQU_ID' => [
                'required',
                'exists:equipos,EQU_ID',
                function ($attribute, $value, $fail) {
                    // Validar que el equipo no esté asignado activamente
                    $asignado = equ_asignado::where('EQU_ID', $value)
                        ->where('EAS_ESTADO', 1)
                        ->exists();

                    if ($asignado) {
                        $fail('Este equipo ya está asignado a otro empleado.');
                    }
                },
            ],
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'EAS_FECHA_ENTREGA' => 'nullable|date|before_or_equal:today',
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
            'EQU_ID.required' => 'El equipo es requerido.',
            'EQU_ID.exists' => 'El equipo seleccionado no existe.',
            'EMP_ID.required' => 'El empleado es requerido.',
            'EMP_ID.exists' => 'El empleado seleccionado no existe.',
            'EAS_FECHA_ENTREGA.date' => 'La fecha de entrega debe ser una fecha válida.',
            'EAS_FECHA_ENTREGA.before_or_equal' => 'La fecha de entrega no puede ser futura.',
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
            'EQU_ID' => 'equipo',
            'EMP_ID' => 'empleado',
            'EAS_FECHA_ENTREGA' => 'fecha de entrega',
        ];
    }
}
