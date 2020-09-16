<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaStoreRequest extends FormRequest
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
            'razonSocial' => 'required|string|min:10|max:50|unique:empresa,razonSocial',

        ];
    }
    public function messages(){
        return [
            'razonSocial.required' => 'La Razón Social es requerido',
            'razonSocial.min' => 'La razón social debe tener al menos 10 caracteres.',
        ];
    }
}
