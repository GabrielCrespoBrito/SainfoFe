<?php

namespace App\Jobs\Guia;

use App\Moneda;
use App\GuiaSalida;
use App\TipoMovimiento;
use App\ClienteProveedor;
use App\Models\Produccion\Produccion;
use App\TipoCambioPrincipal;
use App\Models\TomaInventario\TomaInventario;

class CreateFromProduccionManual 
{
  public $produccion;
  public $tipo_guia;
  public $isIngreso;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(Produccion $produccion, $isIngreso)
  {
    $this->produccion = $produccion;
    $this->isIngreso = $isIngreso;
  }

  public function handle()
  {
    $empresa = get_empresa();
    $empcodi = $this->produccion->empcodi;
    $produccionId = $this->produccion->manId;
    $tipoGuia = $this->isIngreso ? GuiaSalida::INGRESO : GuiaSalida::SALIDA;
    $idTipoMovimiento = $this->isIngreso ? 
      TipoMovimiento::INGRESO_PRODUCCION : 
      TipoMovimiento::SALIDA_PRODUCCION;
      
    $fechas = get_date_info($this->produccion->manFechCulm);

    $guia = new GuiaSalida;
    $guia->GuiOper = GuiaSalida::agregate_cero(GuiaSalida::lastId(), 1);;
    $guia->EmpCodi = $empcodi;
    $guia->PanAno  = $fechas->year;
    $guia->PanPeri = $fechas->month;
    $guia->EntSal  = TipoMovimiento::getByCode($idTipoMovimiento);;
    $guia->GuiSeri  = $this->produccion->getSerieGuia($this->isIngreso);
    $guia->GuiNumee = $this->produccion->getNumeroGuia();
    $guia->GuiNume = $produccionId;
    $guia->GuiUni = null;
    $guia->GuiFemi = $this->produccion->manFechCulm;
    $guia->GuiFDes = $this->produccion->manFechCulm;
    $guia->TmoCodi = $idTipoMovimiento;
    $guia->GuiEsta = $tipoGuia;
    $guia->PCCodi  = ClienteProveedor::DEFAULT_CODIGO_ALMACEN;
    $guia->zoncodi  = "0100";
    $guia->vencodi = $empresa->vendedores->first()->Vencodi;
    $guia->Loccodi = auth()->user()->localCurrent()->loccodi;
    $guia->moncodi = Moneda::SOL_ID;;
    $guia->guiTcam = TipoCambioPrincipal::ultimo_cambio(false);;
    $guia->tracodi = "";
    $guia->guiobse = 'PRODUCCIÓN MANUAL: ' . $produccionId;
    $guia->guipedi = null;
    $guia->guicant = 0;
    $guia->guitbas = 0;
    $guia->guiporp = 0;
    $guia->GuiEsPe = "NP";
    $guia->docrefe = $produccionId;
    $guia->guidirp = null;
    $guia->guidisp = null;
    $guia->guidill = null;
    $guia->guidisll = null;
    $guia->motcodi = null;
    $guia->VehCodi = null;
    $guia->concodi = "01";
    $guia->mescodi = $this->produccion->mesCodi;
    $guia->usucodi = auth()->user()->usucodi;
    $guia->TipCodi = '111201';
    $guia->cpaOper = '0';
    $guia->fe_rpta = '0';
    $guia->fe_obse = 'Creada de Producción Manual ' . $produccionId;
    $guia->vtaoper = null;
    $guia->TidCodi = null;
    $guia->IGVEsta = 0;
    $guia->User_Crea = $this->produccion->USER_CREA;
    $guia->User_ECrea = $this->produccion->USER_ECREA;
    $guia->GuiNOpe = null;
    $guia->TraOper = null;
    $guia->GuiEFor = $this->isIngreso ? GuiaSalida::CON_FORMATO : GuiaSalida::SIN_FORMATO;
    $guia->GuiEOpe = GuiaSalida::CERRADA;
    $guia->TippCodi = $this->isIngreso ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;
    $guia->save();
    
    return $guia;
  }
}