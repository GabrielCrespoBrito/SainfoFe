<?php
namespace App\Util\Import\Excell\Ventas;


use App\Grupo;
use App\Marca;
use App\Venta;
use App\DBHelp;
use App\Unidad;
use App\Familia;
use App\Producto;
use App\VentaItem;
use App\TipoDocumento;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\Models\Suscripcion\Caracteristica;
use App\TipoDocumentoPago;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Collections\RowCollection;
use App\Util\Import\Excell\Ventas\ValidateHojaVentas;


class RegisterVenta 
{
  public $documento;

  public function __construct( array $documento )
  {
    $this->documento = $documento;  
  }

  public function saveDetalles()
  {    
  }

  public function saveVenta()
  {

  }


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

  // public function __construct(UploadedFile $excel = null, array $hojas_procesar = [])
  // {
  //   $this->empresa = get_empresa();
  //   $this->empcodi = empcodi();
  //   $this->monto_limite = get_option('EmpCodOS');

  //   if (!is_null($excel)) {
  //     $this->load($excel);
  //     $this->hojas_procesar = $hojas_procesar;
  //   }
  // }


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

  public function insertOtros()
  {
    $newModel   = $this->getModel();
    $data_fixed = $this->dataInsert();
    Venta::insert($data_fixed);
  }



  public function isVenta()
  {
    return $this->current_table === self::VENTAS_CAB;
  }


  
  public function insertRegistro()
  {
    $newModel   = $this->getModel();

    $data_fixed = $this->dataInsert();
    $newModel->fill($data_fixed)->save();
    SerieDocumento::updateDocumento($newModel->VtaOper);
    $this->addItem($newModel);

    $newModel->calcularTotales();


    // if ($this->isVenta()) {
    //   $this->insertVenta();
    // } else {
    //   $this->insertOtros();
    // }
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


    
    foreach ( $documentos_chuck as $documentos) {
      foreach ($documentos as $documento ) {

        $this->setDataRow($documento);
        $this->incrementIndex();
        $this->insertRegistro();
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
      'errors' => $this->getErrors()
    ];
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
