<?php

namespace App\Http\Requests\CajaDetalle;

use App\Caja;
use App\CajaDetalle;
use Illuminate\Foundation\Http\FormRequest;

class CajaDetalleIngresoUpdateRequest extends FormRequest
{
    
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'moneda' => 'required|exists:moneda,moncodi',
      'fecha' => 'required|date',
      'motivo' => 'required|max:100',
      'nombre' => 'required|max:100',
      'monto' => 'required|numeric|min:0',
      'autoriza' => 'required|max:65',
      'id_movimiento' => 'required',
      
    ];
  }

  public function withValidator($validator)
  {


    if( !$validator->fails() ){
      $validator->after(function($validator){
          $detalle = CajaDetalle::findOrfail($this->id_movimiento);
        if( ! $detalle->caja->isAperturada() ){
          $validator->errors()->add('caja', 'La caja con la que esta trabajando no esta aperturada');
        }
      });
    }
  }
}
