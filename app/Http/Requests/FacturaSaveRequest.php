<?php

namespace App\Http\Requests;

use App\Venta;
use App\Moneda;
use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\Detraccion;
use App\GuiaSalida;
use App\Models\Cierre;
use App\TipoDocumento;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use App\Helpers\DocumentHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Venta\Traits\Calculator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Venta\Traits\CalculatorTotal;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Zona;

class FacturaSaveRequest extends FormRequest
{
  public $total_documento;
  public $cliente_model;
  public $totales_items = [];
  public $canjeQuery;
  public $fp;
  public $empresa;
  public $local;
  public $serie;
  public $guiasIds = null;
  public $anticipoModel = null;
  public $id_nota_venta;
  public $id_proforma;

  public function authorize()
  {
    return true;
  }

  public $id_nota_credito;
  public $id_nota_debito;
  public $id_factura;
  public $id_boleta;
  public $is_nota = false;
  public $quitarIgv;
  public $is_anticipo = false;

  public function rules()
  {
    $this->id_nota_credito = TipoDocumentoPago::NOTA_CREDITO;
    $this->id_nota_debito = TipoDocumentoPago::NOTA_DEBITO;
    $this->id_factura = TipoDocumentoPago::FACTURA;
    $this->id_boleta = TipoDocumentoPago::BOLETA;
    $this->id_nota_venta = TipoDocumentoPago::NOTA_VENTA;
    $this->id_proforma = TipoDocumentoPago::PROFORMA;
    $this->local = auth()->user()->localCurrent()->loccodi;

    $this->empresa = get_empresa();

    $this->quitarIgv = $this->empresa->isQuitarIgvNotaVenta() && $this->tipo_documento == TipoDocumentoPago::NOTA_VENTA;

    $rules = [
      'tipo_documento'    => 'required|in:' .
        $this->id_nota_credito . "," .
        $this->id_nota_debito . "," .
        $this->id_factura . "," .
        $this->id_boleta . "," .
        $this->id_nota_venta . ',' .
        $this->id_proforma,
      'serie_documento'   => 'required',
      'VtaDcto'  => 'required|numeric|min:0',
      'descuento_global'   => 'nullable|sometimes|integer|min:0|max:100',
      'cliente_documento' => 'required',
      'moneda'            => 'required|in:01,02',
      'canje'            =>  'required|in:0,1',
      'tipo_cambio'       => 'required|numeric|min:0',
      'forma_pago'        => 'required',
      'medio_pago'        => 'required|exists:tipo_pago,TpgCodi',
      'vendedor'          => 'required',
      'ZonCodi'          => 'required',
      'observacion'          => 'nullable|sometimes|max:100',
      'descuento_global'   => 'required|numeric|min:0|max:100',
      'nro_pedido'        => 'nullable|sometimes|max:100',
      'hasDetraccion'     => 'required',
      'placa_vehiculo'     => 'nullable|sometimes|max:8',
      'hasAnticipo'     => 'required',
      'guia_tipo'     => sprintf(
        'required|in:%s,%s,%s,%s,%s',
        Venta::GUIA_ACCION_NINGUNA,
        Venta::GUIA_ACCION_INTERNA,
        Venta::GUIA_ACCION_ELECTRONICA,
        Venta::GUIA_ACCION_TRANSPORTISTA,
        VENTA::GUIA_ACCION_ASOCIAR
      ),
      'tipo_cargo_global' => 'nullable|sometimes|in:percepcion,retencion',
      'fecha_emision'     => 'required|date',
      'fecha_vencimiento' => 'required|after_or_equal:' . $this->fecha_emision,
      // ---------------------------------------------------------------------
      'items'          => 'required',
      'items.*.DetCodi' => 'required',
      'items.*.DetNomb' => 'required|max:250',
      'items.*.UniCodi' => 'required',
      'items.*.DetCant' => 'required|numeric|min:0',
      'items.*.DetPrec' => 'required|numeric|min:0',
      'items.*.DetDcto' => 'required|numeric|min:0',
      'items.*.DetCome' => 'nullable|sometimes|max:500',
      'items.*.DetBase' => 'required|in:GRAVADA,INAFECTA,GRATUITA,EXONERADA',
    ];

    $rules['guia_tipo'] = $this->canje == "1" ?
      sprintf('required|in:%s,%s', Venta::GUIA_ACCION_INTERNA, Venta::GUIA_ACCION_ELECTRONICA) :
      sprintf('required|in:%s,%s,%s,%s,%s', Venta::GUIA_ACCION_NINGUNA, Venta::GUIA_ACCION_INTERNA, Venta::GUIA_ACCION_ELECTRONICA, Venta::GUIA_ACCION_TRANSPORTISTA,  VENTA::GUIA_ACCION_ASOCIAR);

    if ($this->tipo_cargo_global == "percepcion" || $this->tipo_cargo_global == "retencion") {
      $rules['cargo_global'] = 'required|integer|min:0|max:100';
    }

    $rule_tipo_documento = '';

    $this->fp = FormaPago::find($this->forma_pago);

    if (optional($this->fp)->hasMultipleDias()) {
      $rules['pagos.*.PgoCodi'] = 'required';
      $rules['pagos.*.dias'] = 'required|integer|min:0';
      $rules['pagos.*.monto'] = 'required|numeric:min:0';
      $rules['pagos.*.fecha_pago'] = 'required|date';
    }

    if (isset($this->guia_remision) && optional($this->guia_remision) == "true") {
      $rules['direccion_llegada'] = 'required';
      $rules['direccion_partida'] = 'required';
      $rules['motivo_traslado'] = 'required';
      $rules['transportista'] = 'required';
      $rules['empresa'] = 'required';
      $rules['placa'] = 'required';
    }

    // if ($this->hasDetraccion == "true") {
    if ($this->detraccionItem) {
      $rules['detraccionItem'] = 'required|exists:detracciones,cod_sunat';
    }

    if ($this->hasAnticipo  == "true") {
      $this->is_anticipo = true;
      $rules['anticipoDocumento'] = 'required';
      $rules['anticipoValue'] = 'required';
    }

    switch ($this->tipo_documento) {
      case $this->id_nota_credito:
      case $this->id_nota_debito:
        $this->is_nota = true;
        $rules['ref_tipo'] = 'required|exists:ventas_tipo_notacredito,id';
        $rules['tipo_seleccion_ref'] = 'required|in:0,1';
        $rules['ref_serie'] = 'required';
        $rules['ref_numero'] = 'required';
        $rules['ref_documento'] = 'required';
        $rules['ref_fecha'] = 'required|date';
        break;
    }

    $rules['cliente_documento'] .= $rule_tipo_documento;
    return $rules;
  }

