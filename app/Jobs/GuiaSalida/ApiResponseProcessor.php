<?php

namespace App\Jobs\GuiaSalida;

use App\Util\ResultTrait;

class ApiResponseProcessor
{
  use ResultTrait;

  protected $guiaSalida;
  protected $content;
  protected $data = [];

  public function __construct($guiaSalida, $content)
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
      if(isset($dataCdr['qrLink'])){
        $this->data['GuiPDF'] = 1;
        $this->data['GuiEOpe'] = 'C';
      }
    }
  }

  public function processContent()
  {
    $content = json_decode(json_encode($this->content), true);
    $rpta = $content['codRespuesta'];
    unset($content['arcCdr']);
    $this->data['fe_rpta'] = $rpta;
    $fe_rpta_api = $this->data['fe_rpta_api'] ?? [];

    $this->data['fe_rpta_api'] = array_merge( $fe_rpta_api,  $content);
    if ($rpta == 0) {
      $this->setSuccess(  "Cod: $rpta | "  . ($this->data['fe_rpta_api']['descripcion'] ?? '--') );
    }
    if ($rpta == 98) {
      $this->setSuccess('Envio En Proceso (98)');
    }
    if ($rpta == 99) {
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