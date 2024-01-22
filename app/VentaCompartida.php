<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaCompartida extends Model
{
  protected $table = "ventas_compartidas";
  public $fillable = [
    'vtaoper' , 'ruc' , 'tidcodi', 'serie', 'documento',
  ];

  public static function compartir($documento)
  {

    if( self::where('vtaoper', $documento)->first()){
      return false;
    } 

    $venta = Venta::find( $documento );

    $fileHelper = FileHelper();


    if ( ($venta->VtaPDF && $fileHelper->pdfExist($venta->nameFile('.pdf'))) == false ) {
      
      $pdfResult = $venta->generatePDF(PDFPlantilla::FORMATO_A4, true, true);
    }

    self::create([
      'vtaoper'  => $documento,
      'ruc'    => get_ruc(),
      'serie'  => $venta->VtaSeri,
      'tidcodi'  => $venta->TidCodi,
      'documento' => $venta->VtaNumee
    ]);
  }

  public static function isCompartido($ruc, $tidcodi, $serie, $numero)
  {
    return self::where('ruc', $ruc)
    ->where('serie', $serie)
    ->where('tidcodi', $tidcodi)
    ->where('numero', $numero)
    ->count();
  }  

}
