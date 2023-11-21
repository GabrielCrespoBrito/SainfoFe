<?php
namespace App\Util\Import\Excell\Ventas;


use App\Grupo;
use App\Marca;
use App\Venta;
use App\DBHelp;
use App\Unidad;
use App\Familia;
use App\TipoIgv;
use App\Producto;
use App\VentaItem;
use App\TipoDocumento;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Venta\Traits\Calculator;
use App\Models\Suscripcion\Caracteristica;
use App\Models\Venta\Traits\CalculatorTotal;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use Maatwebsite\Excel\Collections\RowCollection;
use App\Util\Import\Excell\Ventas\ValidateHojaVentas;

class ImportExcellVentas
{
  // Empresa
  public $empresa;
  public $empcodi;
  public $cantidad = 0;
  public $current_items_calculos;
  /**
   * Los errores de la importación
   *
   * @var array
   */  
  public $errors = [];
  
  public $monto_limite;

  // Si se ha subido o no el excel
  public $loaded = false;
  // Path del excel
  public $path;
  // Data del excel
  public $data;
  // Data de registro unico
  public $data_row;
  // Hojas que se van a procesar del excel
  public $hojas_procesar = [];

  protected $index;

  // tables a procesar
  const GRUPOS = "grupos";
  const FAMILIAS = "familias";
  const MARCAS = "marca";
  const PRODUCTOS = "productos";
  const PROV_CLIENTES = "prov_clientes";
  const VENTAS_CAB = "ventas_cab";
  const VENTAS_DETALLE = "ventas_detalle";

