<?php

namespace App\Http\Requests;
use App\Venta;
use App\Models\Cierre;
use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Http\FormRequest;

class VerificationDocumentoAnulationRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'id_factura' => 'required'
    ];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $venta = Venta::find($this->id_factura);
      $validator->after(function ($validator) use ($venta) {

        if ( is_null($venta)) {
          $validator->errors()->add('id_factura', 'Documento no existe');
          return;
        }

        if ($venta->isAnulada()) {
          $validator->errors()->add('documento', 'Este documento ya se encuentra anulado');
          return;
        }

        if ($venta->isRechazado()) {
          $validator->errors()->add('documento', 'El documento Rechazado no se puede anular');
          return;
        }

        if (!$venta->isAnulable()) {
          $validator->errors()->add('documento', 'Este tipo de documento no se puede anular');
          return;
        }

        if( $venta->isDocumentoSunat() ){

          
          if ( $venta->isCdrNotSend() ) {
            $validator->errors()->add('tipo', 'Primero tiene que enviar el documento a la sunat para poder anularlo');
            return;
          }
          
          if (Cierre::estaAperturadoPorFecha($venta->VtaFvta)) {
            $validator->errors()->add('fecha_emision', sprintf('El Mes actual se encuentra Cerrado, para poder anular, tiene que <a href="%s" target="_blank"> APERTURAR EL MES </a>', route('cierre.index')));
            return;
          }
          
          if ( $venta->isContingencia() ) {
            $validator->errors()->add('tipo', 'Un documento de contingencia no se puede anular');
            return;
          }

          $detalle = $venta->detalle_anulacion();

          if( $detalle ){
            $link = route('boletas.agregar_boleta', [ 'numoper' => $detalle->numoper, 'docNume' => $detalle->docNume]);
            $validator->errors()->add('tipo',  sprintf('Este documento se encuentra en un Resumen de anulacion (%s), ingrese <a target="_blank" href="%s"> AQUI</a> y presione el boton VALIDAR, para terminar el proceso de anulacion', $detalle->docNume , $link));
            return;
          }

          else {
            
              if ((new DocumentHelper())->enPlazoDeEnvio('anulacion', $venta->VtaFvta)) {
                $dias = (new DocumentHelper())->getDiasPlazo('anulacion');
                $validator->errors()->add('tipo',  sprintf('El Documento no se encuentra dentro del plazo permitido para su anulaciòn (%s dias desde la fecha de emisiòn) ', $dias ));
                return;
              }
          }
        }


      });
    }
  }
}