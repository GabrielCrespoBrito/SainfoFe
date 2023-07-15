<?php

namespace App\Http\Requests;

use App\Venta;
use App\SerieDocumento;
use App\TipoNotaCredito;
use App\TipoDocumentoPago;
use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Http\FormRequest;


class VentaCreateNotaCreditoRequest extends FormRequest
{
  use SaverDataRequest;

  public $documento;
  public $dataSave;

  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'tipo' => 'required|in:1,2,3,4',
      'fecha' => 'required|date',
      'serie' => 'required',
      'motivo' => 'required_if:tipo,1,2,4|max:125',
      'concepto' => 'required_if:tipo,3|max:125',
      'tipoIgv' => 'required_if:tipo,3|exists:tipos_igvs,cod_sunat',
      'importe' => 'required_if:tipo,3|numeric|min:0',
      'items.*.id' => 'required_if:tipo,2',
      'items.*.cantidad' => 'required_if:tipo,2|numeric|min:0',
      'cuotas.*.fecha' => 'required_if:tipo,4|date|gte:fecha',
      'cuotas.*.monto' => 'required_if:tipo,4|numeric|min:0'
    ];
  }


  public function validateDocumentoRef(&$validator)
  {
    $id = $this->route()->parameters['id'];

    $this->documento = Venta::find($id);

    if (is_null($this->documento)) {
      $validator->errors()->add('documento', 'El documento de referencia no existe');
      return false;
    }

    //
    if (get_empresa()->produccion()) {
      if (!$this->documento->isAvailabledForNotaCredito()) {
        $validator->errors()->add('documento', 'El documento de referencia tiene que estar enviado a la sunat');
        return false;
      }
    } else {
      if (!$this->documento->isAvailabledForNotaDebitoCreditoDemo()) {
        $validator->errors()->add('documento', 'El documento de referencia tiene que estar enviado a la sunat');
        return false;
      }
    }
    
    if( $this->tipo == 4 ){
      if ( $this->documento->isBoleta() ) {
        $validator->errors()->add('documento', 'Para Crear una Nota de Credito por Ajuste de Fecha/Monto, El Documento solo puede ser Factura con Forma de Pago Credito');
        return false;
      }

      if (! $this->documento->isCredito()) {
        $validator->errors()->add('documento', 'Para Crear una Nota de Credito por Ajuste de Fecha/Monto, El Documento tiene que ser con Forma de Pago Credito');
        return false;
      }
    }


    if ( $nc = $this->documento->hasNotaCreditoValid()) {
      $validator->errors()->add('documento', 'El Documento ya tiene una nota de credito asociada aceptado o pendiente de enviar (' . $nc->numero() . ')');
      return false;
    }

    $this->addData('documento' , $this->documento );
    $this->addData('id', $id );

    return true;
  }

  public function validatePorTipos(&$validator)
  {

    if ($this->tipo == 2) {

      $itemsNotaCredito = collect($this->items);
      $documento = $this->getData('documento');

      $itemsDocumento = $documento->items;

      if ( $itemsNotaCredito->count()  != $itemsNotaCredito->unique('id')->count() ) {
        $validator->errors()->add('items', 'Hay Items repetidos incorrectos');
        return false;
      }

      if($itemsNotaCredito->pluck('id')->diff($itemsDocumento->pluck('Linea'))->count() ){
        $validator->errors()->add('items', 'Hay Items que no corresponden a la factura');
        return false;
      }

      $items = $itemsNotaCredito->map(function($item) use($itemsDocumento)  {
        $item['model'] = $itemsDocumento->where('Linea', $item['id'])->first();
        return $item;
      });
      
      $this->addData('items' , $items );
    } 

    elseif ($this->tipo == 3) {

      if ($this->importe > $this->documento->VtaImpo) {
        $validator->errors()->add('items', 'El Total de la Nota de Credito, no puede Superar al total del Documento de Referencia');
        return false;
      }
      return true;
    }

    elseif ($this->tipo == 4) {

      $totalNC = collect($this->cuotas)->sum('monto');

      if ($totalNC > $this->documento->VtaImpo) {
        $validator->errors()->add('importe', sprintf('El Importe Total de las Cuotas (%s), no puede Superar al Total de Documento de Referencia (%s)', $totalNC, $this->documento->VtaImpo));
        return false;
      }
    }

    return true;
  }

  public function validateDataPrincipal(&$validator)
  {
    $serie = SerieDocumento::where('ID', $this->serie)->first();

    if (is_null($serie)) {
      $validator->errors()->add('serie', 'La Serie No Existe');
      return false;
    }

    if ($serie->empcodi != $this->getData('empcodi')) {
      $validator->errors()->add('serie', 'La Serie No Pertenece a la empresa');
      return false;
    }

    if ($serie->tidcodi != TipoDocumentoPago::NOTA_CREDITO) {
      $validator->errors()->add('serie', 'La Serie Tiene que ser de una nota de Credito');
      return false;
    }

    if (substr($this->documento->VtaSeri, 0, 1) !== substr($serie->sercodi, 0, 1)) {
      $validator->errors()->add('serie', 'La Serie Y el Documento Tiene que Empezar por la misma Letra');
      return;
    }

    if ($this->fecha < $this->documento->VtaFvta) {
      $validator->errors()->add('documento', 'La Fecha de la Nota de Credito no puede ser superior a la fecha del Documento de Referencia');
      return false;
    }


    $documentoHelper = new DocumentHelper();
    
    if ($this->fecha < $this->documento->VtaFvta) {
      $validator->errors()->add('documento', 'La Fecha de la Nota de Credito no puede ser superior a la fecha del Documento de Referencia');
      return false;
    }
    
    if ($documentoHelper->enPlazoDeEnvio(TipoDocumentoPago::NOTA_CREDITO, $this->fecha)) {
      $fecha_limite = (new DocumentHelper())->getFechaLimite(TipoDocumentoPago::NOTA_CREDITO);
      $message_error = sprintf("La %s no se puede emitir antes del %s", TipoDocumentoPago::getNombreDocumento(TipoDocumentoPago::NOTA_CREDITO), $fecha_limite);
      $validator->errors()->add('serie_documento',  $message_error);
      return false;
    }


    $this->addData('serieDocumento' , $serie );

    return true;
  }


  public function validateUser(&$validator)
  {
    $user = auth()->user();

    $this->addData('user' , $user);

    $loccodi = $user->localCurrent()->loccodi;
    
    $caja_id = $user->caja_aperturada(true, $loccodi);
    
    if( ! $caja_id  ){
      $validator->errors()->add('user', 'El Usuario tiene que tener una caja Aperturada');
      return false;
    }

    $this->addData('caja_id', $caja_id );
    $this->addData('loccodi', $loccodi );

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        $this->addData('empcodi', empcodi());

        if (!$this->validateUser($validator)) {
          return;
        }

        if (!$this->validateDocumentoRef($validator)) {
          return;
        }

        if (!$this->validateDataPrincipal($validator)) {
          return;
        }

        if (!$this->validatePorTipos($validator)) {
          return;
        }
      });
    }
  }
}
