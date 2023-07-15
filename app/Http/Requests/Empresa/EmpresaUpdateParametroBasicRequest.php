<?php

namespace App\Http\Requests\Empresa;

use App\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateParametroBasicRequest extends FormRequest
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
            'formato_hoja' => sprintf('required|in:%s,%s,%s', Empresa::FORMATO_HOJA_A4, Empresa::FORMATO_HOJA_A5, Empresa::FORMATO_HOJA_TICKET ),
            'PrecIIGV' => 'required|in:0,1',
        ];
    }
}
