<?php

namespace App\Http\Requests;

use App\Venta;
use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\GuiaSalida;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Http\FormRequest;

class GuiaSaveRequest extends FormRequest
{
  const RULES = [
    'cliente_documento' => 'required',
    'moneda'            => 'required|in:01,02',
    'tipo_cambio'       => 'required|numeric|min:0',
    'forma_pago'        => 'required',
    'vendedor'          => 'required',
    'observacion'       => 'nullable|sometimes|max:100',
    'nro_pedido'        => 'nullable|sometimes|max:100',
    // docref?
    'fecha_emision'     => 'required|date',
    // 'fecha_vencimiento' => 'required|date',
    'observacion' => 'nullable|sometimes|max:100',
    // -----------------------------------------

    // -------- items --------
    'items' => 'required',
    'items.*.DetCodi' => 'required',
    'items.*.DetNomb' => 'required|max:255',
    'items.*.UniCodi' => 'required',
    'items.*.DetCant' => 'required|numeric|min:0.1',
    'items.*.DetPrec' => 'required|numeric|min:0',
    'items.*.DetCome' => 'nullable|sometimes|max:500',
  ];

  const RULES_OPEN_PRICE = [
    'items' => 'required',
    'items.*.Linea' => 'required',
    'items.*.DetPrec' => 'required|numeric|min:0',
  ];


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
    return self::RULES;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {

        // Validar Cliente
        $cliente = ClienteProveedor::findByTipo($this->cliente_documento, ClienteProveedor::TIPO_CLIENTE);

        if (is_null($cliente)) {
          $validator->errors()->add('cliente_documento', 'No existen un cliente registrado en esta empresa con ese ruc');
          return;
        }

        if ((new DocumentHelper())->enPlazoDeEnvio(TipoDocumentoPago::GUIA_SALIDA,  $this->fecha_emision)) {
          $fecha_limite = (new DocumentHelper())->getFechaLimite(TipoDocumentoPago::GUIA_SALIDA);
          $message_error = sprintf("La %s no se puede emitir antes del %s", TipoDocumentoPago::getNombreDocumento(TipoDocumentoPago::GUIA_SALIDA), $fecha_limite);
          $validator->errors()->add('serie_documento',  $message_error);
          return;
        }        

        // Validar
        if ($this->despachar == "1") {
          if (!$cliente->isRucOrDni()) {
            $validator->errors()->add('cliente_documento', __('validation.guia_remision_despacho_cliente'));
            return false;            
          }
        }

        // Validar Forma de pago
        $forma_pago = FormaPago::find($this->forma_pago);
        if (is_null($forma_pago)) {
          $validator->errors()->add('forma_pago', 'Esta forma de pago no existe ');
        }

        // Validar Vendedor
        $vendedor = Vendedor::find($this->vendedor);
        if (is_null($vendedor)) {
          $validator->errors()->add('vendedor', 'Este vendedor no existe');
        }

        // Validar items
        $items = collect($this->items);

        foreach ($items as $item) {
          $producto = Producto::where('ProCodi', $item['DetCodi'])->first();
          if (is_null($producto)) {
            $validator->errors()->add('DetCodi', 'El codigo de producto es incorrecto');
            return;
          }
          $unidad = $producto->unidades->where('Unicodi', $item['UniCodi'])->first();
          if (is_null($unidad)) {
            $validator->errors()->add('UniCodi', 'El codigo de la unidad es incorrecto');
            return;
          }
        }
      });
    }

    // $venta = null;
    // if( is_null($this->doc_ref) ){
    // 	return;
    // }
    // else {
    // $venta = Venta::where([['VtaNume', $this->doc_ref ],['EmpCodi', empcodi() ]] )->first();
    // $items = collect($this->items);
    // foreach( $items as $item ){
    // 	if( !is_numeric($item['DetCant'])  ){
    // 		$validator->errors()->add('items', 'La cantidad del produncto a enviar tiene que ser númerico');
    // 	}
    // 	elseif( $item['DetCant'] < 0 ) {
    // 		$validator->errors()->add('items', 'La cantidad del produncto tiene que ser mayor que 0');
    // 	}
    // }
    // if( !$venta ){
    // 	return;
    // }
    // $venta_items = $venta->items;
    // $error = false;
    // foreach( $items as $item ){
    // 	$venta_item = $venta->items
    // 	->where('DetCodi' , $item['DetCodi'] )
    // 	->where('UniCodi' , $item['UniCodi'] )
    // 	->first();
    // 	$guia_items = collect($this->items);
    // 	if( is_null($venta_item) ){
    // 		$error = true;
    // 		$validator->errors()->add('items', 'No se puede cambiar la información de los productos, que no sea la cantidad');
    // 	}
    // }
    // 	if( ! $error ){
    // 		if( $items->sum('DetCant') > $venta->VtaSdCa ){
    // 			$validator->errors()->add('items', 'No puede registrar mas cantidad de los productos que la que existe en el documento de referencia');
    // 		}
    // 		foreach( $items as $item ){
    // 			$venta_item = $venta_items
    // 			->where('DetCodi' , $item['DetCodi'] )
    // 			->where('UniCodi' , $item['UniCodi'] )
    // 			->first();
    // 			if( $item['DetCant'] > $venta_item->DetCant ){
    // 				$validator->errors()->add('items', 'No puede haber mas cantidad del producto, que en el documento de referencia');
    // 			}
    // 		}
    // 	}
    // }
  }


  public function messages()
  {
    return [
      'doc_ref.required' =>  'El documento de referencia es obligatorio'
    ];
  }
}
