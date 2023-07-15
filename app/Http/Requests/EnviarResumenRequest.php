<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Resumen;

class EnviarResumenRequest extends FormRequest
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
      return [
        'id_resumen' => 'required'
      ];
    }

    public function withValidator($validator)
    {
      if( !$validator->fails() ){

        $validator->after(function($validator){

          $resumen = Resumen::findMultiple( $this->id_resumen , $this->docnume );

          if( !$resumen->items->count() ){
            $validator->errors()->add('documento', 'El resumen tiene que contener detalles');
            return;
          }

          if ($resumen->hasTicket()) {

            $validator->errors()->add('tipo', 'Este Resumen ya tiene ticket, si se encuentra PENDIENTE, proceda a validar el documento con el boton de Validar');
            return;
          }

          else {

            $correlativos = $resumen->items->sort()->pluck('detNume');
            $serie = $resumen->items->first()->detseri . "-";
            $last_value = "";
            $first = true;

            foreach( $correlativos as $correlativo ){              
              if( $first ){
                $last_value = $correlativo;
                $first = false;
              }              
              else {
                $nextValue = agregar_ceros($last_value,6);
                $last_value = $nextValue;                                
                if( $nextValue != $correlativo ){
                  $msj = 'Falta el documento: ' . $serie . $nextValue;
                  $validator->errors()->add( 'documento_faltante', $msj );
                  break;
                }
              }
            } // end foreach
          }

        });

      }

    }
}