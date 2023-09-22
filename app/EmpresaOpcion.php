<?php

namespace App;

use App\TipoDocumentoPago;
use Illuminate\Database\Eloquent\Model;

class EmpresaOpcion extends Model
{
  protected $connection = "mysql";
  protected $table       = 'opciones_emp';  
  public $timestamps   = false;
  protected $primaryKey = 'UltCpra';  
  public $fillable = [ 
  "UltCpra", 
  "LogEmpr", 
  "Logfond", 
  "LogArti", 
  "Logigv", 
  "logrent", 
  "AfPerc", 
  "PrecImp", 
  "CostImp",
  "venCodi", 
  "zoncodi", 
  "tpgCodi", 
  "MonCodi", 
  "CcoCodi", 
  "bacDias", 
  "FilUsua", 
  "concodi", 
  "probusc", 
  "CotItems", 
  "PosCoAr", 
  "ModPVta", 
  "ClaAuto1", 
  "ClaAuto2", 
  "ClaAuto3", 
  "Tiempo", 
  "AlmProd", 
  "DecSole", 
  "DecDola", 
  "Filtro", 
  "DesAuto", 
  "ImpDcto", 
  "ClaDeuda", 
  "ImpVta", 
  "EmpSerie", 
  "EmpCodOS", 
  "EmpUnid", 
  "ImpSald", 
  "EmaServ", 
  "EmaPuer", 
  "EmaClav", 
  "OpcLista", 
  "OpcDcto", 
  "OpcConta", 
  "OpcPCant", 
  "FE_RESO", 
  "FE_DATA", 
  "FE_RPTA", 
  "FE_ENVIO", 
  "FE_REPO", 
  "FE_CERT", 
  "FE_CLAVE", 
  "FE_USUNAT", 
  "FE_UCLAVE", 
  "EmpCodi"
];

  const MODULO_CANJE =  'modulo_canje_nv';
  const MODULO_MANEJO_STOCK =  'modulo_manejo_stock';
  const MODULO_PRECIO_UNICO =  'modulo_precio_unico';
  const MODULO_RESTRICCION_VENTA_STOCK =  'modulo_restriccion_venta_por_stock';
  const MODULO_PRODUCCION_MANUAL =  'modulo_produccion_manual';
  
  const MODULOS = [
    self::MODULO_CANJE,
    self::MODULO_MANEJO_STOCK,
    self::MODULO_PRECIO_UNICO,
    self::MODULO_RESTRICCION_VENTA_STOCK,
    self::MODULO_PRODUCCION_MANUAL,
  ];

  const FIELDS_MODIFY = [
  'MonCodi',
  'CcoCodi',
  'probusc',
  'PosCoAr',
  'DecSole',
  'DecDola',
  'DesAuto',
  'EmpUnid',
  'ImpSald',
  'OpcConta',
  ];

  const NOTFIELDSHOW = ['UltCpra', "EmaClav", "EmaPuer", "EmaServ", "LogEmpr" , "Logfond" , "LogArti" , "FE_RESO" , "FE_DATA", "FE_RPTA", "FE_ENVIO", "FE_REPO", "FE_CERT", 'Logigv',  "FE_CLAVE", "FE_USUNAT", "FE_UCLAVE", "EmpCodi" , 'zoncodi' ];

  const FIELD_CONEXION = "FE_CERT";

