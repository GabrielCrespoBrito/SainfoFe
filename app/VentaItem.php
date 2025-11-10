<?php

namespace App;

use App\Jobs\VentaItem\CreateAnticipoDetalle;
use App\TipoIgv;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Models\Venta\Method\VentaItemMethod;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Venta\Traits\VentaItemInventary;
use App\Models\Venta\Traits\VentaItemCalculates;
use App\Models\Venta\Attribute\VentaItemAttribute;
use App\Util\XmlInformation\XmlInformationResolver;
use App\Models\Venta\Relationship\VentaItemRelationship;
use Illuminate\Support\Facades\Log;

class VentaItem extends Model
{
  use
  UsesTenantConnection,
  VentaItemMethod,
  VentaItemAttribute,
  VentaItemRelationship,
  VentaItemCalculates,
  VentaItemInventary,
  ModelEmpresaScope,
  XmlInformationResolver;

  protected $table = "ventas_detalle";
  protected $primaryKey = "Linea";
  protected $keyType = "string";
  protected $calculos_data = [];
  protected $totals = null;
  public $timestamps = false;   
  public $incrementing = false;
  
  const ID_INIT = "00000000";
  const EMPRESA_CAMPO = "EmpCodi";
  const IGV = 0.18;

  public $fillable = [
    "Linea",
    "EmpCodi",
    "DetItem",
    "VtaOper",
    "DetUnid",
    "DetCodi",
    "DetNomb",
    "MarNomb", 
    "DetCant",
    "DetPrec",
    "DetImpo",
    "UniCodi",
    "DetEsta",
    "DetEsPe",
    "Detfact",
    "DetIGVV",
    "DetIGVP",
    "DetDctoV",
    "DetCSol",
    "DetCDol",
    "DetISC",
    "DetISCP",
    "DetPercP",
    "Estado",
    'DetBase',
    'tipoIGV',
    'DetSdCa',
    'DetIGVV',
    'DetIGVP',
    'DetDctoV',
    'Detfact',
    'DetCSol',
    'DetCDol',
  ];
  public $casts = [
    'lote' => 'array'
  ];

  public function setCalculo($calculo)
  {
    $this->calculos_data = $calculo;
  }

  public function getCalculo()
  {
    return $this->calculos_data;
  }

  public function getTotal( $campo = null )
  {
    if( $this->totals == null ){
      $this->totals = $this->calculos();
    }

    logger('getTotal', [$campo, $this->totals]);

    return $campo ? $this->totals[$campo] : $this->totals;
  }

  public function getPrecio($pUnitario = true)
  {
    return $pUnitario ? 
      $this->getTotal('precio_unitario') : 
      $this->getTotal('valor_unitario'); 
  }



  public static function boot(){
    parent::boot();

    static::created( function (VentaItem $item) {
      
      $nombre   = $item->DetNomb; 
      $venta    = $item->venta;
      $producto = optional($item->producto);
      
      if( (strpos($nombre, '[cantidad]' ) !== false) ){
        $nombre = str_replace("[cantidad]", $item->DetCant , $nombre);
      }

      if( (strpos($nombre, '[CANTIDAD]' ) !== false) ){
        $nombre = str_replace("[CANTIDAD]", $item->DetCant , $nombre);
      }

      if( (strpos($nombre, '[tipo_cambio]' ) !== false)  ){
        $nombre = str_replace("[tipo_cambio]", $venta->VtaTcam , $nombre);
      }

      if( (strpos($nombre, '[TIPO_CAMBIO]' ) !== false)  )
      {
        $nombre = str_replace("[TIPO_CAMBIO]", $venta->VtaTcam , $nombre);
      }

      if( (strpos($nombre, '[moneda]' ) !== false)  )
      {
        $nombre = str_replace("[moneda]", $venta->moneda->monnomb , $nombre);
      }

      if( (strpos($nombre, '[MONEDA]' ) !== false)  ){
        $nombre = str_replace("[MONEDA]", $venta->moneda->monnomb , $nombre);
      }

      $item->update(['DetNomb' => $nombre , 'MarNomb' => optional($producto->marca)->MarNomb, 'Estado' => $producto->tiecodi  ]);

    }); 

  }
  
