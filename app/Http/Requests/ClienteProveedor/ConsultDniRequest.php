<?php

namespace App\Http\Requests\ClienteProveedor;

use Illuminate\Foundation\Http\FormRequest;

class ConsultDniRequest extends FormRequest
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
            'numero' => 'required|digits:8',
        ];
    }
}
