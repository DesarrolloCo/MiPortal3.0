<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioSelectivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:empleados,EMP_ID',
            'CAM_ID' => 'required|exists:campanas,CAM_ID',
            'FECHA_INICIAL' => 'required|date|after_or_equal:today',
            'FECHA_FINAL' => 'required|date|after_or_equal:FECHA_INICIAL',
            'JOR_ID' => 'nullable|required_without_all:HORA_INICIAL,HORA_FINAL|exists:jornadas,JOR_ID',
            'HORA_INICIAL' => 'nullable|required_with:HORA_FINAL|exists:horas,HOR_ID',
            'HORA_FINAL' => 'nullable|required_with:HORA_INICIAL|exists:horas,HOR_ID|gte:HORA_INICIAL',
            'checkJorOrHor' => 'required|in:0,1',
            'USER_ID' => 'required|exists:users,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'ids.required' => 'Debe seleccionar al menos un empleado',
            'ids.array' => 'La selección de empleados es inválida',
            'ids.min' => 'Debe seleccionar al menos un empleado',
            'ids.*.exists' => 'Uno o más empleados seleccionados no existen',
            'CAM_ID.required' => 'Debe seleccionar una campaña',
            'CAM_ID.exists' => 'La campaña seleccionada no existe',
            'FECHA_INICIAL.required' => 'La fecha inicial es obligatoria',
            'FECHA_INICIAL.date' => 'La fecha inicial debe ser una fecha válida',
            'FECHA_INICIAL.after_or_equal' => 'La fecha inicial debe ser hoy o posterior',
            'FECHA_FINAL.required' => 'La fecha final es obligatoria',
            'FECHA_FINAL.date' => 'La fecha final debe ser una fecha válida',
            'FECHA_FINAL.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial',
            'JOR_ID.required_without_all' => 'Debe seleccionar una jornada o un rango de horas',
            'JOR_ID.exists' => 'La jornada seleccionada no existe',
            'HORA_INICIAL.required_with' => 'Debe seleccionar hora inicial y final',
            'HORA_INICIAL.exists' => 'La hora inicial seleccionada no existe',
            'HORA_FINAL.required_with' => 'Debe seleccionar hora inicial y final',
            'HORA_FINAL.exists' => 'La hora final seleccionada no existe',
            'HORA_FINAL.gte' => 'La hora final debe ser mayor o igual a la hora inicial',
            'checkJorOrHor.required' => 'Debe especificar el tipo de horario',
            'checkJorOrHor.in' => 'Tipo de horario inválido'
        ];
    }
}
