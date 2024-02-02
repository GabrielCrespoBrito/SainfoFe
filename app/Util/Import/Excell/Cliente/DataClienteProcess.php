<?php
namespace App\Util\Import\Excell\Cliente;

use App\ClienteProveedor;

class DataClienteProcess {

const HEADERS = [
    'tipo_cliente' => 'TipCodi',
    'tipo_documento' => 'TdoCodi',
    'documento' => 'PCRucc',
    'nombre' => 'PCNomb',
    'direccion' => 'PCDire',
    'telefono' => 'PCTel1',
    'correo' => 'PCMail',
    'ubigeo' => 'PCDist',
    'zona' => 'ZonCodi',
    'cvend' => 'VenCodi',
  ];

  protected $zonas;
  protected $user;
  protected $dataProcess;
  protected $dataOriginal;
  protected $currentIdClienteTipoCliente;
  protected $currentIdClienteTipoProveedor;

  public function __construct($zonas)
  {
    $this->zonas = $zonas;
    $this->user = auth()->user()->usulogi;

    $this->currentIdClienteTipoCliente = (int) ClienteProveedor::ultimoCodigo(ClienteProveedor::TIPO_CLIENTE);
    $this->currentIdClienteTipoProveedor = (int) ClienteProveedor::ultimoCodigo(ClienteProveedor::TIPO_PROVEEDOR);
  }

  public function setDataOriginal($dataOriginal)
  {
    $this->dataOriginal = $dataOriginal;
    return $this;
  }

  public function handle()
  {
    $this->prepareProductData();
    return $this;
  }

  public function getValueFromOriginal($campoName)
  {
    return $this->dataOriginal[$campoName];
  }

  public function getHeaderName($campoName)
  {
    return self::getHeader($campoName);
  }

  public static function getHeader($campoName)
  {
    return self::HEADERS[$campoName];
  }



  public function getUbigeo()
  {
    $ubigeo = $this->getValueFromOriginal('ubigeo');
    if ($ubigeo) {
      $numero = explode('.', $ubigeo);
      $ubigeo = $numero[0];
    }

    return $ubigeo;
  }

  public function getId()
  {
    $tipo = $this->getValueFromOriginal('tipo_cliente');

    $id  = $tipo == ClienteProveedor::TIPO_CLIENTE ? $this->currentIdClienteTipoCliente++ : 
    $this->currentIdClienteTipoProveedor++;

    return agregar_ceros($id, 5);
    
  }


  public function prepareProductData()
  {
    $this->dataProcess[ 'PCCodi'] = $this->getId();
    $this->dataProcess[$this->getHeaderName('tipo_cliente')] = $this->getValueFromOriginal('tipo_cliente');
    $this->dataProcess[$this->getHeaderName('tipo_documento')] = (int) $this->getValueFromOriginal('tipo_documento');
    $this->dataProcess[$this->getHeaderName('documento')] = $this->getValueFromOriginal('documento');
    $this->dataProcess[$this->getHeaderName('nombre')] = $this->getValueFromOriginal('nombre');
    $this->dataProcess[$this->getHeaderName('direccion')] = $this->getValueFromOriginal('direccion');
    $this->dataProcess[$this->getHeaderName('telefono')] = $this->getValueFromOriginal('telefono');
    $this->dataProcess[$this->getHeaderName('correo')] = $this->getValueFromOriginal('correo');
    $this->dataProcess[$this->getHeaderName('ubigeo')] = $this->getUbigeo();
    $this->dataProcess[$this->getHeaderName('zona')] = $this->getValueFromOriginal('zona');
    $this->dataProcess[$this->getHeaderName('cvend')] = $this->getValueFromOriginal('cvend');
    $this->dataProcess["User_FCrea"] = date('Y-m-d H:i:s');
    $this->dataProcess["User_FModi"] = date('Y-m-d H:i:s');
    $this->dataProcess["User_ECrea"] = gethostname();
    $this->dataProcess["UDelete"] =  "";

  }

  public function getDataProcess()
  {
    return $this->dataProcess;
  }
}
