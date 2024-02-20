<?php

namespace App;

use App\Venta;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use App\Http\Controllers\PDFPlantillaController;

class PDFPlantillaData extends Model
{
  use UsesSystemConnection;
  protected $table = "pdf_plantillas_data";
  public $guarded = [];
  public $timestamps = false;

  public function getCliente()
  {
    $cliente = new ClienteProveedor([
      'PCNomb' => $this->nombre_cliente,
      'PCodi' =>  ClienteProveedor::DEFAULT_CODIGO,
      'PCRucc' => $this->documento_cliente,
      'PCDire' => 'AV. NICOLAS AYLLON NRO. 1202 CHACLACAYO - LIMA - LIMA',
      'PCMail' => $this->correo_cliente,
      'ubigeo' => $this->ubigeo_cliente,
      'TDocCodi' => TipoDocumento::RUC,      

    ]);
    return $cliente;
  }

  public function items()
  {
    return $this->hasMany( PDFPlantillaDataDetalle::class, 'data_id' );
  }

  public function getVenta( $forma_pago_codigo )
  {
    $empresa = get_empresa();
    $venta = new Venta();
    $venta->VtaObse = $this->venta_observacion ?? '';
    $venta->VtaFvta = $this->venta_fecha ?? date('Y-m-d');
    $venta->Vencodi = $empresa->vendedores->first()->Vencodi;
    $venta->User_Crea  = $empresa->users->first()->usulogi;
    $venta->VtaPedi = $this->venta_pedi ?? '';
    $venta->Vtabase = $this->venta_base ?? 0;
    $venta->TidCodi = $this->venta_tipo_documento ?? '';
    $venta->MonCodi = $this->venta_moneda ?? Moneda::SOL_ID;
    $venta->VtaImpo = $this->venta_total ?? Moneda::SOL_ID;
    $venta->VtaIGVV = $this->venta_igv ?? Moneda::SOL_ID;
    $venta->ConCodi = $forma_pago_codigo;
    $venta->fe_firma = Str::random(16);
    return $venta;
  }
  public function getGuia()
  {
    $empresa = get_empresa();
    $guia = new GuiaSalida();
    $fechas = get_date_info(date('Y-m-d'));
    list($serie, $numero) = explode('-', $this->documento_id);
    $guia->GuiSeri  = $serie;
    $guia->GuiNumee = $numero;
    $guia->GuiNume = $this->documento_id;
    $guia->GuiUni = null;
    $guia->GuiFemi = $fechas->full;
    $guia->GuiFDes = $fechas->full;
    $guia->TmoCodi = null;
    $guia->GuiEsta = "S";
    $guia->PCCodi  = ClienteProveedor::DEFAULT_CODIGO;
    $guia->zoncodi  = "0100";
    $guia->vencodi = null;
    $guia->Loccodi = null;
    $guia->moncodi = null;
    $guia->guiTcam = null;
    $guia->tracodi =  $empresa->transportistas->first()->TraCodi;
    $guia->guiobse = null;
    $guia->guipedi = '000001';
    $guia->guicant = null;
    $guia->guitbas = null;
    $guia->guiporp = 1000;
    $guia->GuiEsPe = "NP";
    $guia->docrefe = 'F001-000001';
    $guia->guidirp = null;
    $guia->guidisp = '070101';
    $guia->guidill = 'El Rosario, Travessera Pons, 1, 72º F';
    $guia->guidisll = '150118';
    $guia->VehCodi = $empresa->vehiculos->first()->VehCodi;
    $guia->concodi = "01";
    $guia->vencodi = "0001";
    $guia->mescodi =  $fechas->month;
    $guia->usucodi = null;
    $guia->TipCodi = null;
    $guia->cpaOper = NULL;
    $guia->motcodi = MotivoTraslado::first()->MotCodi;
    $guia->vtaoper = null;
    $guia->TidCodi = '09';
    $guia->IGVEsta = 0;
    $guia->GuiNOpe = null;
    $guia->TraOper =  $empresa->empresasTransporte->first()->EmpCodi;
    $guia->GuiEFor = GuiaSalida::CON_FORMATO;
    $guia->GuiEOpe =  GuiaSalida::CERRADA;
    $guia->TippCodi = ClienteProveedor::TIPO_CLIENTE;
    return $guia;
  }
}