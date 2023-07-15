<?php

namespace App\Models\Guia\Traits;

use App\Compra;
use App\GuiaSalida;
use App\TipoMovimiento;

trait GuiaInteractWithCompra
{
  public static function storeFromCompra(Compra $compra, $request)
  {
    set_timezone();
    $guia = new GuiaSalida;
    $guia->GuiOper = self::agregate_cero(self::lastId(), 1);
    $guia->EmpCodi = empcodi();
    $guia->PanAno  = date('Y');
    $guia->PanPeri = date('m');
    $guia->EntSal  = $compra->isNotaCredito() ? self::SALIDA : self::INGRESO;
    $guia->GuiSeri = $request->input('serie');
    $guia->GuiNumee = $request->input('numero');
    $guia->GuiNume = $request->input('serie') . '-' . $request->input('numero');
    $guia->GuiFemi = date('Y-m-d');
    $guia->GuiFDes = date('Y-m-d');
    $guia->TmoCodi = TipoMovimiento::DEFAULT_INGRESO;
    $guia->GuiEsta = $compra->isNotaCredito() ? self::SALIDA : self::INGRESO;
    $guia->PCCodi  = $compra->PCcodi;
    $guia->zoncodi  = "0100";
    $guia->vencodi =  $compra->vencodi;
    $guia->guiporp =  0;
    $guia->Loccodi = $request->input('almacen');
    $guia->moncodi = $compra->moncodi;
    $guia->concodi = $compra->concodi;
    $guia->TipCodi = "111201";
    $guia->guiTcam = $compra->CpaTCam;
    $guia->tracodi = null;
    $guia->guiobse = "";
    $guia->guipedi = "";
    $guia->guitbas = $compra->Cpabase;
    $guia->GuiEsPe = self::NO_PROCESADO;
    $guia->docrefe = $compra->getCompleteCorrelativo();
    $guia->guidirp = null;
    $guia->guidisp = null;
    $guia->guidill = null;
    $guia->guidisll = null;
    $guia->motcodi = null;
    $guia->VehCodi = null;
    $guia->User_ECrea = auth()->user()->usulogi;
    $guia->User_EModi = gethostname();
    $guia->mescodi = $compra->MesCodi;
    $guia->usucodi = auth()->user()->usucodi;
    $guia->cpaOper = $compra->CpaOper;
    $guia->IGVEsta = 0;
    $guia->GuiNOpe = null;
    $guia->CtoOper = "";
    $guia->TraOper = null;
    $guia->GuiEFor = 0;
    $guia->GuiEOpe = "P";
    $guia->TippCodi = "P";
    $guia->save();
    return $guia;
  }
}