  // Tables
  const HEADING_TABLE = [

    self::GRUPOS => [
      'grucodi' => ['name' => 'GruCodi', 'default' => 0],
      'grunomb' => ['name' => 'GruNomb', 'default' => 0],
      'gruesta' => ['name' => 'GruEsta', 'default' => 0],
    ],

    self::FAMILIAS => [
      'famcodi' => ['name' => 'famCodi', 'default' => 0],
      'famnomb' => ['name' => 'famNomb', 'default' => 0],
      'grucodi' => ['name' => 'gruCodi', 'default' => 0],
      'famesta' => ['name' => 'famEsta', 'default' => 1],
    ],

    self::MARCAS => [
      'marcodi' => ['name' => 'MarCodi', 'default' => ""],
      'marnomb' => ['name' => 'MarNomb', 'default' => ""],
    ],

    self::PRODUCTOS => [
      'id' => ['name' => 'ID', 'default' => 0],
      'procodi' => ['name' => 'ProCodi', 'default' => 0],
      'famcodi' => ['name' => 'famcodi', 'default' => 0],
      'grucodi' => ['name' => 'grucodi', 'default' => 0],
      'marcodi' => ['name' => 'marcodi', 'default' => 0],
      'pronomb' => ['name' => 'ProNomb', 'default' => 0],
      'unpcodi' => ['name' => 'unpcodi', 'default' => 0],
      'moncodi' => ['name' => 'moncodi', 'default' => 0],
      'propucd' => ['name' => 'ProPUCD', 'default' => 0],
      'propucs' => ['name' => 'ProPUCS', 'default' => 0],
      'promarg' => ['name' => 'ProMarg', 'default' => 0],
      'propuvd' => ['name' => 'ProPUVD', 'default' => 0],
      'propuvs' => ['name' => 'ProPUVS', 'default' => 0],
      'propeso' => ['name' => 'ProPeso', 'default' => 0],
      'baseigv' => ['name' => 'BaseIGV', 'default' => 0],
      'tiecodi' => ['name' => 'tiecodi', 'default' => 0],
      'proesta' => ['name' => 'proesta', 'default' => 1],
      'prosto1' => ['name' => 'prosto1', 'default' => 0],
      'prosto2' => ['name' => 'prosto1', 'default' => 0],
      'prosto2' => ['name' => 'prosto1', 'default' => 0],
      'prosto3' => ['name' => 'prosto1', 'default' => 0],
      'prosto4' => ['name' => 'prosto1', 'default' => 0],
      'prosto5' => ['name' => 'prosto1', 'default' => 0],
      'prosto6' => ['name' => 'prosto1', 'default' => 0],
      'prosto7' => ['name' => 'prosto1', 'default' => 0],
      'prosto8' => ['name' => 'prosto1', 'default' => 0],
      'prosto9' => ['name' => 'prosto1', 'default' => 0],
      'prosto10' => ['name' => 'prosto1', 'default' => 0],
      'provaco' => ['name' => 'prosto1', 'default' => 0],
      'proigco' => ['name' => 'prosto1', 'default' => 0],
      'promini' => ['name' => 'prosto1', 'default' => 0],
      'properc' => ['name' => 'prosto1', 'default' => 0],
      'profoto' => ['name' => 'prosto1', 'default' => ''],
      'proigvv' => ['name' => 'prosto1', 'default' => '18'],
      'ProcCodi' => ['name' => 'prosto1', 'default' => "00"],

    ],
    self::PROV_CLIENTES => [
      'pccodi' =>  ['name' => 'PCCodi', 'default' => ''],
      'tipcodi' => ['name' => 'TipCodi', 'default' => ''],
      'tdoccodi' => ['name' => 'TDocCodi', 'default' => ''],
      'pcrucc' => ['name' => 'PCRucc', 'default' => ''],
      'pcnomb' => ['name' => 'PCNomb', 'default' => ''],
      'pcdire' => ['name' => 'PCDire', 'default' => ''],
      'pctel1' => ['name' => 'PCTel1', 'default' => ''],
    ],

    self::VENTAS_CAB => [
      'vtaoper' =>  ['name' => 'VtaOper', 'default' => ''],
      'tipodocumento' =>  ['name' => 'TidCodi', 'default' => ''],
      'serie' =>  ['name' => 'VtaSeri', 'default' => ''],
      'vtanumee' => ['name' => 'VtaNumee', 'default' => ''],
      'fecha' =>  ['name' => 'VtaFvta', 'default' => ''],
      'panano' => ['name' => 'PanAno', 'default' => ''],
      'panperi' => ['name' => 'PanPeri', 'default' => ''],
      'vtanume' => ['name' => 'VtaNume', 'default' => ''],
      'pccodi' => ['name' => 'PCCodi', 'default' => ''],
      'vtafpag' => ['name' => 'vtaFpag', 'default' => ''],
      'vtafven' => ['name' => 'VtaFVen', 'default' => ''],
      'usucodi' => ['name' => 'UsuCodi', 'default' => '0'],
      'concodi' => ['name' => 'ConCodi', 'default' => '01'],
      'zoncodi' => ['name' => 'ZonCodi', 'default' => '0100'],
      'moncodi' => ['name' => 'MonCodi', 'default' => '01'],
      'vencodi' => ['name' => 'Vencodi', 'default' => ''],
      'tipo_cambio' => ['name' => 'VtaTcam', 'default' => '3.33'],
      'vtadcto' => ['name' => 'VtaDcto', 'default' => '0'],
      'vtahora' => ['name' => 'VtaHora', 'default' => '10:00'],
      'vtapago' => ['name' => 'VtaPago', 'default' => '0'],
      'user_ecrea' => ['name' => 'User_ECrea', 'default' => '0'],
      'vtasald' => ['name' => 'VtaSald', 'default' => '0'],
      'vtaobse' => ['name' => 'VtaObse', 'default' => ''],
      'user_crea' => ['name' => 'User_Crea', 'default' => ''],
      'vtaesta' => ['name' => 'VtaEsta', 'default' => 'V'],
      'vtaisc' => ['name' => 'VtaISC', 'default' => '0'],
      'fe_rpta' => ['name' => 'fe_rpta', 'default' => '9'],
      'loccodi' => ['name' => 'LocCodi', 'default' => '001'],
      'mescodi' => ['name' => 'MesCodi', 'default' => '0'],
      'mescodi' => ['name' => 'MesCodi', 'default' => '0'],
      'vtacdr' =>  ['name' => 'VtaCDR', 'default' => '0'],
      'vtafmail' =>['name' => 'VtaFMail', 'default' => StatusCode::CODE_0011 ],
      'vtapdf' =>  ['name' => 'VtaPDF', 'default' => '0'],
      'VtaEsPe' => ['name' => 'VtaEsPe', 'default' => '0'],
      'VtaPPer' => ['name' => 'VtaPPer', 'default' => '0'],
      'VtaAPer' => ['name' => 'VtaAPer', 'default' => '0'],
      'VtaPerc' => ['name' => 'VtaPerc', 'default' => '0'],
      'VtaSPer' => ['name' => 'VtaSPer', 'default' => '0'],
      'VtaSdCa' => ['name' => 'VtaSdCa', 'default' => '0'],
      'vtafact' => ['name' => 'vtafact', 'default' => '0'],
      'TipCodi' => ['name' => 'TipCodi', 'default' => '111201'],
      'UDelete' => ['name' => 'TipCodi', 'default' => ''],
      'observacion' => ['name' => 'VtaObse', 'default' => ''],
    ],

    self::VENTAS_DETALLE => [
      'pccodi' =>  ['name' => 'PCCodi', 'default' => ''],
    ],
  ];