  public function validateCanjeIds(&$validator)
  {
    if ($this->canje == 0) {
      return true;
    }

    if (!($this->tipo_documento == Venta::FACTURA || $this->tipo_documento == Venta::BOLETA)) {
      $validator->errors()->add('canjeIds', "El canje solamente se puede hacer en Facturas/Boletas");
      return false;
    }

    if (!is_array($this->canjeIds)) {
      if (count($this->canjeIds) == 0) {
        $validator->errors()->add('canjeIds', "Hay ha cargado las Notas de Venta para el canje");
        return false;
      }
    }

    $this->canjeQuery = DB::connection('tenant')->table('ventas_cab')
      ->where('TidCodi', Venta::NOTA_VENTA)
      ->where('VtaFMail', StatusCode::CODE_EXITO_0001)
      ->whereNull('VtaOperC')
      ->whereIn('VtaOper', $this->canjeIds);

    if ($this->canjeQuery->count() != count($this->canjeIds)) {
      $validator->errors()->add('canjeIds', "Hay Notas de Venta Facturada, Que no se encuentra/Ya estan Canjeadas o el Id no existe");
      return false;
    }

    $this->guiasIds = $this->canjeQuery->pluck('GuiOper')->toArray();

    return true;
  }


  public function validateItems($items, &$validator)
  {
    $calculator = new Calculator();

    $index = 0;
    foreach ($items as $item) {
      $indexReal = $index+1;
      $producto = Producto::where('ProCodi', $item['DetCodi'])->first();
      $cant_caracteres = strlen($item['DetNomb']) + strlen($item['DetCome']);

      if ($cant_caracteres > 250) {
        $validator->errors()->add('DetNomb', "La descripciòn y el comentario del producto es de {$cant_caracteres} caracteres y el limite es: 250");
        return false;
      }

      if (is_null($producto)) {
        $validator->errors()->add('DetCodi', 'El codigo de producto es incorrecto');
        return false;
      } else {
        $unidad = $producto->unidades->where('Unicodi', $item['UniCodi'])->first();
        if (is_null($unidad)) {
          $validator->errors()->add('UniCodi', "El codigo de la unidad {$item['UniCodi']} del item {$indexReal} es incorrecto cuyo nombre es {$item['DetNomb']}");
          return false;
        }
      }

      if( (float) $item['DetPrec'] == 0){
          $validator->errors()->add('UniCodi', "El precio del item {$item['DetNomb']} no puede ser 0");
          return false;
      }


      if( (float) $item['DetCant'] == 0){
        $validator->errors()->add('UniCodi', "La cantidad del item {$item['DetNomb']} no puede ser 0");
        return;
      }



      $incluyeIgv = (bool) $item['incluye_igv'];

      // if ($this->quitarIgv) {
      //   $incluyeIgv = false;
      // }

      $isSol = $this->moneda == Moneda::SOL_ID;
      $cantidad = $item['DetCant'];
      $factor = $unidad->getFactor();
      // Validar valores
      $base_imponible = strtoupper($item['DetBase']);
      $is_bolsa = (bool) $producto->icbper;
      $calculator->setValues(
        $item['DetPrec'],
        $cantidad,
        $incluyeIgv,
        $base_imponible,
        $item['DetDcto'],
        $is_bolsa,
        $item['DetISP'],
        $factor,
        $this->tipo_cambio,
        $isSol,
        $unidad->UniPeso,
      );

      $calculator->calculate();
      $data = $calculator->getCalculos();


      // if( $item['precio'] )

      if ($base_imponible == !Venta::GRATUITA && $item['DetImpo'] != $data['total']) {
        $validator->errors()->add('UniCodi', "El total ({$item['DetImpo']}) suministrado del item ({$index}) no coincide no el total correcto de ({$data['total']})");
        return false;
      }

      $incluyeIgv = $producto->incluyeIgv();
      if ($this->quitarIgv) {
        $incluyeIgv = false;
      }

      $data['costos'] = $unidad->getCostos(
        $producto->ProCodi,
        $this->fecha_emision,
        $this->local,
        $cantidad,
        $factor,
        $incluyeIgv,
        $this->empresa->isCostoDeUltimaCompra() == false
      );

      $data['producto'] = $producto;
      $data['unidad'] = $unidad;
      $data['index'] = $index;
      $this->totales_items[] = $data;
      $index++;
    }


    return true;
  }


