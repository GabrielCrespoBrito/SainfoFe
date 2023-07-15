<?php

namespace App\Http\Requests\Admin;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class DocumentoDeleteRequest extends FormRequest
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
          'empresa_id' => 'required|exists:opciones,empcodi',
          'password_admin' => 'required',
        ];
    }

    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){
            // Validar Contraseña de Administrador
            if($this->password_admin !== config('app.password_admin') ){
              $validator->errors()->add('password_admin','La contraseña de administrador es incorrecta');
              return;
            }

            empresa_bd_tenant($this->empresa_id);
            $venta = Venta::find( $this->route()->parameters()['id'] );

            // Validar Estado de Venta
            if (!$venta->isPendiente()) {
              $validator->errors()->add('estado', 'Solo se puede eliminar documentos pendientes');
              return;
            }

            // Validar Estado de Venta
            if (!$venta->isUltimoDocumento()) {      
            $validator->errors()->add('estado', 'Este comprobante no es el ultimo documento registrado de su serie');
            return;
            }

        });
      }
    }

}
