<?php

namespace App\Http\Requests\Vehiculo;

use App\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;

class VehiculoRequest extends FormRequest
{
  public $isPost;
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */


  public function prepareForValidation()
  {
    $this->merge(['VehPlac' => strtoupper($this->VehPlac)]);
  }

  public function authorize()
  {
    return true;
  }
  public function rules()
  {
    $this->isPost = strtolower($this->getMethod()) == 'post';
    $rules = [
      'VehPlac' => 'required|alpha_num|min:6|max:8',
      'VehMarc' => ['required'],
      'VehInsc' => 'nullable|max:90',
    ];
    return $rules;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        // VehCodi, VehPlac, VehMarc, VehInsc, empcodi
        $vehiculo = Vehiculo::where('VehPlac', $this->VehPlac)->first();
        if ($vehiculo) {
          if ($this->isPost) {
            $validator->errors()->add('VehPlac', 'La placa del vehiculo ya esta siendo usada');
            return;
          }
          $currentEmpresa = Vehiculo::findOrfail($this->route()->parameters()['vehiculo']);
          if ($vehiculo->id != $currentEmpresa->id) {
            $validator->errors()->add('VehPlac', 'La placa del vehiculo ya esta siendo usada por otro vehiculo');
            return;
          }
        }
      });
    }
  }
}