  public function validateTotal(&$validator)
  {
    $calculator = new CalculatorTotal($this->totales_items);
    $detraccion_porc = $this->detraccionItem ? Detraccion::getPorcentajeDetraccion($this->detraccionItem) : 0;
    $percepcion = $this->tipo_cargo_global == 'percepcion' ? $this->cargo_global : 0;
    $retencion = $this->tipo_cargo_global == 'retencion' ? $this->cargo_global : 0;
    $anticipo_igv = 0;
    $anticipo_value = 0;

    if ($this->is_anticipo) {
      $anticipo_igv = $this->anticipoModel->VtaIGVV;
      $anticipo_value = $this->anticipoModel->Vtabase;
    }

    $calculator->setParameters(
      $this->descuento_global,
      $anticipo_value,
      $detraccion_porc,
      $percepcion,
      $retencion,
      $anticipo_igv
    );

    $totales = $calculator->getTotal();
    $total_calculado = (float) trim($totales->total_cobrado);
    $total_request = (float) trim($this->total_importe);

    $this->merge(['anticipoValue' => $anticipo_value]);
    
    if ($total_calculado != $total_request) {
    
      // @TODO FIXED 
      if( $this->empresa->empcodi != "009" && ($this->total_cobrado - $this->total_importe) > 0.1  ){

        $validator->errors()->add('total', "El total suministrado {$this->total_importe} no coincide con el total correcto calculado ({$totales->total_cobrado})");
        return false;

      }

    }

    if (0 >  $total_calculado) {
      $validator->errors()->add('total', "El total no puede ser negativo");
      return false;
    }


    $this->total_documento = $totales;

    return true;
  }