  public $current_table;

  public function __construct(UploadedFile $excel = null, array $hojas_procesar = [])
  {
    $this->empresa = get_empresa();
    $this->empcodi = empcodi();
    $this->monto_limite = get_option('EmpCodOS');

    if (!is_null($excel)) {
      $this->load($excel);
      $this->hojas_procesar = $hojas_procesar;
    }
  }

  public function generateMessageError($message)
  {
    return sprintf('Ha habido un inconveniente en la linea (%s), al guardar los documentos el error es: <br><br> %s', $this->getIndex(), $message);
  }


  public function validate($hoja , $documentos)
  {
    $docLen = count($documentos);

    $validateHoja = new ValidateHojaVentas($hoja);    

    # Validar Información de la Hoja
    if( ! $validateHoja->passes() ){
      $this->setError($validateHoja->getMessage());
      return false;
    }

    # Validar Información de la Hoja
    $validateDocumentos = new ValidateDocumentoVenta($documentos, $this->empresa);

    if ( ! $validateDocumentos->passes() ) {
      $this->setError($validateDocumentos->getErrors());
      return false;
    }

    return true;
  }

  public function getEmpcodi()
  {
    return ($this->current_table == self::PROV_CLIENTES || $this->current_table == self::VENTAS_CAB) ? "EmpCodi" : 'empcodi';
  }
  public function tableHasEmpCodi()
  {
    return ($this->current_table == self::VENTAS_DETALLE) ? false : true;
  }

  public function setData()
  {
   
    $this->data = Excel::load($this->path)->get();
  }

  public function getModel()
  {
    $models = [
      self::GRUPOS   => new Grupo,
      self::FAMILIAS => new Familia,
      self::MARCAS => new Marca,
      self::PRODUCTOS => new Producto,
      self::PROV_CLIENTES => new ClienteProveedor,
      self::VENTAS_CAB => new Venta,
      self::VENTAS_DETALLE => new VentaItem,
    ];
    return $models[$this->current_table];
  }

  public function hojaPuedeProcesarse()
  {
    return in_array($this->current_table, $this->hojas_procesar);
  }

  public function getData()
  {
    return $this->data;
  }

  public function getDataRow()
  {
    return $this->data_row;
  }

  public function setDataRow($data_row)
  {
    $this->data_row = $data_row;
  }

  public function load(UploadedFile  $excel)
  {
    $this->path = $excel->getRealPath();
    $this->loaded = true;
    $this->setData();
  }

  public function processHoja($hoja)
  {
    $this->table_name = strtolower($hoja->getTitle());
  }

