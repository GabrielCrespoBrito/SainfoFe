<?php

namespace App\Http\Requests\EmpresaTransporte;

use App\EmpresaTransporte;
use App\Rules\RucValidation;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaTransporteRequest extends FormRequest
{
  public $isPost;

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
    $this->isPost = strtolower($this->getMethod()) == 'post';

    return [
      'EmpNomb' => 'required|max:90',
      'EmpRucc' => ['required', 'digits:11', new  RucValidation(true)],
      'mtc' => ['required', 'max:20', 'alpha_num']
    ];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        if ($this->EmpRucc == get_empresa('EmpLin1')) {
          $validator->errors()->add('EmpRucc', 'El Ruc de la Empresa de Transporte, No puede ser El mismo que el de la Empresa Emisora');
          return;
        }

        $empresa_trans = EmpresaTransporte::where('EmpRucc', $this->EmpRucc)->first();
        if ($empresa_trans) {

          if ($this->isPost) {
            $validator->errors()->add('EmpRucc', 'El Ruc de la empresa ya esta siendo usado');
            return;
          }

          $currentEmpresa = EmpresaTransporte::findOrfail($this->route()->parameters()['empresa_transporte']);

          if ($empresa_trans->id != $currentEmpresa->id) {
            $validator->errors()->add('EmpRucc', 'El Ruc de la empresa ya esta siendo usado');
            return;
          }
        }
      });
    }
  }
}
