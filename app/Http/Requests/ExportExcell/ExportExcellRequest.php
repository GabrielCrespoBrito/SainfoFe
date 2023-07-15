<?php

namespace App\Http\Requests\ExportExcell;

use Illuminate\Foundation\Http\FormRequest;

class ExportExcellRequest extends FormRequest
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
            'para' => 'required|in:sainfo,concar',
            'tipo' => 'required|in:ventas,compras',
            'periodo' => 'required|exists:mes,mescodi',
        ];
    }
}
