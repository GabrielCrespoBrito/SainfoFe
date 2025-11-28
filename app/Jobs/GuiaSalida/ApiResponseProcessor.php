<?php

namespace App\Jobs\GuiaSalida;

use App\GuiaSalida;
use App\Util\ResultTrait;

class ApiResponseProcessor
{
  use ResultTrait;

  protected $guiaSalida;
  protected $content;
  protected $data = [];

  public function __construct(GuiaSalida $guiaSalida, $content)
  {
    $this->guiaSalida = $guiaSalida;
    $this->content = $content;
  }

  public function getData()
  {
    return $this->data;
  }

  public function processCDR()
  {

    if (property_exists($this->content, 'arcCdr')) {
      $this->data['GuiCDR'] = 1;
      $dataCdr = $this->guiaSalida->saveZipCdr($this->content->arcCdr, $this->data);
      $this->data['fe_rpta_api'] = $dataCdr;
      if (isset($dataCdr['qrLink'])) {
        $this->data['GuiPDF'] = 1;
        $this->data['GuiEOpe'] = 'C';
      }
    }

    else if (property_exists($this->content, 'error')) {
      if( $this->content->error->numError == 1033 ){

        $nameCdr = $this->guiaSalida->nameEnvio('');
        $nameCdrZip = 'R-' .  $nameCdr . '.zip';
        $nameCdrXml = 'R-' . $nameCdr . '.xml';
        $fileHelper = FileHelper($this->guiaSalida->empresa->EmpLin1);
        $exists = $fileHelper->cdrExist($nameCdrZip);
        if ($exists) {
          $content = $fileHelper->getCdr($nameCdrZip);
          $dataCdr = (new ExtractCDRInfo($content, $nameCdrXml))->handle();
          // dd("datacdr",  $dataCdr);
          $this->data['GuiCDR'] = 1;
          $this->data['fe_rpta_api'] = $dataCdr;
          if (isset($dataCdr['qrLink'])) {
            $this->data['GuiPDF'] = 1;
            $this->data['GuiEOpe'] = 'C';
          }
        }
      }
    }
  }

  public function processContent()
  {
    $content = json_decode(json_encode($this->content), true);
    $rpta = $content['codRespuesta'];
    
    unset($content['arcCdr']);
    $fe_rpta_api = $this->data['fe_rpta_api'] ?? [];
    $rpta = $fe_rpta_api['responseCode'] ?? $rpta;
    $this->data['fe_rpta'] = $rpta;
    
    $this->data['fe_rpta_api'] = array_merge($fe_rpta_api,  $content);

    if ($rpta == 0 || ($fe_rpta_api['responseCode'] ?? null) == 0) {
      $this->setSuccess("Cod: $rpta | "  . ($this->data['fe_rpta_api']['descripcion'] ?? '--'));
    }
    else if ($rpta == 98) {
      $this->setSuccess('Envio En Proceso (98)');
    }
    elseif ($rpta == 99) {
      $error = sprintf('Cod: %s | Desc: %s', $content['error']['numError'], $content['error']['desError']);
      $this->setError('Envio Con Error (99) ' . $error);
    }
  }

  public function handle()
  {
    $this->processCDR();
    $this->processContent();
    $this->data['fe_rpta_api'] = json_encode($this->data['fe_rpta_api']);
    $this->guiaSalida->update($this->data);
    return $this;
  }
}
