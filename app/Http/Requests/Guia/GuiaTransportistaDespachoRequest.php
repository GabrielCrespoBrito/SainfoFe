<?php

namespace App\Http\Requests\Guia;

use App\ClienteProveedor;
use App\EmpresaTransporte;
use App\GuiaSalida;
use App\MotivoTraslado;
use App\Helpers\DocumentHelper;
use App\Transportista;
use App\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;

class GuiaTransportistaDespachoRequest extends FormRequest
{
  // ------------------ | ------------------ | ------------------
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'destinatario'    => 'required',
      'direccion_llegada'    => 'required|max:255',
      'ubigeo_partida' => 'required|numeric',
      'ubigeo_llegada' => 'required|numeric',
      'direccion_partida' => 'required|max:255',
      'empresa' => 'required',
      'peso_total' => 'required|numeric|min:1',
      'transportista' => "required",
      'placa' => "required",
    ];
  }

  public function validateEntidadMotivo(&$validator, $guia)
  {
    $cliente = $guia->cliente;
    $documento_entidad = $cliente->PCRucc;
    $ruc_empresa = get_ruc();
    $nombre_traslado_misma_empresa = 'Traslado entre establecimientos de la misma empresa';
    $guia = GuiaSalida::find($this->route()->parameters['id']);
    // Validar Tipo de Cliente
    if (!$cliente->isRucOrDni()) {
      $validator->errors()->add('cliente_documento', __('validation.guia_remision_despacho_cliente'));
      return false;
    }

    if ($this->motivo_traslado === MotivoTraslado::TRASLADO_MISMA_EMPRESA) {
      if ($documento_entidad != $ruc_empresa) {
        $validator->errors()->add('motivo_traslado',  "Cliente Equivocado: el cliente tiene que ser su misma empresa cuando el Motivo de Traslado es  \"$nombre_traslado_misma_empresa\" ");
        return false;
      }
      return true;
    } else {
      if ($documento_entidad == $ruc_empresa) {
        $validator->errors()->add('motivo_traslado', "Cliente Equivocado: El cliente solo puede ser su misma empresa cuando es una Guia de Remisión y el campo Motivo de Traslado es  \"$nombre_traslado_misma_empresa\" ");
        return false;
      }
      return true;
    }
  }

  public function validateDestinatario(&$validator)
  {
    return ClienteProveedor::findCliente($this->destinatario);
  }


  public function validateInfoTraslado(&$validator)
  {
    if (!Transportista::find($this->transportista)) {
      $validator->errors()->add('transportista',  'El Transportista es requirido');
      return false;
    }
    if (!Vehiculo::find($this->placa)) {
      $validator->errors()->add('transportista',  'El Vehiculo es requerido');
      return false;
    }
    if (!EmpresaTransporte::find($this->empresa)) {
      $validator->errors()->add('empresa',  'La Empresa de Transporte es requerida');
      return false;
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {
        // Guia-Transportista
        $id_guia = $this->route()->parameters()['id'];
        $guia = GuiaSalida::find($id_guia);
        $td = $guia->getTipoDocumento();
        // 
        if ((new DocumentHelper())->enPlazoDeEnvio($td, $this->GuiFemi)) {
          $fecha_limite = (new DocumentHelper())->getFechaLimite($td);
          $message_error = sprintf("La Guia Transportista no se puede emitir antes del %s", $fecha_limite);
          $validator->errors()->add('serie_documento',  $message_error);
          return;
        }

        if (!$this->validateInfoTraslado($validator)) {
          return;
        }

        if ($guia->isAnulada()) {
          $validator->errors()->add('guia', 'Esta Guia ya se encuentra en estado anulado');
          return;
        }

        if (!$guia->canChangeDespacho()) {
          $validator->errors()->add('guia', 'Ya no puede cambiar la información de despacho');
          return;
        }

        if (!$this->validateEntidadMotivo($validator, $guia)) {
          return;
        }

        if (!$this->validateDestinatario($validator)) {
          $validator->errors()->add('guia', 'El Destinatario No existe');
          return;
        }
      });
    }
  }

  public function messages()
  {
    return [
      'peso_total.numeric' => 'El peso tiene que ser numerico',
      'peso_total.min' => 'El peso tiene que ser mayor que 0',
    ];
  }
}