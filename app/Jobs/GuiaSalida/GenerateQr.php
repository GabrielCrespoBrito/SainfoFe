<?php

namespace App\Jobs\GuiaSalida;

use App\GuiaSalida;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// use BaconQrCode\Encoder\QrCode;

class GenerateQr
{
  protected $guiaSalida;
  protected $hash;

    public function __construct( GuiaSalida $guiaSalida, $hash )
    {
      $this->guiaSalida = $guiaSalida;
      $this->hash = $hash;
    }

    public function handle()
    {
      $qr = QrCode::format('png')->size(150)->generate($this->hash);
    }
}