  public function cantidad()
  {
    return $this->DetCant;
  }
  
  public static function cutNumber($number){
    $numberStr = (string) $number;
    $numberCut =  explode( "." , $numberStr );
    if( count($numberCut) > 1 ){
      $numberFirstPart = $numberCut[0];
      $numberDecimals =  substr( $numberCut[1] , 0, 2 );
      return $numberFirstPart . "." . $numberDecimals;
    }
    else {
      return $number . "." . "00";
    }
  }
  public function venta(){
    return $this->belongsTo(Venta::class,'VtaOper','VtaOper')->where('EmpCodi', $this->EmpCodi );
  }

  public function parent()
  {
    return $this->belongsTo(Venta::class, 'VtaOper', 'VtaOper');
  }

  public function guiasItems(){    

    $guias =  $this->venta->guias();//->pluck('GuiOper')->toArray();

    $items = [];

    foreach( $guias as $guia ){
      $item = 
      $guia
      ->items
      ->where('UniCodi', $this->UniCodi)
      ->where('DetCodi', $this->DetCodi)
      ->first();

      if( !is_null($item) ){
        array_push( $items , $item );
      }

    }

    return collect($items);
  }

  public function descuentoFactor()
  {
    $dcto = ((float) $this->DetDcto);

    return math()->porcFactor($dcto);
  }

  public function producto()
  {
    return $this->belongsTo( Producto::class, 'DetCodi', 'ProCodi' );
  }

  public function dataUtilidad()
  {
    return (object) [
      'cantidad' => $this->DetCant,
      'importe' => $this->DetImpo,
    ];
  }

  /**
   * Obtener el valor de venta por unidad aplicando el igv y el isc, si aplica
   * - Calculo revizado
   * @return float
   */
  public function valorVentaPorUnidad() : float
  {
    $val = $this->DetPrec;

    // Aplicar igv 
    if( $this->hasIGV() && $this->venta->empresa->incluyeIgv() ){
      $val = math()->division( $val , $this->igvDivider() );
    }

    // Aplicar isc
    if ($this->hasISC() ){
      $val = math()->division($val, $this->iscDivider());
    }

    return (float) $val;
  }
  
  /**
   * Obtener el factor dividor ISC para aplicar a operaciones 
   * 
   * @return float
   */
  public function iscDivider()
  {
    return math()->factorDivider($this->DetISCP);
  }
  
  /**
   * Obtener el factor dividor IGV para aplicar a operaciones 
   * 
   * @return float
   */
  public function igvDivider()
  {
    return math()->factorDivider($this->DetIGVV);
  }
  
  public function nombre( $with_comment = false )
  {
    $nombre = $this->DetNomb;
    if( $with_comment ){
      $nombre .= " {$this->DetDeta}";
    }

    return trim($nombre);
  }

  public function precio(){
    return $this->DetPrec;
  }

  public function isGratuito(){
    return $this->DetBase === Venta::GRATUITA;
  }

  public function getRealBase()
  {
    return $this->DetBase;
  }

  public function isInafecto()
  {
    return $this->DetBase === Venta::INAFECTA;
  }

  public function isExonerada()
  {
    return $this->DetBase == Venta::EXONERADA;
  }


  public function notApplyIgv()
  {
    return $this->isExonerada() || $this->isInafecto() || $this->isGratuito();
  }

  public function dec($value){
    return number_format((float)$value, 2, '.', '');
  }
 
  public function getDetImpoAttribute( $value ){
    return $this->dec($value);
  }     

