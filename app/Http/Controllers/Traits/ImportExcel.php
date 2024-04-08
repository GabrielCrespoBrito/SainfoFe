<?php

namespace App\Http\Controllers\Traits;

use App\Zona;
use App\Grupo;
use App\Marca;
use App\Venta;
use App\DBHelp;
use App\Unidad;
use App\Familia;
use App\Producto;
use App\Vendedor;
use App\VentaItem;
use App\TipoDocumento;
use App\SerieDocumento;
use App\ClienteProveedor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Collections\RowCollection;

class ImportExcel
{
  // Empresa
  public $empresa;
  public $empcodi;
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
  // index
  protected $index;

  // tables a procesar
  const GRUPOS = "grupos";
  const FAMILIAS = "familias";
  const MARCAS = "marcas";
  const PRODUCTOS = "productos";
  const PROV_CLIENTES = "prov_clientes";
  const VENTAS_CAB = "ventas_cab";
  const VENTAS_DETALLE = "ventas_detalle";

  // Tables
  const HEADING_TABLE = [

    self::GRUPOS => [
      'grupo_codigo' => ['name' => 'GruCodi', 'default' => ''],
      'grupo_nombre' => ['name' => 'GruNomb', 'default' => ''],
      'estado' => ['name' => 'GruEsta', 'default' => 0],
    ],

    self::FAMILIAS => [
      'grupo_codigo' => ['name' => 'gruCodi', 'default' => 0],
      'familia_codigo' => ['name' => 'famCodi', 'default' => 0],
      'familia_nombre' => ['name' => 'famNomb', 'default' => 0],
      'estado' => ['name' => 'famEsta', 'default' => 1],
    ],

    self::MARCAS => [
      'marca_codigo' => ['name' => 'MarCodi', 'default' => ""],
      'marca_nombre' => ['name' => 'MarNomb', 'default' => ""],
    ],

    self::PRODUCTOS => [
      'id' => ['name' => 'ID', 'default' => 0],
      'codigo' => ['name' => 'ProCodi', 'default' => 0],
      'codigo_barra' => ['name' => 'ProCodi1', 'default' => null],
      'familia_codigo' => ['name' => 'famcodi', 'default' => 0],
      'grupo_codigo' => ['name' => 'grucodi', 'default' => 0],
      'marca_codigo' => ['name' => 'marcodi', 'default' => 0],
      'nombre' => ['name' => 'ProNomb', 'default' => 0],
      'unidad' => ['name' => 'unpcodi', 'default' => 0],
      'moneda' => ['name' => 'moncodi', 'default' => 0],
      'costo_dolares' => ['name' => 'ProPUCD', 'default' => 0],
      'costo_soles' => ['name' => 'ProPUCS', 'default' => 0],
      'margen' => ['name' => 'ProMarg', 'default' => 0],
      'venta_dolares' => ['name' => 'ProPUVD', 'default' => 0],
      'venta_soles' => ['name' => 'ProPUVS', 'default' => 0],
      'peso' => ['name' => 'ProPeso', 'default' => 0],
      'base_igv' => ['name' => 'BaseIGV', 'default' => 0],
      'tipo_existencia' => ['name' => 'tiecodi', 'default' => 0],
      'proesta' => ['name' => 'proesta', 'default' => 1],
      'stock_almacen1' => ['name' => 'prosto1', 'default' => 0],
      'stock_almacen2' => ['name' => 'prosto2', 'default' => 0],
      'stock_almacen3' => ['name' => 'prosto3', 'default' => 0],
      'stock_almacen4' => ['name' => 'prosto4', 'default' => 0],
      'stock_almacen5' => ['name' => 'prosto5', 'default' => 0],
      'stock_almacen6' => ['name' => 'prosto6', 'default' => 0],
      'stock_almacen7' => ['name' => 'prosto7', 'default' => 0],
      'stock_almacen8' => ['name' => 'prosto8', 'default' => 0],
      'stock_almacen9' => ['name' => 'prosto9', 'default' => 0],
      'provaco' => ['name' => 'provaco', 'default' => 0],
      'proigco' => ['name' => 'proigco', 'default' => 0],
      'promini' => ['name' => 'promini', 'default' => 0],
      'properc' => ['name' => 'properc', 'default' => 0],
      'profoto' => ['name' => 'profoto', 'default' => ''],
      'proigvv' => ['name' => 'proigvv', 'default' => 18],
      'ProcCodi' => ['name' => 'ProcCodi', 'default' => "00"],

    ],

    self::PROV_CLIENTES => [
      'pccodi' =>  ['name' => 'PCCodi', 'default' => null  ],
      'tipo_cliente' => ['name' => 'TipCodi', 'default' => ''],
      'tipo_documento' => ['name' => 'TDocCodi', 'default' => ''],
      'documento' => ['name' => 'PCRucc', 'default' => ''],
      'nombre' => ['name' => 'PCNomb', 'default' => ''   ],
      'direccion' => ['name' => 'PCDire', 'default' => ''],
      'ubigeo' => ['name' => 'PCDist', 'default' => null ],
      'cvend' => ['name' => 'VenCodi', 'default' => null ],
      'zona' => ['name' => 'ZonCodi', 'default' => '0100'],
      'telefono' => ['name' => 'PCTel1', 'default' => '' ],
      'delete' => ['name' => 'UDelete', 'default' => ''  ],

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
      'vtacdr' => ['name' => 'VtaCDR', 'default' => '0'],
      'vtaxml' => ['name' => 'VtaXML', 'default' => '0'],
      'vtapdf' => ['name' => 'VtaPDF', 'default' => '0'],
      'VtaEsPe' => ['name' => 'VtaEsPe', 'default' => '0'],
      'VtaPPer' => ['name' => 'VtaPPer', 'default' => '0'],
      'VtaAPer' => ['name' => 'VtaAPer', 'default' => '0'],
      'VtaPerc' => ['name' => 'VtaPerc', 'default' => '0'],
      'VtaSPer' => ['name' => 'VtaSPer', 'default' => '0'],
      'VtaSdCa' => ['name' => 'VtaSdCa', 'default' => '0'],
      'vtafact' => ['name' => 'vtafact', 'default' => '0'],
      'TipCodi' => ['name' => 'TipCodi', 'default' => '111201'],

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
    return in_array(
      $this->current_table,
      $this->hojas_procesar
    );
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

  public function load(UploadedFile $excel)
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
    $fecha_limite_boleta = date('Y-m-d', strtotime('-30 days'));

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

    $tdc = is_numeric($tipodocumento_cliente) ? (int) $tipodocumento_cliente : $tipodocumento_cliente;
    $documento = is_numeric($documento) ? (int) $documento : $documento;
    $doclen = strlen((string) $documento);

    if ($tdc === TipoDocumento::CEDULA || $tdc === TipoDocumento::DNI || $tdc === TipoDocumento::RUC) {
      if (!is_int($documento)) {


        throw new \Exception($this->generateMessageError("Si el tipo de documento del cliente es ({$tdc}) el n° de documento del cliente debe ser numerico "));
      }
    } else if ($tdc === TipoDocumento::NINGUNA) {
      if ($documento != ".") {
        throw new \Exception($this->generateMessageError("Si el tipo de documento del cliente es (" . TipoDocumento::NINGUNA .  ") el n° del documento tiene que ser simplemente un punto '.'  "));
      }
    } else {
      $error = sprintf(
        'Tipo documento del cliente no permitido, los permitidos son: (%s) (%s) (%s) y (%s)',
        TipoDocumento::NINGUNA,
        TipoDocumento::CEDULA,
        TipoDocumento::DNI,
        TipoDocumento::RUC
      );
      throw new \Exception($this->generateMessageError($error));
    }

    if ($tdc === TipoDocumento::CEDULA || $tdc === TipoDocumento::DNI) {

      if ($doclen != 8) {
        throw new \Exception($this->generateMessageError("El tipo de documento cedula o dni, tiene que ser un entero de 8 digitos"));
      }
    }

    if ($tdc === TipoDocumento::NINGUNA) {
      if ($this->data_row['importe1'] > $this->monto_limite) {
        throw new \Exception($this->generateMessageError("Si el monto de la venta supera los " .  $this->monto_limite . " el tipo de documento del cliente tiene que ser RUC/DNI/CEDULA."));
      }
    }

    if ($tdc === TipoDocumento::RUC) {
      if ($doclen != 11) {
        throw new \Exception($this->generateMessageError("El tipo de documento RUC, tiene que contener 11 digitos"));
      }
    }
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


    if ($this->isCliente()) {

      $this->data_row['pccodi'] = null;

      // Crear Vendedor
      if ($this->data_row['cvend'] && $this->data_row['vendedor']) {
        $vendedor = Vendedor::find($this->data_row['cvend']);
        if (is_null($vendedor)) {
          $vencodi = $this->data_row['cvend'];
          $vennomb = $this->data_row['vendedor'];
          $vendedor = new Vendedor;
          $vendedor->Vencodi = $vencodi;
          $vendedor->vennomb = $vennomb;
          $vendedor->empcodi =  $this->empcodi;
          $this->data_row['VenCodi'] = $this->data_row['cvend'];
          $vendedor->save();
        }
      }


      // Crear Zona
      if ($this->data_row['zona'] ) {
        $zona = Zona::where('ZonNomb', $this->data_row['zona'])->first();
        if (is_null($zona)) {
          $zona = new Zona;
          $zona->ZonCodi = strtoupper(str_random(4));
          $zona->ZonNomb = $this->data_row['zona'];
          $zona->save();
          $this->data_row['zona'] = $zona->ZonCodi;
        }
      }


    }
  }


  public function isCliente()
  {
    return $this->current_table === self::PROV_CLIENTES;
  }

  public function addItem($venta)
  {

    for ($i = 1; $i < 10; $i++) {
      $indexProCodi = 'producto' . $i;

      if (isset($this->data_row[$indexProCodi])) {

        $data = $this->data_row;
        $producto = Producto::findByProCodi($data[$indexProCodi], $this->empcodi);
        $unidad = $producto->unidades->first();
        $item = new VentaItem;
        $item->Linea = VentaItem::nextLinea();
        $item->DetItem = agregar_ceros($i, 2, 0);
        $item->VtaOper = $venta->VtaOper;
        $item->EmpCodi = $venta->EmpCodi;
        $item->UniCodi = $unidad->Unicodi;
        $item->DetUnid = $unidad->UniAbre;
        $item->DetCodi = $data[$indexProCodi];
        $item->DetNomb = $data['nombre' . $i];
        $item->MarNomb = "";
        $item->DetCant = $data['cantidad' . $i];
        $item->DetPrec = $data['precio' . $i];
        $item->DetImpo = $data['importe' . $i];
        $item->DetEsta = "V";
        $item->DetEsPe = 0;
        $item->DetBase = $data['base' . $i];
        $item->DetISC = 0;
        $item->DetISCP = 0;
        $item->DetIGVP = 0;
        $item->DetPercP = 0;
        $item->DetPeso = 0;
        $item->DetPeso = 0;
        $item->save();
      }
    }
  }

  public function insertVenta()
  {
    $newModel   = $this->getModel();
    $data_fixed = $this->dataInsert();
    $newModel->fill($data_fixed)->save();
    SerieDocumento::updateDocumento($newModel->VtaOper);
    $this->addItem($newModel);
    $newModel->calcularTotales();
  }

  public function dataInsert()
  {

    $this->processDataBefore();

    $data_fixed = [];

    foreach (self::HEADING_TABLE[$this->current_table] as $fieldLower => $column) {

      $realFieldName = $column['name'];

      if (isset($this->data_row[$fieldLower])) {
        $value = is_null($this->data_row[$fieldLower]) ? $column['default'] : $this->data_row[$fieldLower];
      } else {
        if (!isset($column['default'])) {
        }
        $value = $column['default'];
      }

      $data_fixed[$realFieldName] = trim(str_replace("'", '', $value));
    }


    if ($this->tableHasEmpCodi()) {
      $name_empcodi = $this->getEmpcodi();
      $data_fixed[$name_empcodi] = empcodi();
    }

    return $data_fixed;
  }


  public function addUnidad($producto)
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



  public function insertOtros()
  {

    $newModel   = $this->getModel();
    $data_fixed = $this->dataInsert();

    // _dd( '$data_fixed', $newModel, $data_fixed );
    // exit();
    
    if ( $this->isCliente()) {
      $data_fixed['PCCodi'] = ClienteProveedor::ultimoCodigo($data_fixed['TipCodi']);
      // unset( $data_fixed['PCCodi'] );
    }
    


    $newModel->fill($data_fixed)->save();

    // En productos hay que insertar undad
    if ($this->current_table == self::PRODUCTOS) {
      Unidad::save_unidad($newModel);
      if (isset($this->data_row['uniabre1'])) {
        $this->addUnidad($newModel);
      }
    }
  }

  public function isVenta()
  {
    return $this->current_table === self::VENTAS_CAB;
  }

  public function insertRegistro()
  {

    if ($this->isVenta()) {
      $this->insertVenta();
    } else {
      $this->insertOtros();
    }
  }

  public function startIndex()
  {
    $this->index = 1;
  }

  public function getIndex()
  {
    return $this->index;
  }

  public function addIndex()
  {
    return $this->index++;
  }

  public function procesarHoja($data_insertar)
  {
    $this->startIndex();

    foreach ($data_insertar->chunk(100) as $datas) {
      foreach ($datas as $data) {
        $this->setDataRow($data);
        $this->addIndex();

        // Venta        
        if (isset($data['tipodocumento'])) {
          if ($this->data_row['tipodocumento'] == null) {
            continue;
          }
          $this->insertRegistro();
        }
        // Otro
        else {
          if (isset($data['codigo'])) {
            if ($data['codigo'] == null) {
              continue;
            }
            $this->insertRegistro();
          } else {
            $this->insertRegistro();
          }
        }
      }
    }
  }

  public function cleanTable($clean = false)
  {
    if ($clean) {
      if ($this->current_table != self::VENTAS_CAB) {
        // DBHelp::TT($this->current_table);
        if ($this->current_table == self::PRODUCTOS) {
          // DBHelp::TT("unidad");                            
        }
      }
    }
  }

  /**
   * Importación
   */


  public function saveDB()
  {
    if (!$this->loaded) {
      throw new \Exception("Tiene que cargar el excel primero");
    }

    $data = $this->getData();

    if ($data instanceof RowCollection) {
      throw new \Exception("El excell tiene que tener al menos 2 hojas, por favor descargar la Plantilla Excell de ejemplo.");
    }

    if (!$data->count()) {
      throw new \Exception("Este excel no tiene hojas");
    }

    foreach ($data as $hojas) {

      $this->current_table = strtolower($hojas->getTitle());


      if ($this->hojaPuedeProcesarse()) {
        $data_insertar = $hojas->toArray();
        // _dd($data_insertar);
        // exit();
        $this->cleanTable();
        $data_insertar = collect($data_insertar);
        $this->procesarHoja($data_insertar);
      } // end if
    } // end foreach hojas

    return "success";
  }

  /**
   * Get the value of empresa
   */
  public function getEmpresa()
  {
    return $this->empresa;
  }

  /**
   * Set the value of empresa
   *
   * @return  self
   */
  public function setEmpresa($empresa)
  {
    $this->empresa = $empresa;

    return $this;
  }
}
