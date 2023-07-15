<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\MotivoIngreso;
use App\MotivoEgreso;

class DeleteMotivoRequest extends FormRequest
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

    public $is_ingreso;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      $this->is_ingreso = $this->route()->parameters['type'] == "ingresos";

        return [
          'id_motivo' => 'required'
        ];


      
    }

  public function withValidator($validator)
  {

    if( ! $validator->fails() ){
      $validator->after(function ($validator){
        
        $motivo = $this->is_ingreso ? MotivoIngreso::findOrfail($this->id_motivo) : MotivoEgreso::findOrfail($this->id_motivo);
        $empresa = get_empresa();
        $cantMotivos = $this->is_ingreso ? $empresa->motivosIngresos->count() : $empresa->motivosEgresos->count();


        if( $cantMotivos == 1 ){
          $validator->errors()->add('id_motivo', 'No puede eliminar todos los motivos');
          return;
        }

        if( $motivo->movimientos->count() ){
          $validator->errors()->add('id_motivo', 'Este motivo esta asociado a un movimiento de caja.');
          return;
        }

      });
    }
    
  }



}
