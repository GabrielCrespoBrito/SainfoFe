<?php

namespace App\Jobs\GuiaSalida;

use App\GuiaSalida;

class GenerateDataEnvioApi
{
  public function __construct(GuiaSalida $guiaSalida)
  {
    $this->guiaSalida = $guiaSalida;
  }

  public function handle()
  {
    $infoZip = $this->guiaSalida->createXmlZip();
    $zipBase64 = base64_encode($infoZip['contentZip']);
    $hashZip = hash('sha256', $infoZip['contentZip']);

    return [
      "nomArchivo" => $infoZip['name_zip'],
      "arcGreZip"  => $zipBase64,
      "hashZip" => $hashZip,
    ];
  }
}
