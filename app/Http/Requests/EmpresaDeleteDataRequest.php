<?php

namespace App\Http\Requests;

use App\Empresa;
use App\Jobs\Empresa\deleteData;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaDeleteDataRequest extends FormRequest
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
    public function rules()
    {
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        $empresa = Empresa::findOrfail($this->route()->parameters()['id']);

        if ($empresa->isPlanRegular()) {
          $validator->errors()->add('empresa', 'Esta Empresa no puede eliminarse su información porque tiene un plan de producción');
          return;
        }
      });
    }
  }
}
