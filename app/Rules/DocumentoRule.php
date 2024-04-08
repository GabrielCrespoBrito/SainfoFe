<?php

namespace App\Rules;

use App\TipoDocumento;
use App\TipoIgv;
use Illuminate\Contracts\Validation\Rule;

class DocumentoRule implements Rule
{


  /**
   * Create a new rule instance.
   *
   * @return void
   */

  public $clientes;
  public $tipo_cliente;
  public $tipo_documento;
  public $message;

  public function __construct( $clientes, $tipo_cliente, $tipo_documento )
  {
    $this->clientes = $clientes;
    $this->tipo_cliente = $tipo_cliente;
    $this->tipo_documento = (int) $tipo_documento;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    if ($this->tipo_documento === TipoDocumento::NINGUNA) {
      return true;
    }
    
    if ($this->exists($value)) {
      return false;
    }
    
    return $this->validate($value);
  }

  public function exists($documento)
  {
    if (isset($this->clientes[ $this->tipo_cliente . '-' . $documento])) {
      $entidad = $this->tipo_cliente == 'C' ? 'Cliente' : 'Proveedor';
      $this->message =  sprintf("El %s de numero Doc, %s esta repetido", $entidad, $documento );
      return true;
    }

    return false;
  }

  public function validate($documento)
  {
    $validator = $this->tipo_documento == TipoDocumento::DNI ? new DniValidation() : new RucValidation();
    
    if( $validator->passes('validation', $documento) == false ){
      $this->message = $validator->getMessage();
      return false;
    }

    return true;
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return $this->message;
  }
}
