<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Caja;
use App\Local;

class AperturarRequest extends FormRequest
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
          'id_local' => 'required'
      ];
    }

  public function withValidator($validator)
  {
    if( !$validator->fails() ){

      $validator->after(function($validator){
      
        $local = Local::where('LocCodi', $this->id_local)->where('EmpCodi', empcodi())->first();

        if( is_null($local) ){
          $validator->errors()->add('Caja', 'Esta local no existe.');
          return;
        }

        // $caja_aperturada = Caja::hasAperturada($this->id_local);
        $caja_aperturada = Caja::cajaAperturada($this->id_local, get_empresa()->isTipoCajaLocal() )->first();
        
        if( $caja_aperturada ){
          $validator->errors()->add('Caja', "Hay caja ya aperturada de fecha {$caja_aperturada->CajFech}. Para aperturar una nueva, tiene cerrar la anterior.");
          return;
        }
  
      });

    }

  }

}