  public function validate_date($date)
  {

    $fecha_actual = date('Y-m-d');
    $fecha_documento = $date->format('Y-m-d');
    $timestamp = $date->getTimestamp();

    $fecha_limite_factura = date('Y-m-d',   strtotime('-7 days'));
    $fecha_limite_boleta = date('Y-m-d', strtotime('-60 days'));

    $isBoleta = $this->data_row['tipodocumento'] == '03';

    if ($fecha_documento > date('Y-m-d')) {
      throw new \Exception($this->generateMessageError("La fecha de su documento ({$fecha_documento}) no puede ser superior a la fecha actual ({$fecha_actual})"));
    }

    if ($isBoleta and  ($fecha_documento < $fecha_limite_boleta)) {

      throw new \Exception($this->generateMessageError("La fecha del limite para el envio de boletas es ({$fecha_limite_boleta}) la fecha del documento suministrada es ({$fecha_documento})"));
    }

    if (!$isBoleta and  ($fecha_documento < $fecha_limite_factura)) {

      throw new \Exception($this->generateMessageError("La fecha del limite para el envio de facturas es ({$fecha_limite_factura}) la fecha del documento suministrada es ({$fecha_documento})"));
    }
  }

  public function validate_client($documento, $tipodocumento_cliente, $tipodocumento_venta)
  {
    // $tdc = is_numeric($tipodocumento_cliente) ? (int) $tipodocumento_cliente : $tipodocumento_cliente;
    // $documento = is_numeric($documento) ? (int) $documento : $documento;
    // $doclen = strlen((string) $documento);

    // if ($tdc === TipoDocumento::CEDULA || $tdc === TipoDocumento::DNI || $tdc === TipoDocumento::RUC) {
    //   if (!is_int($documento)) {


    //     throw new \Exception($this->generateMessageError("Si el tipo de documento del cliente es ({$tdc}) el n° de documento del cliente debe ser numerico "));
    //   }
    // } else if ($tdc === TipoDocumento::NINGUNA) {
    //   if ($documento != ".") {
    //     throw new \Exception($this->generateMessageError("Si el tipo de documento del cliente es (" . TipoDocumento::NINGUNA .  ") el n° del documento tiene que ser simplemente un punto '.'  "));
    //   }
    // } else {
    //   $error = sprintf(
    //     'Tipo documento del cliente no permitido, los permitidos son: (%s) (%s) (%s) y (%s)',
    //     TipoDocumento::NINGUNA,
    //     TipoDocumento::CEDULA,
    //     TipoDocumento::DNI,
    //     TipoDocumento::RUC
    //   );
    //   throw new \Exception($this->generateMessageError($error));
    // }

    // if ($tdc === TipoDocumento::CEDULA || $tdc === TipoDocumento::DNI) {

    //   if ($doclen != 8) {
    //     throw new \Exception($this->generateMessageError("El tipo de documento cedula o dni, tiene que ser un entero de 8 digitos"));
    //   }
    // }

    // if ($tdc === TipoDocumento::NINGUNA) {
    //   if ($this->data_row['importe1'] > $this->monto_limite) {
    //     throw new \Exception($this->generateMessageError("Si el monto de la venta supera los " .  $this->monto_limite . " el tipo de documento del cliente tiene que ser RUC/DNI/CEDULA."));
    //   }
    // }

    // if ($tdc === TipoDocumento::RUC) {
    //   if ($doclen != 11) {
    //     throw new \Exception($this->generateMessageError("El tipo de documento RUC, tiene que contener 11 digitos"));
    //   }
    // }
  }

