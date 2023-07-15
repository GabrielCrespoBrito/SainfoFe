<?php

namespace App\Http\Requests\Guia;

use App\EmpresaTransporte;
use App\GuiaSalida;
use App\MotivoTraslado;
use App\Helpers\DocumentHelper;
use App\Transportista;
use App\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;

class GuiaDespachoIngresoRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }  

  public function rules()
  {
    $modTrasladoPublico = GuiaSalida::TRASLADO_PUBLICO;
    $modTrasladoPrivado = GuiaSalida::TRASLADO_PRIVADO;
    return [
      'direccion_llegada'    => 'required|max:255',
      'ubigeo_partida' => 'required|numeric',
      'ubigeo_llegada' => 'required|numeric',
      'direccion_partida' => 'required|max:255',
      'empresa' => 'required',
      'peso_total' => 'required|numeric|min:1',
      'motivo_traslado' => 'required|exists:motivo_traslado,MotCodi',
      'modalidad_traslado' => "required|in:{$modTrasladoPrivado},{$modTrasladoPublico}",
      
      'transportista' => "required_if:modalidad_traslado,{$modTrasladoPrivado}",
      'placa' => "required_if:modalidad_traslado,{$modTrasladoPrivado}",
      'empresa' => "required_if:modalidad_traslado,{$modTrasladoPublico}",
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

    if ($guia->motcodi) {
      if ($this->motivo_traslado != $guia->motcodi) {
        $validator->errors()->add('motivo_traslado',  "El motivo de traslado tiene que ser 'Traslado Entre Establecimientos de la misma Empresa'");
        return false;
      }
    } else {
      if ($this->motivo_traslado === MotivoTraslado::TRASLADO_MISMA_EMPRESA) {
        if ($documento_entidad != $ruc_empresa) {
          $validator->errors()->add('motivo_traslado',  "Cliente Equivocado: el cliente tiene que ser su misma empresa cuando el Motivo de Traslado es  \"$nombre_traslado_misma_empresa\" ");
          return false;
        }
        return true;
      } else {
        if ($documento_entidad == $ruc_empresa) {
          $validator->errors()->add('motivo_traslado', "Cliente Equivocado: El cliente solo puede ser su misma empresa cuando el campo Motivo de Traslado es  \"$nombre_traslado_misma_empresa\" ");
          return false;
        }
        return true;
      }
    }
  }


  public function validateInfoTraslado(&$validator)
  {
    if ($this->modalidad_traslado == GuiaSalida::TRASLADO_PRIVADO) {

      if (! Transportista::find($this->transportista)) {
        $validator->errors()->add('transportista',  'El Transportista es requirido');
        return false;
      }

      if (! Vehiculo::find($this->placa)) {
        $validator->errors()->add('transportista',  'El Vehiculo es requerido');
        return false;
      }

      return true;
    }


    if (! EmpresaTransporte::find($this->empresa)) {
      $validator->errors()->add('empresa',  'La Empresa de Transporte es requerida');
      return false;
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {

        $id_guia = $this->route()->parameters()['id'];
        $guia = GuiaSalida::find($id_guia);
        $td = $guia->getTipoDocumento();

        if ((new DocumentHelper())->enPlazoDeEnvio($td, $this->GuiFemi)) {
          $fecha_limite = (new DocumentHelper())->getFechaLimite($td);
          $message_error = sprintf("La Guia no se puede emitir antes del %s", $fecha_limite);
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

        // if ($guia->isCerrada()) {
        //   $validator->errors()->add('guia', 'Esta guia ya esta impresa');
        //   return;
        // }
        
        if ( !$guia->canChangeDespacho()) {
          $validator->errors()->add('guia', 'Ya no puede cambiar la información de despacho');
          return;
        }

        if (!$this->validateEntidadMotivo($validator, $guia)) {
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
