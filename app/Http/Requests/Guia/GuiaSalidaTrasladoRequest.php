<?php

namespace App\Http\Requests\Guia;

use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\MotivoTraslado;
use Illuminate\Foundation\Http\FormRequest;

class GuiaSalidaTrasladoRequest extends FormRequest
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
      'almacen_id' => 'required',
      'tipo_movimiento' => 'required',
    ];
  }

  public function withValidator($validator)
  {    
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        $guia = GuiaSalida::find($this->route()->parameters['id']);        

        if ($guia->haSidoTrasladada()) {
          $validator->errors()->add('GuiOper', 'La guia ya ha sido trasladada');
          return;
        }

        if ($guia->isAnulada()) {
          $validator->errors()->add('GuiOper', 'La guia ya se encuentra anulada, no se puede realizar traslado');
          return;
        }        

        if (!$guia->isSameEmpresa()) {          
          $validator->errors()->add('GuiOper', 'Para hacer un traslado, el cliente seleccionar tiene que ser su empresa');
          return;
        }

        if( $guia->hasFormato() ) {
          if( !$guia->motcodi == MotivoTraslado::TRASLADO_MISMA_EMPRESA){
            $validator->errors()->add('GuiOper', 'A Guia no puede hacersele traslado, por el motivo consignado');
            return;
          }
        }
        
      });
    }
  }
}
