<?php

namespace App;

use App\Moneda;
use App\Jobs\Empresa\ImgStringInfo;
use App\Util\PDFGenerator\PDFGenerator;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\PDFPlantilla\GetDataForGuia;
use App\Jobs\PDFPlantilla\GetDataForVenta;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use App\Jobs\PDFPlantilla\GetDataForCotizacion;

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


  public function getSetting( $provider = PDFGenerator::HTMLGENERATOR )
  {
    $settings = json_decode($this->settings);
    return (array) $settings->{$provider};
  }
}