  public function validateAnticipo(&$validator)
  {
    if (!$this->is_anticipo) {
      return true;
    }

    $this->anticipoModel  = Venta::find(agregar_ceros($this->anticipoDocumento, 6, 0));

    // Validar que el documento de anticipo exista
    if (!$this->anticipoModel) {
      $validator->errors()->add('anticipo', "El documento de anticipo no existe");
      return false;
    }

    // Validar que sea el mismo cliente
    if ($this->anticipoModel->PCCodi != $this->cliente_documento) {
      $validator->errors()->add('anticipo', "El cliente del documento de anticipo tiene que ser el mismo del documento actual");
      return false;
    }

    // Validar que sea el documento de anticipo no este anulado
    if ($this->anticipoModel->isAnulada()) {
      $validator->errors()->add('anticipo', "El documento de anticipo no puede estar anulado");
      return false;
    }

    // Validar que sea el documento de anticipo sea de la misma moneda que el actual
    if ($this->anticipoModel->MonCodi != $this->moneda) {
      $validator->errors()->add('anticipo', "El documento documento de anticipo tiene que ser de la misma moneda que el documento actual");
      return false;
    }

    // Validar que sea el documento de anticipo sea de la misma moneda que el actual
    if ($this->anticipoModel->Vtabase != $this->anticipoValue) {
      $validator->errors()->add('anticipo', "El total del documento de anticipo no es correcto");
      return false;
    }

    // Validar que sea el documento de anticipo sea de la misma moneda que el actual
    if (!($this->anticipoModel->TidCodi == TipoDocumentoPago::FACTURA || $this->anticipoModel->TidCodi == TipoDocumentoPago::BOLETA)) {
      $validator->errors()->add('anticipo', "El total del documento de anticipo tiene que ser una factura o boleta");
      return false;
    }


    // Validar que sea el documento de anticipo sea de la misma moneda que el actual
    if (!($this->tipo_documento == TipoDocumentoPago::FACTURA || $this->tipo_documento == TipoDocumentoPago::BOLETA)) {
      $validator->errors()->add('anticipo', "Solo se puede hacer documento con anticipo si es Factura/Boleta");
      return false;
    }

    return true;
  }

  public function validateCliente(&$validator)
  {
    $cliente = $this->cliente_model;

    if (!$cliente) {
      $validator->errors()->add('cliente_documento', 'El cliente suministrado no existe');
      return false;
    }

    switch ($this->tipo_documento) {

        // Validar Factura
      case TipoDocumentoPago::FACTURA:
        if (!$cliente->isRuc()) {
          $validator->errors()->add('cliente_documento', 'Las Facturas solo pueden emitirse con cliente con RUC');
          return false;
        }
        break;

        // Validar Boleta
      case TipoDocumentoPago::BOLETA:
        if ($cliente->isRuc()) {
          $validator->errors()->add('cliente_documento', 'Las Boletas no puede ser emitidas a cliente con RUC, por favor utilizar Factura');
          return false;
        }
        break;

        // Validar NC
      case TipoDocumentoPago::NOTA_CREDITO:
      case TipoDocumentoPago::NOTA_DEBITO:

        $nombre = $this->tipo_documento == TipoDocumentoPago::NOTA_CREDITO ? 'Notas de Credito' : 'Notas de Debito';
        $isNotaFactura = strtoupper(substr($this->serie_documento, 0, 1)) == "F";

        // Si es una NC/ND para factura 
        if ($isNotaFactura) {
          if (!$cliente->isRuc()) {
            $validator->errors()->add('cliente_documento', "Las {$nombre} para Facturas Solo pueden emitirse a cliente con RUC");
            return false;
          }
        }

        // Si es una NC/ND para boleta
        else {
          if ($cliente->isRuc()) {
            $validator->errors()->add('cliente_documento', "Las {$nombre} para Boletas no pueden emitirse a clientes con RUC");
            return false;
          }
        }
        break;
    }

    return true;
  }


  /*
  */
  public function validateCotizacion(&$validator)
  {
  }


