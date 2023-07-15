<?php

namespace App\Http\Requests\Resumen;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class ResumenDestroyRequest extends FormRequest
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
      return [];
    }


    public function withValidator( $validator )
    {
      if( ! $validator->fails() ){
        $validator->after(function($validator){

          $resumen = Resumen::findMultiple( $this->id_resumen, $this->docnume );

          if( $resumen->hasTicket() ){
            $validator->errors()->add('tipo', 'Este Resumen ya tiene ticket, si se encuentra PENDIENTE, proceda a validar el documento con el boton de Validar');
          }

          if ( $resumen->codeResumenExists()) {
            $validator->errors()->add('tipo','Este Resumen ya se encuentra en la sunat, y se intento enviar por segunda vez, por favor revice en la sunat reemplace el # de Ticket para poder validar el documento');
          }

        });
      }
    }
}

