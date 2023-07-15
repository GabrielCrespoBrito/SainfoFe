<?php 

namespace App\Models\Caja\Method;

use App\CajaDetalle;
use App\Jobs\Caja\ReporteCajaSimplificado;

trait CajaMethod
{
  public function isAbierta()
  {
    return ! $this->isCerrada();
  }

  /**
   * Si fue la ultima caja que se aperturo
   * 
   */
  public function isLastApertura ()
  {
    return self::orderByDesc('MesCodi')
    ->where('CueCodi' , $this->CueCodi)
    ->first()->CajNume == $this->CajNume;
  }

  public static function aperturarTo($usucodi, $loccodi, $empcodi)
  {
    set_timezone();

    $cajas = self::where([
      'EmpCodi'  => $empcodi,
      'LocCodi'  => $loccodi,
      'UsuCodi'  => $usucodi,
    ]);

    $cajNume = $cajas->max('CajNume');

    // No ay cajas existentes de este usuario en ese local y con esa empresa
    if (is_null($cajNume)) {
      $cajNume = "1" . $usucodi . "-000001";
    }
    // Existen otras cajas de ese usuario
    else {
      $nume = explode("-", $cajNume);
      $primera_parte = $nume[0];
      $segunda_parte = agregar_ceros($nume[1]);
      $cajNume = $primera_parte . "-" . $segunda_parte;
    }


    $caja = new self;
    $caja->CajNume = $cajNume;
    $caja->CueCodi = "0000";
    $caja->CajFech = date('Y-m-d');
    $caja->CajSals = 0;
    $caja->CajSalD = 0;
    $caja->CajEsta = "Ap";
    $caja->UsuCodi = $usucodi;
    $caja->CajHora = date('H:m:i');
    $caja->LocCodi = $loccodi;
    $caja->EmpCodi = $empcodi;
    $caja->PanAno  = date('Y');
    $caja->PanPeri = date('m');
    $caja->MesCodi = date('Ym');
    $caja->User_Crea  = user_()->usulogi;
    $caja->User_ECrea = gethostname();
    $caja->save();
    CajaDetalle::registrarApertura($caja);
  }

  public function isCaja()
  {
    return $this->CueCodi == "0000";
  }

  public function nombre()
  {
    return $this->isCaja() ? 'CAJA' : 'BANCO ' . $this->banco->CueNume;
  }

  public function getReporteCajaSimplificadoData()
  {
    // toString
    return (new ReporteCajaSimplificado($this))
    ->handle()
    ->getData();
  }

  public function getNombreEstado()
  {
    return $this->isAperturada() ? 'Aperturada' : 'Cerrada';    
  }

}