<?php

namespace App\Http\Requests;

use App\Zona;
use App\Venta;
use App\Moneda;
use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\Cotizacion;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use App\TipoCambioPrincipal;
use App\Models\Venta\Traits\Calculator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Venta\Traits\CalculatorTotal;

class CotizacionSaveRequest extends FormRequest
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

  public $isStore;
  public $id_nota_credito;
  public $id_nota_debito;
  public $id_factura;
  public $id_boleta;
  public $totales_items = [];
  public $series = null;

  public function prepareForValidation()
  {
    $this->merge(['tipo_cambio' => TipoCambioPrincipal::lastChangeSale()]);
  }

  public function rules()
  {

    $this->isStore = $this->route()->getName() != 'coti.update';
    $this->id_nota_credito = TipoDocumentoPago::notaCreditoId();
    $this->id_nota_debito = TipoDocumentoPago::notaDebitoId();
    $this->id_factura = TipoDocumentoPago::facturaId();
    $this->id_boleta = TipoDocumentoPago::boletaId();

    $rules = [
      'tipo_documento'  => 'required',
      'cliente_documento' => 'required',
      'moneda'  => 'required|exists:moneda,moncodi',

      // 'forma_pago' => 'required|exists:condicion,conCodi',
      'vendedor' => 'required',
      'ZonCodi' => 'required',
      'nro_pedido' => '',
      'fecha_emision' => 'required|date',
      'fecha_vencimiento' => 'required|after_or_equal:' . $this->fecha_emision,

      // items
      'items.*.DetNomb' => 'required|max:255',
      // 'items.*.DetUni' => 'required',
      // 'items.*.DetUniNomb' => 'required',
      'items.*.DetCant' => 'required|numeric|min:0.1',
      'items.*.DetPrec' => 'required|numeric|min:0',
      'items.*.DetDcto' => 'required|numeric|min:0',
      'items.*.DetImpo' => 'required|numeric|min:0',
    ];

    if ($this->isStore) {
      $rules['tipo'] = 'required|in:50,53,98,99';
    }

    return $rules;
  }


  public function validateTotal(&$validator)
  {
    // _dd($this->totales_items);
    // exit();

    $calculator = new CalculatorTotal($this->totales_items);

    $calculator->setParameters(0, $this->anticipoValue, 0, 0);

    $totales = $calculator->getTotal();

    $total_calculado = (float) trim($totales->total_cobrado);
    $total_request = (float) trim($this->total_importe);

    if ( ($this->total_cobrado - $this->total_importe) > 0.1) {

      $validator->errors()->add('total', "El total suministrado {$this->total_importe} no coincide con el total correcto calculado ({$totales->total_cobrado})");
      return false;
    }

    if (0 >  $total_calculado) {
      $validator->errors()->add('total', "El total no puede ser negativo");
      return false;
    }

    $this->total_documento = $totales;

    return true;
  }



  public function validateItems($items, &$validator)
  {
    $calculator = new Calculator();

    $index = 0;

    foreach ($items as $item) {

      $produtoId = substr($item['UniCodi'], 0, -2);

      $producto = Producto::withoutGlobalScope('noEliminados')
      ->where('ID', $produtoId)
      ->first();


      if (is_null($producto)) {
        $validator->errors()->add('DetCodi', sprintf('El codigo de producto %s (%s) no existe', $item['DetCodi'], $item['DetNomb']));
        return false;
      } else {
        $unidad = $producto->unidades->where('Unicodi', $item['UniCodi'])->first();
        if (is_null($unidad)) {
          $validator->errors()->add('UniCodi', "El codigo de la unidad {$item['UniCodi']} del item {$index} es incorrecto cuyo nombre es {$item['DetNomb']}");
          return false;
        }
      }

      $isSol = $this->moneda  == Moneda::SOL_ID;
      // Validar valores 
      $base_imponible = strtoupper($item['DetBase']);
      $is_bolsa = (bool) $producto->icbper;
      $calculator->setValues(
        $item['DetPrec'],
        $item['DetCant'],
        (bool) $item['incluye_igv'],
        $base_imponible,
        $item['DetDcto'],
        $is_bolsa,
        0,
        $unidad->getFactor(),
        $this->tipo_cambio,
        $isSol
      );

      $calculator->calculate();
      $data = $calculator->getCalculos();
      if ($base_imponible = !Venta::GRATUITA && $item['DetImpo'] != $data['total']) {
        $validator->errors()->add('UniCodi', "El total ({$item['DetImpo']}) suministrado del item ({$index}) no coincide no el total correcto de ({$data['total']})");
        return false;
      }

      $data['producto'] = $producto;
      $data['unidad'] = $unidad;
      $data['index'] = $index;

      $this->totales_items[] = $data;
      $index++;
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {

        // Validar serie del documento 

        $empcodi = empcodi();
        $local = auth()->user()->localCurrent()->loccodi;

        if ($this->isStore) {
          $this->series = SerieDocumento::findSerie(
            $empcodi,
            null,
            $this->tipo,
            $local
          );

          if (!$this->series->first()) {
            $nombreDocumento = TipoDocumentoPago::getNombreDocumento($this->tipo);
            $validator->errors()->add('serie_documento', "No existe serie de {$nombreDocumento} para este usuario en el local actual");
            return;
          }
        }

        // Validar Cliente
        // $cliente = ClienteProveedor::find($this->cliente_documento);
        $tipo = $this->isStore ? $this->tipo : Cotizacion::findOrfail($this->id_cotizacion)->TidCodi1;

        $cliente = $tipo == Cotizacion::ORDEN_COMPRA ?
          ClienteProveedor::findProveedor($this->cliente_documento) :
          ClienteProveedor::findCliente($this->cliente_documento);

        if (is_null($cliente)) {
          $validator->errors()->add('cliente_documento', 'No existen un cliente registrado en esta empresa con ese ruc');
          return;
        }

        if (!$cliente->isValidToEmitDoc($this->tipo_documento)) {
          $validator->errors()->add('cliente_documento', 'Este tipo de cliente no puede emitir este tipo de documento');
          return;
        }


        // Validar Forma de pago
        $forma_pago = FormaPago::find($this->forma_pago);
        if (is_null($forma_pago)) {
          $validator->errors()->add('forma_pago', 'Esta forma de pago no existe ');
        }

        // Validar Vendedor
        $zona = Zona::find($this->ZonCodi);
        if (is_null($zona)) {
          $validator->errors()->add('ZonCodi', 'Esta zona no existe');
        }

        // Validar Vendedor
        $vendedor = Vendedor::find($this->vendedor);
        if (is_null($vendedor)) {
          $validator->errors()->add('vendedor', 'Este vendedor no existe');
        }

        // Validar items
        $items = collect($this->items);
        $total =  $items->sum("DetImpo");

        // Validar items
        if (!$this->validateItems($items, $validator)) {
          return;
          }

        if ($total == 0) {
          $validator->errors()->add('importe', 'El importe total no puede ser igual a 0');
        }

        // Validar total
        if (!$this->validateTotal($validator)) {
          return;
        }

        // No puede hacerse una factura asi mimsmp 
        if ($this->cliente_documento == session()->get('empresa_ruc')) {
          $validator->errors()->add('ruc', 'No se puede hacer una factura del mismo ruc');
        }
      });
    }
  }
  public function messages()
  {

    return [
      // 'tipo_cambio' =>  [
      // 	'required' => 'El tipo de cambio es obligatorio'
      // ]
    ];
  }
}
