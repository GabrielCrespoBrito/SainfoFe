<?php

namespace App\Util\Import\Excell\Ventas;

use App\Empresa;
use App\Rules\RucValidation;
use App\TipoDocumento;
use App\SerieDocumento;
use Barryvdh\Debugbar\Twig\Extension\Dump;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Collections\RowCollection;

class ValidateCliente
{
  /**
   * Mensaje de error
   *
   * @var string
   */
  public $message = "";


  /**
   * Documentos a validar
   *
   * @var array
   */

  public $documento = [];


  public function __construct( array $documento)
  {
    $documento['cliente_tipodocumento'] = is_numeric($documento['cliente_tipodocumento']) ?  
    (int) $documento['cliente_tipodocumento'] :
    $documento['cliente_tipodocumento'];

    $this->documento = $documento;
  }

  public function passes()
  {
    // dump( $this->documento['cliente_tipodocumento']);
    $tipoDocumento = $this->documento['cliente_tipodocumento'];
    $documento =  $this->documento['cliente_documento'];

    $validTipo = Validator::make($this->documento, [
      'cliente_tipodocumento' => 'in:' . implode(',', TipoDocumento::getAll()),
      'cliente_documento' => 'required',
      'cliente_nombre' => 'required|max:255:',
    ]);

    # Validación basica
    if ($validTipo->fails()) {
      $this->setMessage($validTipo->errors());
      return false;
    }

    # Validar RUC
    if ($tipoDocumento === TipoDocumento::RUC) {
      $validRuc = new RucValidation(false);
      if (!$validRuc->validate($documento)) {
        $this->setMessage($validRuc->getMessage());
        return false;
      }
    }

    # Validar DNI
    elseif ($tipoDocumento === TipoDocumento::DNI) {
      $validator =  Validator::make($this->documento, [
        'cliente_documento' => 'digits:8'
      ]);
      if ($validator->fails()) {
        $this->setMessage($validator->errors());
        return false;
      }
    }

    # Validar Ninguno
    elseif ($tipoDocumento === TipoDocumento::NINGUNA) {
      if ( $documento != "." ) {
        $this->setMessage("Si el tipo de documento del cliente es ({$tipoDocumento}) el n° del documento tiene que ser simplemente un punto (.) y es  ({$documento}) ");
        return false;
      }
    }

    # Validar pasaporte y carnet de extranjeria
    elseif ( $tipoDocumento === TipoDocumento::PASAPORTE || $tipoDocumento === TipoDocumento::CARNETA_EXTRANJERIA || $tipoDocumento === TipoDocumento::CEDULA  ) {
      $validator =  Validator::make($this->documento, [
        'cliente_documento' => 'numeric'
      ]);
      if ($validator->fails()) {
        $this->setMessage($validator->errors());
        return false;
      }
    }

    return true;
  }

  /**
   * Get the value of message
   */
  public function getMessage() : string
  {
    return $this->message;
  }

  /**
   * Set the value of message
   *
   * @return  self
   */
  public function setMessage($message) 
  {
    $this->message = $message;

    return $this;
  }
}
