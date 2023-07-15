<?php

namespace App;

use App\TipoDocumentoPago;
use Illuminate\Database\Eloquent\Model;
use App\Models\SerieDocumento\Method\SerieDocumentoMethod;

class SerieDocumento extends Model
{
  use SerieDocumentoMethod;

  protected $connection = 'mysql';
  protected $table = 'usuario_documento';
  protected $primaryKey = 'ID';
  public $timestamps  = false;
  const ID_INIT = "000001";
  const CONTINGENCIA_STATE = "1";


  const VENTA_ACTUALIZACION  = "venta";
  const GUIA_ACTUALIZACION  = "guia";
  const COTIZACION_ACTUALIZACION  = "cotizacion";

  // Tipos de documentos
  const TIPO_GUIA_REMISION = "09";
  const FACTURA = "01";

  public $fillable = [
    "empcodi",
    "usucodi",
    "tidcodi",
    "sercodi",
    "numcodi",
    "defecto",
    "loccodi",
    "estado",
    "contingencia",
    "a4_plantilla_id",
    "a5_plantilla_id",
    "ticket_plantilla_id",
    'impresion_directa',
    'cantidad_copias',
    'nombre_impresora',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'usucodi', 'usucodi');
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'empcodi', 'EmpCodi');
  }

  public function getTipo()
  {
    $td = $this->tidcodi;
    // 
    switch ($td) {
      case  TipoDocumentoPago::PROFORMA:
      case  TipoDocumentoPago::ORDEN_PAGO:
       return PDFPlantilla::TIPO_COTIZACION;
      break;
      case  TipoDocumentoPago::GUIA_SALIDA:
        return PDFPlantilla::TIPO_GUIA;
        break;      
      default:
        return PDFPlantilla::TIPO_VENTA;
        break;
    }
  }

  public function local()
  {
    return $this->belongsTo(Local::class, 'loccodi', 'loccodi');
  }

  public function getIdField($formato)
  {
    return [
      PDFPlantilla::FORMATO_A4 => 'a4_plantilla_id',
      PDFPlantilla::FORMATO_A5 => 'a5_plantilla_id',
      PDFPlantilla::FORMATO_TICKET => 'ticket_plantilla_id',
    ][$formato];
  }

  public function documento()
  {
  }

  /**
   * Obtener el siguiente numero 
   * 
   * @return string
   */
  public function getCorrelativeNextNumber(string $separator = '')
  {
    return $this->sercodi . $separator . $this->getNextNumber();
  }

  /**
   * Obtener el siguiente numero 
   * 
   * @return string
   */
  public function getNextNumber()
  {
    return math()->addCero($this->numcodi + 1, 6);
  }

  /**
   * Actualizar al siguiente numero de continuación 
   * 
   * @return string
   */
  public function updateNextNumber($simitarSerie = false)
  {
    $nextNumber = $this->getNextNumber();

    if( $simitarSerie ){

      $series = self::where('tidcodi' , $this->tidcodi )
      ->where('sercodi' , $this->sercodi)
      ->where('empcodi', $this->empcodi )
      ->get();

      foreach( $series as $serie ){
        $serie->update([ 'numcodi' => $nextNumber ]);
      }

      return;
    }

    $this->numcodi = $nextNumber;
    $this->save();

  }


  public static function lastNume($sercodi, $local = null, $empcodi = null, $tidCodi = null)
  {
    $user = $user ?? user_();
    $local = $local ?? $user->local();
    $empcodi = $empcodi ?? empcodi();
    $tidCodi = $tidCodi ?? "09";

    $numcodi = self::OrderByDesc('numcodi')
      ->where('empcodi', $empcodi)
      ->where('loccodi', $local)
      ->where('tidcodi', $tidCodi)
      ->where('sercodi', $sercodi)
      ->where('usucodi', $user->usucodi )
      ->get()
      ->first()
      ->numcodi;

    return agregar_ceros($numcodi, 6, 1);
  }


  public static function getSerie($local = null, $empcodi = null, $tidCodi = null)
  {
    return self::where('empcodi', $empcodi)
      ->where('loccodi', $local)
      ->where('tidcodi', $tidCodi)
      ->first();
  }

  public function nextCorrelativo()
  {
    return agregar_ceros($this->numcodi, 6, 1);
  }


  public static function agregate_cero($numero = false, $set = 0)
  {
    $numero = $numero ? $numero : "00000";
    $cero_agregar = [null, "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);
    return $codigoLen < 6 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public function tipoDocumento()
  {
    return $this->belongsTo(TipoDocumentoPago::class, 'tidcodi', 'TidCodi');
  }


  /**
   * Obtener series
   */

  public static function getSeries($empcodi, $loccodi, $usucodi, $seriesToSearch )
  {
    $documento_serie =
      self::with('tipoDocumento')
      ->where('empcodi', $empcodi )
      ->where('loccodi', $loccodi)
      ->where('usucodi', $usucodi )
      ->whereIn('tidcodi', $seriesToSearch)
      ->get()
      ->groupBy('tidcodi');

    $series_ordenada = [];

    foreach ($documento_serie as $key => $series) {
      $ser = [];

      $nuevo_codigo_defecto = false;

      foreach ($series as $serie) {

        $nuevo_codigo = self::agregate_cero($serie->numcodi, 1);

        if ($nuevo_codigo_defecto === false) {
          $nuevo_codigo_defecto = $nuevo_codigo;
        }

        if ($serie->defecto) {
          $nuevo_codigo_defecto = $nuevo_codigo;
        }

        // dump( $serie->defecto );

        $ser[] = [
          'id'            => $serie->sercodi,
          'nombre'        => $serie->sercodi,
          'ultimo_codigo' => $serie->numcodi,
          'defecto' => $serie->defecto,
          'nuevo_codigo'  => $nuevo_codigo,
        ];
      }

      $data = [
        'nombre' => $serie->tipoDocumento->TidNomb,
        'id' => $key,
        'nuevo_codigo_defecto' => $nuevo_codigo_defecto,
        'series' => $ser,
      ];

      $series_ordenada[] = $data;
    }


    return collect($series_ordenada);
  }

  public static function getSeriesVentas( $exclude_notas = false )
  {
    $user = auth()->user();
    $tiposDocumento= $exclude_notas ?
    TipoDocumentoPago::VALID_TIPO_VENTAS_WITHOUT_NOTAS :
    TipoDocumentoPago::VALID_TIPO_VENTAS;

    return self::getSeries(empcodi(), $user->localCurrent()->loccodi, $user->usucodi, $tiposDocumento );
  }



  public static function ultimaSerie($guia = false, $tipoDoc = GuiaSalida::TIPO_GUIA_REMISION)
  {
    $guiaSimbol = $guia ? "=" : "!=";
    $local_user = auth()->user()->localCurrent();

    // _dd( $local_user, $tipoDoc, $guiaSimbol );
    // exit();

    $documento_serie =
      self::with('tipoDocumento')->where('empcodi', empcodi())
      // ->where('tidcodi', $guiaSimbol, "09")
      ->where('tidcodi', $guiaSimbol, $tipoDoc )
      ->where('loccodi', $local_user->loccodi)
      ->where('usucodi', usucodi())
      ->get()
      ->groupBy('tidcodi');

    $series_ordenada = [];

    foreach ($documento_serie as $key => $series) {
      $ser = [];

      $nuevo_codigo_defecto = false;

      foreach ($series as $serie) {

        $nuevo_codigo = self::agregate_cero($serie->numcodi, 1);

        if ($nuevo_codigo_defecto === false) {
          $nuevo_codigo_defecto = $nuevo_codigo;
        }

        if ($serie->defecto) {
          $nuevo_codigo_defecto = $nuevo_codigo;
        }

        // dump( $serie->defecto );

        $ser[] = [
          'id'            => $serie->sercodi,
          'nombre'        => $serie->sercodi,
          'ultimo_codigo' => $serie->numcodi,
          'defecto' => $serie->defecto,
          'nuevo_codigo'  => $nuevo_codigo,
        ];
      }

      $data = [
        'nombre' => $serie->tipoDocumento->TidNomb,
        'id' => $key,
        'nuevo_codigo_defecto' => $nuevo_codigo_defecto,
        'series' => $ser,
      ];

      $series_ordenada[] = $data;
    }


    return collect($series_ordenada);
  }



  public static function findSerieRemision($local)
  {
    return self
      ::where('empcodi', session()->get('empresa'))
      ->where('loccodi', $local)
      ->where('usucodi', auth()->user()->usucodi)
      ->where('tidcodi', TipoDocumentoPago::guiaremisionId())
      ->get();
  }

  public function plantillaA4()
  {
    return $this->hasOne(PDFPlantilla::class,  'id', 'a4_plantilla_id');
  }

  public function plantillaA5()
  {
    return $this->hasOne(PDFPlantilla::class, 'id', 'a5_plantilla_id');
  }

  public function plantillaTicket()
  {
    return $this->hasOne(PDFPlantilla::class, 'id', 'ticket_plantilla_id');
  }


  public static function findSerie($empcodi, $vtaseri = null, $tidcodi = null, $local = null, $usucodi = false)
  {

    $serie = self::where('empcodi', $empcodi);

    if ($vtaseri) {
      $serie->where('sercodi', $vtaseri);
    }

    if ($tidcodi) {
      $serie->where('tidcodi', $tidcodi);
    }

    if ($local) {
      $serie->where('loccodi', $local);
    }

    if ($usucodi) {
      $serie->where('usucodi', $usucodi);
    }    
    return $serie->get();
  }

  public static function updateDocumento($id, $documento = self::VENTA_ACTUALIZACION)
  {
    if ( $documento == self::VENTA_ACTUALIZACION ) {
      $venta   = Venta::find($id);
      $empcodi = $venta->EmpCodi;
      $seri    = $venta->VtaSeri;
      $tidcodi = $venta->TidCodi;
      $loccodi = $venta->LocCodi;
    }
    else if( $documento == self::COTIZACION_ACTUALIZACION ){      
      $cotizacion   = Cotizacion::find($id);
      $empcodi = $cotizacion->EmpCodi;
      $seri    = $cotizacion->getSerieNumeracion();
      $tidcodi = $cotizacion->TidCodi1;
      $loccodi = $cotizacion->LocCodi;
    }
    else {
      $guia = GuiaSalida::find($id);
      $empcodi = $guia->EmpCodi;
      $seri    = $guia->GuiSeri;
      $tidcodi = $guia->getTipoDocumento();
      $loccodi = $guia->Loccodi;
    }

    $docs = self::findSerie($empcodi, $seri, $tidcodi, $loccodi);
    $nuevo = agregar_ceros( $docs->first()->numcodi, 6, 1 );
    foreach ($docs as $doc) {
      $doc->update([ 'numcodi' => $nuevo ]);
    }
  }

  public static function updateDocumentoByGuia(GuiaSalida $guia)
  {
    $docs = self::findSerie($guia->EmpCodi, $guia->GuiSeri,  $guia->getTipoDocumento(), $guia->Loccodi);
    $nuevo = agregar_ceros($docs->first()->numcodi, 6, 1);
    foreach ($docs as $doc) {
      $doc->update([ 'numcodi' => $nuevo ]);
    }
  }


  public static function updateSerie(Venta $venta)
  {
    $empcodi = $venta->EmpCodi;
    $seri    = $venta->VtaSeri;
    $tidcodi = $venta->TidCodi;
    $loccodi = $venta->LocCodi;

    $docs = self::findSerie($empcodi, $seri, $tidcodi, $loccodi);
    $nuevo = agregar_ceros($docs->first()->numcodi, 6, 1);
    foreach ($docs as $doc) {
      $doc->update(['numcodi' => $nuevo]);
    }
  }



  public static function deleteDocumento($id)
  {
    $venta = Venta::find($id);

    $docs = self::findSerie($venta->EmpCodi, $venta->VtaSeri, $venta->TidCodi, $venta->LocCodi);

    $nuevo_numero = agregar_ceros($docs->first()->numcodi, 6, -1);

    foreach ($docs as $doc) {
      // $nuevo = agregar_ceros($docs->first()->numcodi, 6, -1);
      $doc->update(['numcodi' => $nuevo_numero ]);
    }
  }


  public function isTipoPlantilla($tipo)
  {
    return TipoDocumentoPago::getNombreTipoForPlantilla($this->tidcodi) == $tipo; 
  }

  public static function updateSeries( $empcodi, $tidcodi, $sercodi, $numcodi )
  {
    $docs = self::findSerie($empcodi, $sercodi, $tidcodi );

    foreach ($docs as $doc) {
      $doc->update(['numcodi' => agregar_ceros($numcodi,6,0)]);
    }
  }


  public static function reverseNumeracion($model)
  {
    if ($model instanceof Venta) {
      $empcodi = $model->EmpCodi;
      $serie = $model->VtaSeri;
      $tidcodi = $model->TidCodi;
      $local = $model->LocCodi;
    } else if ($model instanceof GuiaSalida) {
      $empcodi = $model->EmpCodi;
      $serie = $model->GuiSeri;
      $tidcodi =  $model->getTipoDocumento();
      $local = $model->Loccodi;
    }


    $series = self::findSerie($empcodi, $serie, $tidcodi, $local);
    $numcodi = optional($series->first())->numcodi;

    // Actualizar
    foreach ($series as $serie) {
      $nuevo = agregar_ceros($numcodi, 6, -1);
      $serie->update(['numcodi' => $nuevo]);
    }
  }


}
