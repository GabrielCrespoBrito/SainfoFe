<?php

namespace App\Http\Requests;

use App\Venta;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Http\FormRequest;

class VentaCreateNotaDebitoRequest extends FormRequest
{
  use SaverDataRequest;

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
      'fecha' => 'required|date',
      'serie' => 'required',
      'concepto' => 'required_if:tipo,3|max:125',
      'tipoIgv' => 'required_if:tipo,3|exists:tipos_igvs,cod_sunat',
      'importe' => 'required_if:tipo,3|numeric|min:0',
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

    if( get_empresa()->produccion() ){
      if (!$this->documento->isAvailabledForNotaDebito()) {
        $validator->errors()->add('documento', 'El documento de referencia tiene que estar enviado a la sunat');
        return false;
      }
    }
    else {
      if (!$this->documento->isAvailabledForNotaDebitoCreditoDemo()) {
        $validator->errors()->add('documento', 'El documento de referencia tiene que estar enviado a la sunat');
        return false;
      }
    }


    if ($nc = $this->documento->notaDebito()) {
      $validator->errors()->add('documento', 'El Documento ya tiene una Nota De Debito Asociado (' . $nc->numero() . ')');
      return false;
    }

    $this->addData('documento', $this->documento);
    $this->addData('id', $id);

    return true;
  }

  public function validatePorTipos(&$validator)
  {
    if ($this->importe > $this->documento->VtaImpo) {
      $validator->errors()->add('items', 'El Total de la Nota de Debito, no puede Superar al total del Documento de Referencia');
      return false;
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

    if ($serie->tidcodi != TipoDocumentoPago::NOTA_DEBITO) {
      $validator->errors()->add('serie', 'La Serie Tiene que ser de una nota de credito');
      return false;
    }

    if (substr($this->documento->VtaSeri, 0, 1) !== substr($serie->sercodi, 0, 1)) {
      $validator->errors()->add('serie', 'La Serie Y el Documento Tiene que Empezar por la misma Letra');
      return;
    }

    if ($this->fecha < $this->documento->VtaFvta) {
      $validator->errors()->add('documento', 'La Fecha de la Nota de Debito no puede ser inferior a la fecha del Documento de Referencia');
      return false;
    }


    $documentoHelper = new DocumentHelper();

    if ($this->fecha < $this->documento->VtaFvta) {
      $validator->errors()->add('documento', 'La Fecha de la Nota de Credito no puede ser superior a la fecha del Documento de Referencia');
      return false;
    }

    if ($documentoHelper->enPlazoDeEnvio(TipoDocumentoPago::NOTA_DEBITO, $this->fecha)) {
      $fecha_limite = (new DocumentHelper())->getFechaLimite(TipoDocumentoPago::NOTA_DEBITO);
      $message_error = sprintf("La %s no se puede emitir antes del %s", TipoDocumentoPago::getNombreDocumento(TipoDocumentoPago::NOTA_DEBITO), $fecha_limite);
      $validator->errors()->add('serie_documento',  $message_error);
      return false;
    }


    $this->addData('serieDocumento', $serie);

    return true;
  }


  public function validateUser(&$validator)
  {
    $user = auth()->user();

    $this->addData('user', $user);

    $loccodi = $user->localCurrent()->loccodi;

    $caja_id = $user->caja_aperturada(true, $loccodi);

    if (!$caja_id) {
      $validator->errors()->add('user', 'El Usuario tiene que tener una caja Aperturada');
      return false;
    }

    $this->addData('caja_id', $caja_id);
    $this->addData('loccodi', $loccodi);

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
