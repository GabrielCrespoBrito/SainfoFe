<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ResumenDetalle extends Model
{
  use UsesTenantConnection;

  protected $table       = 'ventas_ra_detalle';  
  protected $primaryKey = 'numoper'; 
  public $incrementing = false;   
  public $timestamps = false;
  const LETRA_ANULACION = "A";
  protected $keyType = 'string';   
  public $fillable = ["CANINGD" , "CANINGS", 'docNume', 'icbper_value', 'icbper_unit'];

  public function resumen()
  {
    return $this->belongsTo(Resumen::class,'numoper','NumOper')->where('EmpCodi',$this->EmpCodi)->where('DocNume',$this->docNume);
  }

  public function cliente()
  {
    return $this->belongsTo( ClienteProveedor::class , 'PCCodi' , 'PCCodi' );
  }

  public function boleta()
  {
  	return Venta::where('VtaNumee',$this->detNume	)
    ->where('VtaSeri' , $this->detseri)
    ->where('TidCodi' , $this->tidcodi)
    ->where('EmpCodi', $this->EmpCodi )
    ->first();
  }

  public function isNotaCredito(){
    return $this->tidcodi == "07";
  }

  public function documento()
  {
    return $this->boleta();
  }

  public function dec($value){
    return number_format((float)$value, 2, '.', '');
  }  

  public static function dec_($value){
    return number_format((float)$value, 2, '.', '');
  }  


  public function getDetExonAttribute($value){
    return $this->dec($value);
  } 

  public function getDetInafAttribute($value){
    return $this->dec($value);
  } 

  public function getDetIGVAttribute($value){
    return $this->dec($value);
  } 


  public function getDetISCAttribute($value){
    return $this->dec($value);
  } 

  public function getDetTotaAttribute($value){
    return $this->dec($value);
  } 

  public function setDetExonAttribute($value){
    $this->attributes["DetExon"] = $this->dec($value);
  } 

  public function setDetInafAttribute($value){
    $this->attributes["DetInaf"] = $this->dec($value);
  } 

  public function setDetIGVAttribute($value){
    $this->attributes["DetIGV"] = $this->dec($value);
  } 


  public function setDetISCAttribute($value){
    $this->attributes["DetISC"] = $this->dec($value);
  } 
  // dec
  public function setDetTotaAttribute($value){
    $this->attributes["DetTota"] = $this->dec($value);
  } 

  public static function createAnulacion( $resumen , $ids , $tipo_documento = "venta" )
  {
    $resumen = is_string($resumen) ? Resumen::find($resumen) : $resumen;
    $is_venta  = $tipo_documento == "venta";
    $item = 1;
    $r_detalles = [];

    foreach( $ids as $id ) {
      $item_fixed = $item < 10 ? ( "0" . $item ) : $item ;
      $item++;
      $documento = $is_venta ? Venta::find($id) : GuiaSalida::find($id);
      $resumen_detalle = new self;
      $resumen_detalle->EmpCodi  = $resumen->EmpCodi;
      $resumen_detalle->PanAno   = $resumen->PanAno;
      $resumen_detalle->PanPeri  = $resumen->PanPeri;    
      $resumen_detalle->numoper  = $resumen->NumOper;
      $resumen_detalle->docNume  = $resumen->DocNume;
      $resumen_detalle->detfecha = date('Y-m-d');
      $resumen_detalle->DetItem = $item_fixed;    
      $resumen_detalle->DetMotivo = Resumen::ANULACION;
      $resumen_detalle->tidcodi =  $is_venta ? $documento->TidCodi  : "09";
      $resumen_detalle->detseri =  $is_venta ? $documento->VtaSeri  : $documento->GuiSeri;
      $resumen_detalle->DetNume =  $is_venta ? $documento->VtaNumee : $documento->GuiNumee;
      $resumen_detalle->save();  
      array_push( $r_detalles , $resumen_detalle );
    }
    return $r_detalles;
  }

	public static function createDetalle($resumen, $ids, $baja = false , $empcodi = false)
	{

    if( $resumen->items->count()  ){
      foreach( $resumen->items as $item ){        
        $item->delete();
      }
    }

    set_timezone();
    
    $item = 1; 
    $rdetalles = [];

    $empcodi = $empcodi ? $empcodi : session()->get('empresa');
    $empresa =Empresa::find($empcodi);
    foreach( $ids as $id ){

      $item_fixed = $item < 10 ? ("0".$item) : $item ;
      $item++;
      $boleta =  Venta::find($id);
			$resumen_detalle = new self;
      $resumen_detalle->EmpCodi = $resumen->EmpCodi; 
      $resumen_detalle->PanAno  = $resumen->PanAno;
      $resumen_detalle->PanPeri = $resumen->PanPeri;    
      $resumen_detalle->numoper = $resumen->NumOper;
      $resumen_detalle->docNume = $resumen->DocNume;
      $resumen_detalle->DetItem = $item_fixed;
      $resumen_detalle->detfecha = date('Y-m-d');
			$resumen_detalle->tidcodi = $boleta->TidCodi;
			$resumen_detalle->detseri = $boleta->VtaSeri;
			$resumen_detalle->DetNume = $boleta->VtaNumee;
      $resumen_detalle->vtatdr = $boleta->VtaTDR;
      $resumen_detalle->vtaserir = $boleta->VtaSeriR;
      $resumen_detalle->vtanumer = $boleta->VtaNumeR;
      $resumen_detalle->DetMotivo = Resumen::RESUMEN;

      // ------------
      if($boleta->isAnulada()) {                
        $importe = $boleta->items->sum('DetImpo');
        $igv = $importe - ($importe / ((float) "1." . ($empresa->getOpcion('igv')))); 
        $DetGrav = $boleta->items->where('DetBase','GRAVADA')->sum('DetImpo');
        $DetExon = $boleta->items->where('DetBase','EXONERADA')->sum('DetImpo');
        $DetInaf = $boleta->items->where('DetBase','INAFECTA')->sum('DetImpo');
        $DetIGV  = $igv;
        $DetISC  = $boleta->items->sum('DetISC');
        $DetTota = $boleta->items->sum('DetImpo');        
      }
      else {
        $DetGrav = $boleta->Vtabase;
        $DetExon = $boleta->VtaExon;
        $DetInaf = $boleta->VtaInaf;
        $DetIGV  = $boleta->VtaIGVV;
        $DetISC  = $boleta->VtaISC;
        $DetTota = $boleta->VtaImpo;        
      }

			$resumen_detalle->DetGrav = decimal($DetGrav);
			$resumen_detalle->DetExon = decimal($DetExon);
			$resumen_detalle->DetInaf = decimal($DetInaf);
			$resumen_detalle->DetIGV  = decimal($DetIGV);
			$resumen_detalle->DetISC  = decimal($DetISC);
      $resumen_detalle->DetTota = decimal($DetTota);
      $resumen_detalle->icbper_value = $boleta->getBolsaTotal();
      $resumen_detalle->DetTota = decimal($DetTota);      
			$resumen_detalle->PCCodi  = $boleta->PCCodi;
      $resumen_detalle->PCRucc  = $boleta->cliente->PCRucc == "" ? "." : $boleta->cliente->PCRucc;      
			$resumen_detalle->TDocCodi= $boleta->cliente->TDocCodi;
			$resumen_detalle->VtaEsta = $boleta->VtaEsta;
      $resumen_detalle->save();
      array_push( $rdetalles ,$resumen_detalle );
    }    
    return($rdetalles);

    return $rdetalles;
  }
  
  /**
   * Si el detalle es por anulación
   * 
   * @return bool
   */
  public function isAnulacion()
  {
    return $this->DetMotivo === self::LETRA_ANULACION;
  }

  /**
   * El total de impuesto a la bolsa del item
   *
   * @return void
   */
  public function getBolsaUnit()
  {
    return $this->icbper_unit ?? 0;
  }

  /**
   * El total de impuesto a la bolsa del item
   *
   * @return void
   */
  public function getBolsaTotal()
  {
    return $this->icbper_value ?? 0;
  }

}
