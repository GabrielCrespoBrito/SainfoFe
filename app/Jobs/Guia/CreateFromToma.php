<?php

namespace App\Jobs\Guia;

use App\Moneda;
use App\GuiaSalida;
use App\TipoMovimiento;
use App\ClienteProveedor;
use App\TipoCambioPrincipal;
use App\Models\TomaInventario\TomaInventario;

class CreateFromToma
{
  public $tomaInventario;
  public $tipo_guia;
  public $is_ingreso;


  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(TomaInventario $tomaInventario, $tipo_guia)
  {
    $this->tomaInventario = $tomaInventario;
    $this->tipo_guia = $tipo_guia;
    $this->is_ingreso = $tipo_guia == GuiaSalida::INGRESO;
  }

  public function handle()
  {
    $empresa = get_empresa();

    $tipoGuia = $this->is_ingreso ? GuiaSalida::INGRESO : GuiaSalida::SALIDA;    
    $id_tipo_movimiento =  $this->is_ingreso ? TipoMovimiento::INGRESO_INVENTARIO : TipoMovimiento::INVENTARIO_SALIDA;
    $fechas = get_date_info($this->tomaInventario->InvFech);
    $nume = agregar_ceros(GuiaSalida::lastNume( $this->tomaInventario->empcodi, $tipoGuia ), 6);
    //

    $guia = new GuiaSalida;
    $guia->GuiOper = GuiaSalida::agregate_cero(GuiaSalida::lastId(), 1);;
    $guia->EmpCodi = $this->tomaInventario->empcodi;
    $guia->PanAno  = $fechas->year;
    $guia->PanPeri = $fechas->month;
    $guia->EntSal  = TipoMovimiento::getByCode($id_tipo_movimiento);;
    $guia->GuiSeri  = null;
    $guia->GuiNumee = null;
    $guia->GuiNume = $nume;
    $guia->GuiUni = null;
    $guia->GuiFemi = $this->tomaInventario->InvFech;
    $guia->GuiFDes = $this->tomaInventario->InvFech;
    $guia->TmoCodi = $id_tipo_movimiento;
    $guia->GuiEsta = $tipoGuia;
    $guia->PCCodi  = ClienteProveedor::DEFAULT_CODIGO_ALMACEN;
    $guia->zoncodi  = "0100";
    $guia->vencodi = $empresa->vendedores->first()->Vencodi;
    $guia->Loccodi = $this->tomaInventario->LocCodi;
    $guia->moncodi = Moneda::SOL_ID;;
    $guia->guiTcam = TipoCambioPrincipal::ultimo_cambio(false);;
    $guia->tracodi = "";
    $guia->guiobse = 'TOMA INVENTARIO: ' . $this->tomaInventario->InvNomb;
    $guia->guipedi = null;
    $guia->guicant = 0;
    $guia->guitbas = 0;
    $guia->guiporp = 0;
    $guia->GuiEsPe = "NP";
    $guia->docrefe = '';
    $guia->guidirp = null;
    $guia->guidisp = null;
    $guia->guidill = null;
    $guia->guidisll = null;
    $guia->motcodi = null;
    $guia->VehCodi = null;
    $guia->concodi = "01";
    $guia->mescodi = $this->tomaInventario->mescodi;
    $guia->usucodi = auth()->user()->usucodi;
    $guia->TipCodi = '111201';
    $guia->cpaOper = '0';
    $guia->fe_rpta = '0';
    $guia->fe_obse = 'Guia Creada de Toma de Inventario ' . $this->tomaInventario->InvNomb;
    $guia->vtaoper = null;
    $guia->TidCodi = null;
    $guia->IGVEsta = 0;
    $guia->User_Crea = $this->tomaInventario->user_Crea;
    $guia->User_ECrea = $this->tomaInventario->User_ECrea;
    $guia->GuiNOpe = null;
    $guia->TraOper = null;
    $guia->GuiEFor = $this->is_ingreso ? GuiaSalida::CON_FORMATO : GuiaSalida::SIN_FORMATO;
    $guia->GuiEOpe = GuiaSalida::CERRADA;
    $guia->TippCodi = $this->is_ingreso ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;
    $guia->save();

    return $guia;
    
  }
}
