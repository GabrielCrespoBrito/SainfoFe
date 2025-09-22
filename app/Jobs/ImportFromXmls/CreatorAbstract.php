<?php

namespace App\Jobs\ImportFromXmls;

use App\GuiaSalida;
use App\Venta;
use App\Resumen;
use App\Util\ResultTrait;
use Illuminate\Support\Facades\DB;

abstract class CreatorAbstract
{
  use ResultTrait;
  public $xmlContent;
  public $empresa;
  public $data;
  public $cacheTemp;
  public $fileId;
  public $fileTipo;
  public $fileSerie;
  public $fileNumero;

  public function __construct($xmlContent, $empresa, &$cacheTemp, $fileTipo, $fileSerie, $fileNumero)
  {
    $this->xmlContent = $xmlContent;
    $this->empresa = $empresa;
    $this->cacheTemp = $cacheTemp;
    $this->fileTipo = $fileTipo;
    $this->fileSerie = $fileSerie;
    $this->fileNumero = $fileNumero;
    $this->fileId = $fileTipo . '-' . $fileSerie . '-' . $fileNumero;
  }

  public abstract function generateData();
  public abstract function saveDataModel();

  public function existRegister()
  {
    $ft = $this->fileTipo;

    if ($ft == "01" || $ft == "03" || $ft == "07" || $ft == "08") {
      return Venta::where('VtaUni', $this->fileId)->exists();
    }

    if ($ft == "RA" || $ft == "RC") {
      return Resumen::where('DocNume', $this->fileId)->exists();
    }

    return GuiaSalida::where('GuiUni', $this->fileId)->exists();
  }

  public function handle()
  {
    if ($this->existRegister()) {
      $this->setError('El registro ya existe');
      return $this;
    }

    try {
      DB::connection('tenant')->beginTransaction();
      $this->generateData();
      $this->saveDataModel();
      DB::connection('tenant')->commit();
      $this->setSuccess($this->data);
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollBack();
      dd($th);
      logger('error import from xmls', [$th]);
      $this->setError($th->getMessage());
    }
    return $this;
  }
}
