<?php

namespace App;

use App\Util\ModelUtil\ModelUtil;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\InteractsWithCaja;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Models\Compra\Method\CompraMethod;
use App\Models\Traits\InteractWithPayment;
use App\Models\Compra\Traits\CompraReporte;
use App\Http\Controllers\Compra\CompraTrait;
use App\Models\Compra\Attribute\CompraAttribute;
use App\Models\Compra\Relationship\CompraRelationship;
use App\Models\Traits\InteractWithDocument;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Compra extends Model
{
  use
    CompraRelationship,
    ModelEmpresaScope,
    UsesTenantConnection,
    CompraAttribute,
    CompraMethod,
    CompraTrait,
    InteractWithDocument,
    InteractsWithCaja,
    InteractWithPayment,
    CompraReporte,
    ModelUtil;

  protected $table = "compras_cab";
  protected $primaryKey = "CpaOper";
  protected $CpaOperCero = 6;
  protected $keyType = "string";
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  const EMPRESA_CAMPO = "EmpCodi";
  const INCLUYE_IGV = "1";
  const NO_INCLUYE_IGV = "0";
  
  public $incrementing = false;
  protected $fillable = [
    "TidCodi",
    "CpaSerie",
    "PCcodi",
    "CpaNumee",
    "CpaSdCa",
    "PCNomb",
    "CpaFCpa",
    "moncodi",
    "moncodi",
    "CpaFCon",
    "CpaTCam",
    "CpaFven",
    "VenCodi",
    "concodi",
    'IGVEsta',
    "TpgCodi",
    "GuiOper",
    "VtaPedi",
    "Docrefe",
    "Cpaobse",
    "Cpabase",
    "CpaIGVV",
    "CpaImpo",
    "zoncodi",
    'CpaPago',
    "CpaSald",
    "Cpatota",
    'usuCodi',
    "IGVImpo"
  ];
  
  public static function boot()
  {
    parent::boot();
    static::creating(function (Compra $compra) {
      $user = auth()->user();
      $dates_info = get_date_info( $compra->CpaFCon );
      $empresa = get_empresa();
      $compra->CpaOper =  $compra->getLastIncrement('CpaOper', ['EmpCodi' => $empresa->empcodi]);
      $compra->EmpCodi = $empresa->empcodi;
      $compra->PanAno  = $dates_info->year;
      $compra->PanPeri = $dates_info->month;
      $compra->MesCodi = $dates_info->mescodi;
      $compra->CpaPago = 0;
      $compra->CpaEsPe = "NP";
      $compra->CpaEsPe = "NP";
      $compra->CpaPPer = 0;
      $compra->LocCodi = optional($user)->localCurrent()->loccodi;
      $compra->CpaAPer = 0;
      $compra->CpaPerc = 0;
      $compra->AjuNeto = 0;
      $compra->AjuIGVV = 0;
      $compra->User_ECrea = gethostname();
      $compra->User_Crea = $user->usulogi;
      $compra->IGVImpo = get_option('Logigv');
      $compra->CpaEsta = "C";
      $compra->AjuNeto = 0;
      $compra->AjuIGVV = 0;
      $compra->CpaEsta = 0;
      $compra->CajNume = Caja::currentCaja()->CajNume;
      $compra->AlmEsta = "Pe";
      $compra->TipCodi = "111201";
      $compra->CpaEOpe = "P";
      $compra->CpaNume = $compra->CpaSerie . '-' . $compra->CpaNumee;
    });
    
    static::updating(function (Compra $compra) {
      $dates_info = get_date_info($compra->CpaFCon);
      $compra->PanAno  = $dates_info->year;
      $compra->PanPeri = $dates_info->month;
      $compra->MesCodi = $dates_info->mescodi;
    });
  }

  public function subTotal()
  {
    return is_null($this->CpaOper) ? "0.00" : $this->Cpabase;
  }

}
