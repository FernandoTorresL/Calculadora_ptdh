<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculaCuotasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre_patron'     => ['required', 'min:3', 'max:30'],
            'dias_laborados'    => ['required', 'between:1,20'],
            'sueldo_mensual'    => ['required', 'between:100,4999'],
        ];
    }

    public function messages()
    {
        return [
            'nombre_patron.required' => 'Por favor, captura el nombre de un patrón',
            'nombre.min' => 'Nombre del Patrón debe tener al menos :min caracteres',
            'nombre.max' => 'Nombre del Patrón debe tener menos de :max caracteres',

            'dias_laborados.required' => 'Por favor, captura la cantidad de días laborados',

            'sueldo_mensual.required' => 'Por favor, captura el sueldo mensual',
        ];
    }
}
