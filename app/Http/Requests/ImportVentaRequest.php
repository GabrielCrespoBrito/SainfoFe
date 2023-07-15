<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Venta;
use App\Cotizacion;

class ImportVentaRequest extends FormRequest
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

	public function prepareForValidation()
	{
		if( $number = $this->numero_documento){
			$this->merge(['numero_documento' => math()->addCero($number,6) ]);
		}
	}

	public function rules()
	{
		return [
		  // 'type_id' => 'required|in:id,nume',
      'serie_documento' => 'required',
		  'numero_documento' => 'required'
		];
	}

  public function withValidator($validator)
  {
    if(!$validator->fails()){
      $validator->after(function($validator){
		  
      $nume = $this->serie_documento . '-' . $this->numero_documento;	
        
			$cotizacion = Cotizacion::findByNume($nume);

      if( $cotizacion ){
        $isPreventa = $cotizacion->isPreventa();
        
        if( $isPreventa ){
          if( ! $cotizacion->isPendiente() ){
            $estadoName = strtoupper($cotizacion->stateName());
			      $validator->errors()->add('Numero', "La Preventa se encuentra {$estadoName}");
            return;
          }
        }
        // Validar cotización
        else {
        }

        return;
      }

      // Validar Venta
      $venta = Venta::findByNume($nume);

      if($venta){
      }

      else {
        $validator->errors()->add('Numero', "No se encontro ningúna Preventa/Factura/Boleta con el numero {$nume}");
      }

      });
    }
  }

}
