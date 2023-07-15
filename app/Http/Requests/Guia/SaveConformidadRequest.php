<?php

namespace App\Http\Requests\Guia;

use App\GuiaSalida;
use Illuminate\Foundation\Http\FormRequest;

class SaveConformidadRequest extends FormRequest
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
      'e_conformidad' => sprintf(
        'required|in:%s,%s,%s',
        GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_RECHAZADO,
        GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_ACEPTADO,
        GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE
      ),
      'obs_traslado' => 'required|nullable|sometimes|max:600'
    ];
  }

  public function withValidator($validator)
  {
    if(!$validator->fails()){
      $validator->after(function($validator){
        $guia = GuiaSalida::find($this->route()->parameters['id']);
        $loccodi = $guia->Loccodi;
        if( ! auth()->user()->locales->where( 'loccodi', $loccodi )->count() ){
          $validator->errors()->add('loccodi', 'El usuario no puede dar conformidad, ya que no pertenece al local de destino');
          return;
        }
      });
    }
  }

}