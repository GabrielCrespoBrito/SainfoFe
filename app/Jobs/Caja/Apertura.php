<?php

namespace App\Jobs\Caja;

use App\CajaDetalle;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Modelo para aperturar la caja
 * 
 */

class Apertura
{
  use Dispatchable;

  # Petición
  public $request;

  # Si la apertura va hacer un banco
  public $isBanco;

  public function __construct(Request $request, $isBanco)
  {
    $this->request = $request;
    $this->isBanco = $isBanco;
  }

  public function handle()
  {
    $local = $banco ? user_()->localCurrent()->loccodi : $request->id_local;

    $mescodi = $banco ? $request->periodo_id : date('Ym');
    $user = user_();

    $saldoSol = 0.00;
    $saldoDolar = 0.00;
    $lastCaja = Caja::lastCaja($user->usucodi, $local);
    if ($lastCaja) {
      $saldoSol = $lastCaja->CajSalS;
      $saldoDolar = $lastCaja->CajSalD;
    }

    $cuenta =  $banco ? $request->cuenta_id : "0000";
    $caja = new Caja;
    $caja->CajNume = self::getcajNume($request, $banco);
    $caja->CueCodi = $cuenta;
    $caja->CajFech = date('Y-m-d');
    $caja->CajSalS = $saldoSol;
    $caja->CajSalD = $saldoDolar;
    $caja->CajEsta = "Ap";
    $caja->UsuCodi = $user->usucodi;
    $caja->CajHora = '';
    $caja->LocCodi = $local;
    $caja->EmpCodi = empcodi();
    $caja->PanAno  = date('Y');
    $caja->PanPeri = date('m');
    $caja->MesCodi = $mescodi;
    $caja->User_Crea  = $user->usulogi;
    $caja->User_ECrea = gethostname();
    $caja->save();
    CajaDetalle::registrarApertura($caja);
  }
}
