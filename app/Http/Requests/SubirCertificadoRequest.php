<?php

namespace App\Http\Requests;

use App\Http\Requests\User\StoreClaveSolRequest;
use App\Rules\SolRule;
use Illuminate\Foundation\Http\FormRequest;

class SubirCertificadoRequest extends FormRequest
{
    public function authorize()
    {
      return true;
    }

    public function rules()
    {
      return [
      'cert_password' => 'required',
      'usuario_sol' => 'required',
      'clave_sol' => 'required',

      'cert_key' => 'required|file',
      'cert_cer' => 'required|file',
      'cert_pfx' => 'required|file',
      ];
    }

    public function withValidator($validator){

      if( !$validator->fails() ){

        $validator->after(function($validator){

          # Validar que el usuario pueda subir el certificado
          if( ! get_empresa()->needConfig()) {
            $validator->errors()->add('cert_cer', 'Tiene que elegir un plan para poder actualizar los datos de facturaciòn'); 
            return;
          }

          # Validar el usuario sol
          $solValidate = new SolRule( get_empresa()->ruc(),  $this->usuario_sol, $this->clave_sol );
          
          if( !$solValidate->passes(null,null) ){
            // $validator->errors()->add('cert_cer', $solValidate->message() );
            // return;
          }

          # Validar los certificados
          if( !$this->cert_cer && !$this->cert_key && !$this->cert_pfx )
          {
            $validator->errors()->add( 'cert_cer' , 'Tiene que subir al menos un certificado' );
            $validator->errors()->add( 'cert_key' , 'Tiene que subir al menos un certificado' );
            $validator->errors()->add( 'cert_pfx' , 'Tiene que subir al menos un certificado' );                    
          }

          else {

            if( $this->cert_key ){
              if( $this->cert_key->getClientOriginalExtension() != 'key' ){
                $validator->errors()->add( 'cert_key' , 'La extension del archivo tiene que ser .key' );
              }
            }

            if( $this->cert_cer ){
              if( $this->cert_cer->getClientOriginalExtension() != 'cer' ){
                $validator->errors()->add( 'cert_cer' , 'La extension del archivo tiene que ser .cer' );
              }
            }
            if( $this->cert_pfx ){          
              if( $this->cert_pfx->getClientOriginalExtension() != 'pfx' ){
                $validator->errors()->add( 'cert_pfx' , 'La extension del archivo tiene que ser .pfx' );
              }
            }
          }


        });
        
      }

    }
}
