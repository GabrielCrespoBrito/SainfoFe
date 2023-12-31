<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'id' => 'required',
            'nombre' => 'required|min:4|max:140',
            'telefono' => 'sometimes|nullable|numeric',
            'direccion' => 'nullable|max:140',
            'password' => 'nullable|sometimes|confirmed',
            'email' => 'required',

        ];
    }
}
