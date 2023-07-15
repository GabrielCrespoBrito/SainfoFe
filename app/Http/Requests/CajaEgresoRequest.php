<?php

namespace App\Http\Requests;

use App\Caja;
use App\Control;
use App\Personal;
use App\BancoEmpresa;
use App\MotivoEgreso;
use Illuminate\Foundation\Http\FormRequest;

class CajaEgresoRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    $rules = [
      'moneda'  => 'required|exists:moneda,moncodi',
      'fecha'   => 'required|date',
      'nombre'  => 'required|max:100',
      'monto'   => 'required|numeric',
      'egreso_tipo' => 'required',
      'egreso_tipo' => 'required',
      'motivo' => 'required',
      'id_movimiento' => 'required',
      'otro_doc' => 'nullable|sometimes|max:50',
      'autoriza' => 'required|max:100',
    ];

    if ($this->egreso_tipo == Control::PAGO_PERSONAL) {
      $rules['personal_id'] = 'required';
    }

    if ($this->egreso_tipo == Control::TRANSFERENCIA_BANCO) {
      $rules['banco_id'] = 'required';
    }

    return $rules;
  }

  public function withValidator($validator)
  {
    $caja_current = Caja::find($this->route()->parameters["id_caja"]);

    if (!$validator->fails()) {

      $validator->after(function ($validator) use ($caja_current) {

        if (!$caja_current->isAperturada()) {
          $validator->errors()->add('caja', 'La caja con la que esta trabajando no esta aperturada');
        } else {

          // Tipos de egresos permitidos
          if (!in_array($this->egreso_tipo, Control::EGRESO_PERMITIDOS)) {
            $validator->errors()->add('egreso_tipo', 'Este tipo de egreso no esta permitido');
          } else {

            // Transferencia a otra caja
            if ($this->egreso_tipo == Control::SALIDA_TRANSFERENCIA) {

              $caja = Caja::find($this->caja_transferencia);

              if (is_null($caja)) {
                $validator->errors()->add('caja_transferencia', 'Tiene que seleccionar la caja a la que hacer la transferencia');
              } elseif ($caja->CajNume == $caja_current->CajNume) {
                $validator->errors()->add('caja_transferencia', 'No se puede transferir a la misma caja');
              } elseif (!$caja->isAperturada()) {
                $validator->errors()->add('caja_transferencia', 'La caja a transferir tiene que estar aperturada');
              }
            }

            // Personal
            if ($this->egreso_tipo == Control::PAGO_PERSONAL) {
              $personal = Personal::find($this->personal_id);

              if (is_null($personal)) {
                $validator->errors()->add('personal_id', 'Esta empresa no tiene asociada un personal con este codigo');
              }
            }

            // Personal
            if ($this->egreso_tipo == Control::TRANSFERENCIA_BANCO) {
              $banco = BancoEmpresa::find($this->banco_id);
              if (is_null($banco)) {
                $validator->errors()->add('banco_id', 'Tiene que seleccionar un banco valido');
                return;
              }
              if ( $banco->isAperturada() == null ) {
                $validator->errors()->add('banco_id', 'La cuenta que tener aperturada una cuenta');
                return;
              }

            }

            if (is_null(MotivoEgreso::find($this->motivo))) {
              $validator->errors()->add('motivo', 'Este motivo no se encuentra registrado en el sistema');
            }
          }
        }
      });
    }
  }
}