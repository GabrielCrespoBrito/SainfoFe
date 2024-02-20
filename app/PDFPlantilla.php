<?php

namespace App;

use App\Moneda;
use App\Jobs\Empresa\ImgStringInfo;
use App\Jobs\PDFPlantilla\GetDataForCotizacion;
use App\Jobs\PDFPlantilla\GetDataForGuia;
use App\Jobs\PDFPlantilla\GetDataForVenta;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class PDFPlantilla extends Model
{
  use UsesSystemConnection;

  protected $table = "pdf_plantillas";
  public $fillable = [
    'nombre',
    'descripcion',
    'vista',
    'tipo',
    'default',
    'impresion_directa',
    'copias_impresion',
  ];

  public $timestamps = false;

  const  TIPO_VENTA = "ventas";
  const  TIPO_COTIZACION = "cotizaciones";
  const  TIPO_GUIA = "guias";

  const FORMATO_A4  = 'a4';
  const FORMATO_A5  = 'a5';
  const FORMATO_TICKET  = 'ticket';


  public function plantilla_data()
  {
    return $this->hasOne( PDFPlantillaData::class, 'plantilla_id' , 'id' );
  }

  public function isFormatoA4()
  {
    return $this->formato === self::FORMATO_A4;
  }

  public function isFormatoTicket()
  {
    return $this->formato === self::FORMATO_TICKET;
  }

  public function isFormatoA5()
  {
    return $this->formato === self::FORMATO_A5;
  }


  public function isFormatoTicketOrA5()
  {
    return $this->isFormatoA45() || $this->isFormatoTicket();;
  }

  public function getData()
  {
    
    switch ($this->tipo) {
      case 'ventas':
        $dataProvider = new GetDataForVenta($this);
        break;
      case 'cotizaciones':
        $dataProvider = new GetDataForCotizacion($this);
        break;
      case 'guias':
        $dataProvider = new GetDataForGuia($this);
        break;                
    }

    return $dataProvider->handle();
  }


  public function getDataPlantilla()
  {
    return $this->getData();
  }
  //   $e = get_empresa();
  //   $plantilla_data = $this->plantilla_data;
  //   $forma_pago =  $e->formas_pagos->first();
  //   $medio_pago_nombre = $e->medios_pagos->last()->TpgNomb;

  //   $user = $e->userOwner();
  //   $local = $user->localPrincipal();

  //   $venta_fake = $plantilla_data->getVenta( $forma_pago->conCodi );
  //   $cliente = $plantilla_data->getCliente();
  //   $venta = $venta_fake->toArray();
  //   $firma = $venta_fake->dataQR( $e->EmpLin1, $cliente->TDocCodi, $cliente->PCRucc );
  //   $items = $plantilla_data->items;
  //   $opciones = $e->opcion;
  //   // $local = user_()->localPrincipal();
  //   $empresa =  $e->toArray();
  //   $empresa['igv_porc'] = $e->opcion->Logigv;
  //   $bancos = Venta::getFormatBancos($e->bancos->groupBy('BanCodi'));
    
  //   // ---------Dirección---------
  //   $direccion =  $e->getDirecciones();
  //   $telefonos = $local->LocTele;
  //   $condiciones = explode("-", CondicionVenta::getDefault($this->EmpCodi));
  //   $logoDocumento  =  $e->getLogo(  $this->formato );
  //   $decimales = $opciones->DecSole;
  //   $empresa['EmpLogo'] = null;

  //   $venta['empresa'] = null;
  //   $venta['nombre_documento'] = $plantilla_data->nombre_documento;
  //   $size = $this->formato == Venta::FORMATO_TICKET ? 200 : 100;
  //   $qr = \QrCode::format('png')->size($size)->generate($firma);
  //   $qrData = $firma;
  //   $logoMarcaAguaSizes = null;
 
  //   $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
  //   if ($logoMarcaAgua) {
  //     $logoMarcaAguaSizes = (new ImgStringInfo($e->FE_RESO))->getInfo();
  //   }
  
  //   $documento_id = $plantilla_data->documento_id;
  //   $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
  //   $logoSubtitulo = $e->getLogoEncodeSubtitulo();
  //   $guias = ['T001-000001'];
  //   return [
  //     'title' => '',
  //     'venta_rapida' => $e->hasVentaRapida(),
  //     'venta' => $venta,
  //     'venta2' => $venta_fake,
  //     'empresa' => $empresa,
  //     'isPreventa' => false,
  //     'bancos' => $bancos,
  //     'hasFormato' => true,
  //     'logoDocumento'  => $logoDocumento,
  //     'logoMarcaAgua' => $logoMarcaAgua,
  //     'logoMarcaAguaSizes' => $logoMarcaAguaSizes,
  //     'logoSubtitulo' => $logoSubtitulo,
  //     'cliente_correo' => getNombreCorreo($e->EmpLin3),
  //     'correo' => $e->EmpLin3,
  //     'telefonos'     => $telefonos,
  //     'nombre_documento' =>  $plantilla_data->nombre_documento,
  //     'documento_id' => $documento_id,
  //     'condiciones' => $condiciones,
  //     'guias' => $guias,
  //     'decimals' => $decimales,
  //     'direccion' => $direccion,
  //     'contacto' => '',
  //     'vendedor' => 'OFICINA',
  //     'vendedor_nombre' => 'OFICINA',      
  //     'fecha_emision_' => '2022-07-05',
  //     'observacion' => '',
  //     'peso' => 10,
  //     'base' => $this->venta_base,
  //     'igv_porcentaje' => get_option('Logigv'),
  //     'igv' => $this->venta_igv,
  //     'total' => $this->venta_total,
  //     'mostrar_igv' => true,
  //     'orden_campos' => [
  //       'valor_unitario' => false,
  //       'precio_unitario' => true,
  //     ],
  //     'valor_unitario' => 1,
  //     'valor_unitario' => 1,
  //     'venta_rapida' => $e->hasVentaRapida(),
  //     'cifra_letra' => $venta_fake->cifra_letra(),
  //     'cliente'     => $cliente,
  //     'moneda'      => $venta_fake->moneda,
  //     'moneda_abreviatura'  => $venta_fake->moneda->monabre,
  //     'forma_pago'  => $forma_pago,
  //     'medio_pago_nombre' => $medio_pago_nombre,
  //     'items'       => $items,      
  //     'qr'          => $qr,
  //     'firma'          => '',
  //   ];
  // }

  public static function findDefault($tipo, $formato)
  {
    return self::where('tipo', $tipo)
    ->where('formato' , $formato )
    ->where('default' , '1')->first();
  }

  public static function createNew($nombre, $vista, $tipo, $formato, $descripcion = "" )
  {
    $data = [
      'nombre' => $nombre,
      'vista' => $vista,
      'tipo' => $tipo,
      'formato' => $formato,
      'descripcion' => $descripcion,
      'impresion_directa' => 0,
      'copias_impresion' => 0,
      'default' => 0,
    ];
    
    $plantilla = self::create($data);
    $plantilla_default = self::findDefault($tipo, $formato);

    # Plantilla Data Copiar Data
    $plantilla_data_default = $plantilla_default->plantilla_data;
    $plantilla_data_arr = $plantilla_data_default->toArray();
    $plantilla_data_arr['plantilla_id'] = $plantilla->id;
    unset($plantilla_data_arr['id']);
    $plantilla_data = $plantilla->plantilla_data()->create($plantilla_data_arr);

    #Copiar Detalles
    foreach( $plantilla_data_default->items as $item ) {
      $item_data = $item->toArray();
      unset($item_data['id']);
      $item_data['data_id'] = $plantilla_data->id;
      $plantilla_data->items()->create($item_data);
    }
  }


  public function getSetting( $provider )
  {
    $settings = json_decode($this->settings);
    return $settings[$provider];
  }

}