  const FIELD_NAMES = [
    "LogEmpr" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'LogEmpr'],
    "Logfond" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Logfond'],
    "LogArti" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'LogArti'],
    "logrent" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'logrent'],
    "AfPerc"  => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'AfPerc'],
    "PrecImp" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'PrecImp'],
    "CostImp" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'CostImp'],
    "venCodi" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'venCodi'],
    "zoncodi" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'zoncodi'],
    "tpgCodi" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'tpgCodi'],
    
    //
    "MonCodi" => [
      'rules_validation' => 'required|in:01,02',
      'required' => true,
      'default' => '01',
      'type' => 'select',
      'options' => ['01' => 'Sol', '02' => 'Dolares'],
      'name' => 'Moneda'
    ],

    "CcoCodi" => [
      'rules_validation' => 
      "required|in:01,03,07,08,52,50", 'required' => true, 'default' => '01', 'type' => 'select', 'options' => [
      '01' => 'FACTURA',
      '03' => 'BOLETA',
      '07' => 'NOTA DE CREDITO',
      '08' => 'NOTA DE DEBITO',
      '50' => 'COTIZACION',
      '52' => 'NOTA DE VENTA',
      TipoDocumentoPago::NOTA_VENTA =>  'NOTA DE VENTA'
    ], 
    'name' => 'Tipo de documento seleccionado por defecto
    '],

    "bacDias" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'bacDias'],
    "FilUsua" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FilUsua'],
    "concodi" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'concodi'],
    
    "probusc" => [ 'rules_validation' => 'required|in:0,1', 'required' => true, 'default' => 0, 'type' => 'select' , 'options' => [ 0 => 'Codigo' , 1 => 'Nombre'  ] , 'name' => 'Campo del producto al que apuntar con el cursor por defecto'],


    "CotItems"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'CotItems'],

    "PosCoAr" => ['rules_validation' => 'required|in:0,1', 'required' => true, 'default' => 0, 'type' => 'select', 'options' => [0 => 'Tipo de documento', 1 => 'Producto'], 'name' => 'Cursor inicial'],
    // PosCoAr

    "ModPVta" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ModPVta'],
    "ClaAuto1"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ClaAuto1'],
    "ClaAuto2"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ClaAuto2'],
    "ClaAuto3"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ClaAuto3'],
    "Tiempo"  => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Tiempo'],
    "AlmProd" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'AlmProd'],
    
    "DecSole" => [ 'rules_validation' => 'required|integer|min:1|max:8', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Decimales de las cifras en soles'],

    "DecDola" => [ 'rules_validation' => 'required|integer|min:1|max:8', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Decimales de las cifras en dolares'],
    
    "Filtro"  => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input', 'options' => [], 'name' => 'Filtro'],
    "Logigv " => [ 'rules_validation' => 'required|integer', 'required' => true, 'default' => null, 'type' => 'number' , 'options' => [] , 'name' => 'Porcentaje del igv'],

    "DesAuto" => [ 'rules_validation' => 'required|in:0,1,2', 'required' => true, 'default' => 1, 'type' => 'select', 'options' => [ 0 => 'Sin Guia', 1 => 'Guia Interna', 2 => 'Guia Electroncia' ], 'name' => 'Opciòn para generar guias por defecto'],

    "ImpDcto" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ImpDcto'],
    "ClaDeuda"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ClaDeuda'],
    "ImpVta"  => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'ImpVta'],
    "EmpSerie"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'EmpSerie'],
    "EmpCodOS"=> [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'EmpCodOS'],
    "EmpUnid" => [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Unidad por defecto'],

    "ImpSald" => [ 'rules_validation' => 'required|in:0,1', 'required' => true, 'default' => 1, 'type' => 'select' , 'options' => [ 1 => 'Si', 0 => 'No'  ] , 'name' =>  'Mostrar Deudas Clientes'],
    
    "EmaServ" => [ 'rules_validation' => 'required|max:40', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' =>  'Servidor de correos'],

    "EmaPuer" => [ 'rules_validation' => 'required|max:40', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' =>  'Puerto del servidor de correo'],
    
    "EmaClav" => [ 'rules_validation' => 'required|max:45', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' =>  'Contraseña del correo'],

    "OpcLista"=>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'OpcLista'],
    "OpcDcto" =>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'OpcDcto'],
    
    "OpcConta"=>  [ 'rules_validation' => 'required|in:0,1', 'required' => true, 'default' => 1, 'type' => 'select' , 'options' => [1 => 'Si', 0 => 'No'], 'name' => 'Realizar Pagos'],

    "OpcPCant"=>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'OpcPCant'],
    "FE_RESO" =>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FE_RESO'],
    "FE_DATA" =>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FE_DATA'],
    "FE_RPTA" =>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FE_RPTA'],
    "FE_ENVIO"=>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FE_ENVIO'],
    "FE_REPO" =>  [ 'rules_validation' => '', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'FE_REPO'],

    "FE_CERT" =>  [ 'rules_validation' => 'required|max:45', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Nombre Certificado'],

    "FE_CLAVE"=>  [ 'rules_validation' => 'required|max:45', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' => 'Clave Certificado'],

    "FE_USUNAT"=> [ 'rules_validation' => 'required|max:45', 'required' => true, 'default' => null, 'type' => 'input' , 'options' => [] , 'name' =>  'Usuario certificado'],

    "FE_UCLAVE"=> [ 'rules_validation' => 'required|max:45', 'required' => true, 'default' => null, 'type' => 'input', 'options' => [], 'name' =>  'Clave certificado Sunat'],
  ];

  
  /**
   * Campo para saber que input va a tener el cursor por defecto para la busqueda del producto
   * @example probusc = 0  // Codigo
   * @example probusc = 1  // Nombre
   */
  const CAMPO_CURSOR_PRODUCTO = 'probusc';
  const CAMPO_CURSOR_INICIAL = 'PosCoAr';  
  const CONSULTAR_DEUDA_CLIENTE = 'ImpSald';

  public function getInfoSetting($field)
  {
    return  self::FIELD_NAMES[$field] ?? null;
  }


  public function getDataConexionTienda()
  {
    return $this->{self::FIELD_CONEXION};
  }

  public static function lastUltCpra()
  {
    return agregar_ceros( self::max('UltCpra') , 7 , 1 );
  }

  public function getParameterValidToModify()
  {
    return $this->only(self::FIELDS_MODIFY);
  }


  public function updateOpcion($opc, $value)
  {
    $this->{$opc} = $value;
    $this->save();
  }

  public function getTipoCambioPublicoAttribute()
  {
    return $this->attributes['FE_RPTA'];
  }

  public static function createDefault( $empcodi )
  {
    $data = self::where('EmpCodi', '001')->first()->toArray();
    $data['UltCpra'] = self::lastUltCpra();
    $data['ImpDcto'] = 0;
    $data['DecSole'] = 2;
    $data['DecDola'] = 2;
    $data['EmpCodi'] = $empcodi;
    self::create($data);
  }

}