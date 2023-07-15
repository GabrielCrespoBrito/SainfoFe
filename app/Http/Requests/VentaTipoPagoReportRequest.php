<?php

namespace App\Http\Requests;

use App\ClienteProveedor;
use Illuminate\Foundation\Http\FormRequest;

class VentaTipoPagoReportRequest extends FormRequest
{
  public function prepareForValidation()
  {
    $cliente_documento_id = null;
    if( $this->cliente_documento ){
      $cliente_documento_id = $this->cliente_documento == 'undefined' ? null : $this->cliente_documento;
    }

    $this->merge([
      'cliente_documento' => $cliente_documento_id
    ]);
  }


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
          "tipo_pago_id" => 'nullable|exists:tipo_pago,TpgCodi',
          "cliente_documento" => "nullable",
          "fecha_desde" => "required|date",
          "fecha_hasta" => "required|after_or_equal:fecha_desde"
        ];
    }

// $this->cliente_document
    
    public function withValidator($validator)
    {

      if(!$validator->fails()){
        $validator->after(function($validator){
          if(!is_null( $this->cliente_documento) ){
            if (!ClienteProveedor::findByTipo($this->cliente_documento, ClienteProveedor::TIPO_CLIENTE)){
              $validator->errors()->add('field','El Cliente suministrado no existe');
            }
          }

        });
      }
    }

}