  public function validateFormaPago(&$validator)
  {
    if (is_null($this->fp)) {
      $validator->errors()->add('forma_pago', 'Esta forma de pago no existe ');
      return false;
    }

    if ($this->fp->isContado()) {
      return true;
    }

    $dias = $this->fp->dias;
    $dias_count = $dias->count();
    $pagos = collect($this->pagos);

    if ($dias_count == 1) {
      return true;
    }

    if ($dias->count() !== $pagos->count()) {
      $validator->errors()->add('forma_pago', 'La cantidad de cuotas de la forma de pago no corresponde a la suministrada');
      return false;
    }

    if ($pagos->pluck('PgoCodi')->diff($dias->pluck('PgoCodi'))->count()) {
      $validator->errors()->add('forma_pago', 'La cantidad de cuotas de la forma de pago no corresponde a la suministrada');
      return false;
    }

    // Validar las fechas
    $fecha_cuota = $this->fecha_emision;
    foreach ($pagos as $pago) {
      $fecha_cuota_current = $pago['fecha_pago'];
      if ($fecha_cuota_current <= $fecha_cuota) {
        $validator->errors()->add('forma_pago', "La fecha ({$fecha_cuota_current}) no puede menor o igual que la fecha ({$fecha_cuota})");
        return false;
      }

      $fecha_cuota = $fecha_cuota_current;
    }

    // Validar el total
    $total = $this->total_documento->total_cobrado;

    $total -= ($this->total_documento->retencion +  $this->total_documento->detraccion);

    $monto_total_pagos = decimal($pagos->sum('monto'));
    $total = decimal($total);


    if ($monto_total_pagos > $total) {

      // $this->tipo_cargo_global == "retencion"
      if ($this->tipo_cargo_global == "retencion") {
        $message = "La sumatoria ({$monto_total_pagos}) de los montos en las cuotas, supera el total del documento ({$total}) = Total - Retención";
      }

      if ($this->total_documento->detraccion) {
        $message = "La sumatoria ({$monto_total_pagos}) de los montos en las cuotas, supera el total del documento ({$total}) = Total - Detracciòn";
      } else {
        $message = "La sumatoria ({$monto_total_pagos}) de los montos en las cuotas, supera el total del documento {$total}";
      }


      $validator->errors()->add('forma_pago', $message);
      return false;
    }
  }