  public static function agregate_cero( $numero = false  , $set = 0 )
  {
    $numero = $numero ? $numero : self::ID_INIT;    
    $cero_agregar = [null,"0000000","000000","00000","0000","000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);
    return $codigoLen < 8 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($codigoNum);
  }

  public static function nextLinea(){
    $last = self::orderByDesc('Linea')->first(); 
    $num = is_null($last) ? false : $last->Linea;
    return self::agregate_cero( $num , 1);
  }

  public static function createItem( 
    $id_factura,
    $items,
    $con_productos_enviados = false,
    $totales_items = null,
    $placa = null,
    $save = true )
  {
    $empcodi = empcodi();
    $index_item = 0;
    $i = 1;
    $valor_icbper = config('app.parametros.bolsa');
    $itemsArr = [];

    foreach( $items as $item )
    {
      $totales_item = $totales_items[$index_item];
      $unidad = $totales_item['unidad'] ;
      $unicodi = isset($item['UniCodi']) ? $item['UniCodi'] : $item['Unicodi'];
      $producto = $totales_item['producto'];
      $tieCodi = is_null($producto)  ? "01" : $producto->tieCodi;
      $detItem = $i < 9 ? ("0".$i) : $i;
      $i++;
      $icbper_unit = $producto->icbper ? $valor_icbper : 0.00;
      
      $it = new VentaItem();
      $it->Linea = self::nextLinea();
      $it->DetItem = $detItem ;           
      $it->VtaOper = $id_factura;
      $it->EmpCodi = $empcodi;
      $it->DetUnid = $unidad->UniAbre;
      $it->UniCodi = $unicodi;
      $it->DetCodi = $item['DetCodi'];  
      $it->DetNomb = $item['DetNomb'];      
      $it->MarNomb = $item['Marca'];
      $it->DetCant = $item['DetCant'];
      $it->DetPrec = $totales_item['precio'];  //  $item['DetPrec'];
      $it->DetPeso = $unidad->UniPeso * $item['DetCant'];
      $it->DetEsta = "V";
      $it->DetEspe = 0;
      $it->lote = $totales_item;
      $it->DetCSol = $totales_item['costos']->sol;
      $it->DetCDol = $totales_item['costos']->dolar;
      // Ganancia
      $it->DetVSol = $totales_item['costo_soles'];
      $it->DetVDol = $totales_item['costo_dolares'];

      // Porc Vendedor 

      $porcVendedor = $unidad->porc_com_vend;

      if($porcVendedor){
        $it->DetPorcVend = $porcVendedor;
        $it->DetPorcVenSol =  math()->porc( $porcVendedor, $it->DetVSol );
        $it->DetPorcVenDol =  math()->porc($porcVendedor, $it->DetVDol);
      }

      $it->GuiOper = null;
      $it->DetSdCa = $con_productos_enviados ? 0 : $item['DetCant'];
      $it->DetDcto = $item['DetDcto'];
      $it->DetDctoV = $totales_item['descuento'];
      $it->Detfact = $totales_item['factor'];
      $it->DetIGVV = $totales_item['igv_unitario'];
      $it->DetIGVP = $totales_item['igv_total'];
      $it->DetISC = $totales_item['isc'];
      $it->DetISCP = $item['DetISP'] ?? ((int) $producto->ISC);
      $it->icbper_value = $totales_item['bolsa'];
      $it->icbper_unit = (float) $icbper_unit;
      $it->DetImpo = $totales_item['total'];
      $it->detfven = strtoupper($placa);
      $it->DetDeta = $item['DetCome'];
      $it->Estado  = $tieCodi;
      $it->DetBase = $item['DetBase'] ?? $producto->BaseIGV;
      $tipo_igv = TipoIgv::getCodeSunat($it->DetBase, $item['TipoIGV'] ?? null  ) ;
      $it->incluye_igv  = $item['incluye_igv'] ?? $producto->incluye_igv;
      $it->DetPercP = 0;
      $it->TipoIGV = $tipo_igv;
      if($save){
        $it->save();
      }
      $index_item++;
      $itemsArr[] = $it;
    }

    return $itemsArr;
  }

  /**
   * Crear el detalle de anticipo  
   */
  public static function  createDetalleAnticipo( $venta_anticipo , $id_factura, $detItem )
  {
    (new CreateAnticipoDetalle( $venta_anticipo, $id_factura, $detItem  ))->handle();
  }


  public function DetDetaFormat()
  {
    return str_replace( "\n", '<br/>' , $this->DetDeta );
  }

  public function porEnviar()
  {
    return $this->DetCant - $this->DetSdCa;
  }

}