  public function processDataBefore()
  {
    if ($this->isVenta()) {

      $fecha_current = $this->data_row['fecha'];

      $timestamp = $this->data_row['fecha']->getTimestamp();

      $hora = date('H:m:i', $timestamp);
      $fecha = date('Y-m-d', $timestamp);
      $documento_cliente =
        is_numeric($this->data_row['cliente_documento']) ?
        (int) $this->data_row['cliente_documento'] :
        $this->data_row['cliente_documento'];

      $this->validate_date($this->data_row['fecha']);
      $this->validate_client(
        $this->data_row['cliente_documento'],
        $this->data_row['cliente_tipodocumento'],
        $this->data_row['tipodocumento']
      );


      $cliente = ClienteProveedor::where('EmpCodi', $this->empcodi)
        ->where('PCRucc', $documento_cliente)
        ->where('TipCodi', 'C')
        ->first();

      if (is_null($cliente)) {
        $cliente = new ClienteProveedor;
        $cliente->EmpCodi = $this->empcodi;
        $cliente->TipCodi = 'C';
        $cliente->PCCodi = '';
        $cliente->TDocCodi = $this->data_row['cliente_tipodocumento'];
        $cliente->PCNomb  = $this->data_row['cliente_nombre'];
        $cliente->PCRucc  = $documento_cliente;
        $cliente->TdoCodi = $this->data_row['cliente_tipodocumento'];
        $cliente->MonCodi = '01';
        $cliente->save();
      }

      $this->data_row['vtaoper']  = Venta::UltimoId();
      $this->data_row['vtanumee'] = Venta::UltimoCorrelativo($this->data_row['serie'], $this->data_row['tipodocumento']);
      $this->data_row['vtafvta'] = $fecha;
      $this->data_row['vtafpag'] = $fecha;
      $this->data_row['usucodi'] = auth()->user()->usucodi;
      $user_crea =  stripos($this->empresa->EmpNomb, "cambur") === false ? auth()->user()->usulogi : NULL;
      $this->data_row['user_crea'] = $user_crea;
      $this->data_row['user_ecrea'] = gethostname();
      $this->data_row['mescodi'] = date('Ym', $timestamp);
      $this->data_row['vtafven'] = $fecha;
      $this->data_row['vtahora'] = $hora;
      $this->data_row['panano'] =  date('Y', $timestamp);
      $this->data_row['panperi'] = date('m', $timestamp);
      $this->data_row['vtanume'] = $this->data_row['serie'] . '-' . $this->data_row['vtanumee'];
      $this->data_row['pccodi'] = $cliente->PCCodi;
      $this->data_row['vencodi'] = $this->empresa->vendedores->first()->Vencodi;
    }

  }


  public function addItem($vtaoper)
  {
    $this->clearItemsCalculos();
    $this->clearItemsToSave();

    $codeItem = (int) VentaItem::nextLinea();

    for ($i = 1; $i < 10; $i++) {
      $indexProCodi = 'producto' . $i;
      if (isset($this->data_row[$indexProCodi])) {
        $data = $this->data_row;
        $nombre = $data['nombre' . $i];
        $cantidad = $data['cantidad' . $i];
        $precio = $data['precio' . $i];
        
        $producto = Producto::findByProCodi($data[$indexProCodi]);
        
        // logger( $data, $indexProCodi, $producto, $data[$indexProCodi]  ) ;

        $incluye_igv = (bool) $producto->incluye_igv;
        $base = $data['base' . $i];
        
        $descuento_porcentaje =  $data['descuento'.$i] ?? 0;
        $is_bolsa =   $producto->isBolsa();
        $isc_porc =  $producto->iscPorc();

        $calculator = new Calculator();
        $calculator->setValues( $precio, $cantidad, $incluye_igv, $base, $descuento_porcentaje, $is_bolsa, $isc_porc );
        $calculator->calculate();
        $totales = $calculator->data_calculate;

        $unidad = $producto->unidades->first();
        $item = new VentaItem;
        $item->Linea =  agregar_ceros($codeItem,8,0);
        $item->DetItem = agregar_ceros($i, 2, 0);
        $item->VtaOper = $vtaoper;
        $item->EmpCodi = $this->empcodi;
        $item->UniCodi = $unidad->Unicodi;
        $item->DetUnid = $unidad->UniAbre;
        $item->DetCodi = $data[$indexProCodi];
        $item->DetNomb = $nombre;
        $item->MarNomb = "";
        $item->DetCant = $cantidad;
        $item->DetPrec = $precio;
        $item->lote = $totales;
        $item->DetImpo = $totales['total'];
        $item->DetCSol = $totales['costo_soles'];
        $item->DetCDol = $totales['costo_dolares'];
        $item->DetEsta = "V";
        $item->DetEsPe = 0;
        $item->DetBase = $base;
        $item->DetISC = $totales['isc'];
        $item->DetISCP = $totales['isc_porc'];
        $item->Detfact = $totales['factor'];

        $item->DetIGVV = $totales['igv_unitario'];
        $item->DetIGVP = $totales['igv_total'];

        $item->icbper_unit = $totales['bolsa_unit'];
        $item->icbper_value = $totales['bolsa'];
        $item->DetPercP = 0;
        $item->DetPeso = 0;
        $item->DetPeso = 0;
        
        $tipo_igv = TipoIgv::getCodeSunat($item->DetBase) ?? null;
        $item->TipoIGV = $tipo_igv;

        $item->incluye_igv = (int) $incluye_igv;
        // $item->save();
        $codeItem++;
        
        $this->registerCalculoItem($totales);
        $this->registerItemToSave($item);
      }
    }
  }