  public function validateGuias(&$validator)
  {
    // Si no se trata de asociar guias, no hay nada que evaluar
    if ($this->guia_tipo != "asociar") {
      if ($this->guia_tipo == Venta::GUIA_ACCION_ELECTRONICA) {

        if ($this->tipo_documento == TipoDocumentoPago::NOTA_CREDITO) {
          $validator->errors()->add('tipo_guia', __('validation.guia_remision_notacredito'));
          return false;
        }

        if (!$this->cliente_model->isRucOrDni()) {
          $validator->errors()->add('cliente_documento', __('validation.guia_remision_cliente'));
          return false;
        }
      }
      return true;
    }

    $guias = $this->guias;

    // Si no se agregaròn guias para asociar
    if ($guias == null) {
      return true;
    }

    // Verificar que no se repita los guioper
    if (count(array_unique($guias)) < count($guias)) {
      $validator->errors()->add('guias', 'Numero de guia duplicado');
      return false;
    }

    foreach ($guias as $guia) {
      if (!GuiaSalida::isSunatAddFactura($guia)) {
        $validator->errors()->add('guia', "Error en la guia de remisiión numero: $guia");
        return false;
      }
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {

        // Validar la fecha de emision del documento
        if (
          $this->tipo_documento == TipoDocumentoPago::FACTURA ||
          $this->tipo_documento   == TipoDocumentoPago::BOLETA ||
          $this->tipo_documento   == TipoDocumentoPago::NOTA_CREDITO ||
          $this->tipo_documento   == TipoDocumentoPago::NOTA_DEBITO
        ) {

          if (Cierre::estaAperturadoPorFecha($this->fecha_emision)) {
            $validator->errors()->add('fecha_emision', sprintf('El Mes actual se encuentra Cerrado, para aperturar, <a href="%s" target="_blank"> INGRESE AQUI </a>', route('cierre.index')));
            return;
          }
          if ((new DocumentHelper())->enPlazoDeEnvio($this->tipo_documento,  $this->fecha_emision)) {
            $fecha_limite = (new DocumentHelper())->getFechaLimite($this->tipo_documento);
            $message_error = sprintf("La %s no se puede emitir antes del %s", TipoDocumentoPago::getNombreDocumento($this->tipo_documento), $fecha_limite);
            $validator->errors()->add('serie_documento',  $message_error);
            return;
          }
        }

        $empresa_ruc = $this->empresa->ruc();
        $empcodi = $this->empresa->empcodi;

        $this->serie = SerieDocumento::findSerie(
          $empcodi,
          $this->serie_documento,
          $this->tipo_documento,
          $this->local,
          auth()->user()->usucodi
        )->first();

        if (!$this->serie) {
          $validator->errors()->add('serie_documento', 'Esta serie no existe para este usuario');
          return;
        }

        $this->cliente_model = ClienteProveedor::find($this->cliente_documento);

        // Validar cliente
        if (!$this->validateCliente($validator)) {
          return;
        }

        // Validar Guias
        if (!$this->validateGuias($validator)) {
          return;
        }


        // Validar Vendedor
        $vendedor = Vendedor::find($this->vendedor);
        if (is_null($vendedor)) {
          $validator->errors()->add('vendedor', 'Este vendedor no existe');
        }

        // Validar Zona
        $zona = Zona::find($this->ZonCodi);
        if (is_null($zona)) {
          $validator->errors()->add('ZonCodi', 'Este zona no existe');
        }

        $countGlobalCargo = 0;
        $countGlobalCargo += $this->descuento_global > 0 ? 1 : 0;
        $countGlobalCargo += $this->percepcion > 0 ? 1 : 0;
        $countGlobalCargo += $this->is_anticipo ? 1 : 0;

        if ($countGlobalCargo > 1) {
          $validator->errors()->add('percepcion', 'Solo se puede aplicar un cargo global a la vez: Anticipo / Descuento Global / Percepción');
          return;
        }

        // Validar items
        $items = collect($this->items);
        $total =  $items->sum("DetImpo");
        $cliente_tipo_documento = $this->cliente_model->TDocCodi;

        // Boleta limite
        $boleta_limite = $this->empresa->getOpcion('boleta_limite');

        if ($this->tipo_documento == $this->id_boleta and $total >= $boleta_limite) {

          if (
            $cliente_tipo_documento != TipoDocumento::DNI &&
            $cliente_tipo_documento != TipoDocumento::CEDULA  &&
            $cliente_tipo_documento != TipoDocumento::PASAPORTE
          ) {
            $validator->errors()->add('ruc', 'Tiene que introducir un cliente DNI/Doc.Nac si el importe de la boleta supera los: ' . $boleta_limite . ' S/.');
          }
        }

        // Validar items
        if (!$this->validateItems($items, $validator)) {
          return;
        }

        if (!$this->validateCanjeIds($validator)) {
          return;
        }

        if ($total == 0) {
          $validator->errors()->add('importe', 'El importe total no puede ser igual a 0');
        }

        if (!$this->validateAnticipo($validator)) {
          return;
        }

        // Validar total
        if (!$this->validateTotal($validator)) {
          return;
        }

        // Validar Forma de pago
        if (!$this->validateFormaPago($validator)) {
          return;
        }

        // No puede hacerse una factura asi mimsmp 
        if ($this->cliente_documento == $empresa_ruc) {
          $validator->errors()->add('ruc', 'No se puede hacer una factura del mismo ruc');
        }

        if ($this->is_nota) {

          if ($this->tipo_seleccion_ref == 0) {

            $v_ref = Venta::where('EmpCodi', $empcodi)
              ->where('VtaSeri', $this->ref_serie)
              ->where('VtaNumee', $this->ref_numero)
              ->where('TidCodi', $this->ref_documento)
              ->first();

            if (is_null($v_ref)) {
              $validator->errors()->add('ref_serie', 'No existe este documento de referencia');
              return;
            } elseif ($v_ref->isAnulada()) {
              $validator->errors()->add('ruc', 'No se puede hacer una nota de un documento que ha sido dado de baja');
              return;
            }

            if ($v_ref->MonCodi != $this->moneda) {
              $validator->errors()->add('moneda', 'El tipo de moneda de la nota debe ser el mismo que el declarado en el documento que modifica');
              return;
            }

            if ($this->total_importe > $v_ref->VtaImpo) {
              $validator->errors()->add('moneda', 'El monto del documento actual supera al documento de referencia');
              return;
            }
          }
        }
      });
    }
  }

  public function messages()
  {
    return [
      'items.*.DetUni' => 'Es necesario la unidad del producto',
      'anticipoValue.required' => 'Tiene que poner el total el anticio',
      'anticipoCorrelativo.required' => 'El numero del documento del anticipo debe consignarse',
      'anticipoCorrelativo.regex' => 'El formato del correlativo es invalido tiene que ser (FXXX-XXXXXXX) o (BXXX-XXXXXXX)',
    ];
  }
}