  public function saveItems()
  {
    $items = $this->getItemsToSave();

    foreach( $items as $item ){
      $item->save();
    }
  }

  /**
   * Obtener totales de los items de la venta actual
   * 
   * @return array
   */
  public function getCalculosItems()
  {
    return $this->current_items_calculos;
  }

  /**
   *  Agregar calculo
   * 
   * @return void
   */
  public function registerCalculoItem($calculos)
  {
    $this->current_items_calculos[] = $calculos; 
  }
  
  /**
   * Limpiar el array de los calculos de los items de la venta actual
   * 
   * @return void
   */
  public function clearItemsToSave()
  {
    $this->current_items_to_save = [];
  }

  /**
   * Obtener totales de los items de la venta actual
   * 
   * @return array
   */
  public function getItemsToSave()
  {
    return $this->current_items_to_save;
  }

  /**
   *  Agregar calculo
   * 
   * @return void
   */
  public function registerItemToSave($item)
  {
    $this->current_items_to_save[] = $item; 
  }
  
  /**
   * Limpiar el array de los calculos de los items de la venta actual
   * 
   * @return void
   */
  public function clearItemsCalculos()
  {
    $this->current_items_calculos = [];
  }



  public function dataInsert()
  {
    $this->processDataBefore();

    $data_fixed = [];

    $columns = self::HEADING_TABLE[$this->current_table];

    foreach ( $columns as $fieldLower => $column) {

      $realFieldName = $column['name'];

      if (isset($this->data_row[$fieldLower])) {
        $value = is_null($this->data_row[$fieldLower]) ? $column['default'] : $this->data_row[$fieldLower];
      } 
      else {
        $value = $column['default'];
      }
      $data_fixed[$realFieldName] = $value;
    }

    if ($this->tableHasEmpCodi()) {
      $name_empcodi = $this->getEmpcodi();
      $data_fixed[$name_empcodi] = empcodi();
    }

    return $data_fixed;
  }

  public function addUnidad( $producto )
  {    
    for ($i = 1; $i < 10; $i++) {
      $campoUniCodiAdicional = 'uniabre' . $i;
      if (isset($this->data_row[$campoUniCodiAdicional])) {
        if ($this->data_row[$campoUniCodiAdicional] != null) {
          Unidad::save_adicional($producto, $this->data_row, $i);
        }
      }
    }
  }

  public function isVenta()
  {
    return $this->current_table === self::VENTAS_CAB;
  }

  public function addTotals( &$data_fixed)
  {
    $calculator = new CalculatorTotal( $this->getCalculosItems() );
    $totales = $calculator->getTotal();
    $data_fixed['icbper'] = $totales->icbper;
    $data_fixed['Vtabase'] = $totales->total_gravadas;
    $data_fixed['VtaIGVV'] = $totales->igv;
    $data_fixed['VtaISC'] = $totales->isc;
    $data_fixed['CuenCodi'] = $totales;
    $data_fixed['VtaDcto'] = $totales->descuento_total;
    $data_fixed['VtaInaf'] = $totales->total_inafecta;
    $data_fixed['VtaExon'] = $totales->total_exonerada;
    $data_fixed['VtaGrat'] = $totales->total_gratuita;
    $data_fixed['Vtacant'] = $totales->total_cantidad;
    $data_fixed['VtaImpo'] = $totales->total_cobrado;
    $data_fixed['VtaSald'] = $totales->total_cobrado;
    $data_fixed['VtaTota'] = $totales->total_cobrado;
  }

  public function insertRegistro()
  {
    $this->sumCantidad();
    $newModel   = new Venta();
    $data_fixed = $this->dataInsert();
    $vtaoper = $data_fixed['VtaOper'];
    $this->addItem( $vtaoper );
    $this->addTotals($data_fixed);

    $newModel = new Venta();
    $newModel->fill($data_fixed)->save();

    $this->saveItems();
    SerieDocumento::updateSerie($newModel);
  }

  public function startIndex()
  {
    $this->index = 1;
  }

  public function getIndex()
  {
    return $this->index;
  }

  public function incrementIndex()
  {
    return $this->index++;
  }

  public function procesarHoja($documentos)
  {
    $documentos_chuck = array_chunk( $documentos , 100 );

    $this->startIndex();
    
    foreach ( $documentos_chuck as $documentos ) {
      foreach ($documentos as $documento ) {
        $this->setDataRow($documento);
        if (isset($documento['tipodocumento'])) {
          if ($documento['tipodocumento'] == null) {
            continue;
          }
          $this->insertRegistro();
        } 
        $this->incrementIndex();
      }
    }
  }

  // public function cleanTable($clean = false)
  // {
  //   if ($clean) {
  //     if ($this->current_table != self::VENTAS_CAB) {
  //       DBHelp::TT($this->current_table);
  //       if ($this->current_table == self::PRODUCTOS) {
  //         DBHelp::TT("unidad");
  //       }
  //     }
  //   }
  // }



  public function importToDB()
  {
    if (!$this->loaded) {
      $this->setError( "Tiene que cargar el excel primero");
      return false;
    }

    $data = $this->getData();

    if ($data instanceof RowCollection) {
      $this->setError("El excell tiene que tener al menos 2 hojas, por favor descargar archivo de ejemplo.");
      return false;
    }

    if (!$data->count()) {
      $this->setError("Este excel no tiene hojas");
      return false;
    }

    $findHojaVentas = false;
    
    foreach ($data as $hoja) {
      
      $this->current_table = strtolower($hoja->getTitle());
      
      if ($this->current_table == 'ventas_cab') {
        $findHojaVentas = true;

        # Documentos
        $documentos = $hoja->toArray();
        
        # Validar hoja
        if( ! $this->validate( $hoja , $documentos ) ){
          return false;
        }
        
        // $data_insertar = collect($data_insertar);
        $this->procesarHoja($documentos);
      } 

    } // end foreach hojas

    if (!$findHojaVentas) {
      $this->setError("No se encontro la hoja ventas_cab, por favor revize el archivo de ejemplo.");
      return false;
    }

    return true;
  }


  /**
   * Undocumented function
   *
   * @return void
   */
  public function saveDB()
  {
    $success = $this->importToDB();

    return [
      'success' => $success,
      'cantidad' => $this->getCantidadRegistros(),
      'errors' => $this->getErrors()
    ];
  }

  /**
   * Sumar registro a la cantidad de documentos agregados
   * 
   * @return int
   */
  public function sumCantidad()
  {
    $this->cantidad += 1;
  }

  /**
   * Cantida de registros guardados
   * 
   * @return int
   */
  public function getCantidadRegistros()
  {
    return $this->cantidad;
  }

  /**
   * Get the value of errors
   */ 
  public function getErrors()
  {
    return $this->errors;
  }

  public function iterateError($error)
  {
    $error = (array) $error;
    $error_arr = [];

    foreach( $error as $key => $err ){

      if( is_array($err) ){
        foreach( $err as $er ){
          $error_arr[] = $er;
        }
      }

      else {
        $error_arr[] = $err;
      }
    }


    return $error_arr;
  }

  /**
   * Set the value of errors
   *
   * @return self
   */ 
  public function setError($error)
  {

    $error =  (array) $error;

    foreach( array_flat($error) as $error_str ){
      $this->errors[] = $error_str;
    }

    return $this;

  }

}
