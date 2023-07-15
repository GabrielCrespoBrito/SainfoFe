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
      // $size = [
      //   Venta::FORMATO_TICKET => 200,
      //   Venta::FORMATO_A4 => 150,
      //   Venta::FORMATO_A5 => 100,
      // ][$formato];
      
        $qr = QrCode::format('png')->size(200)->generate($this->hash);
      // $qr = QrCode::format('png')->size($size)->generate($firma);

        //
    }
}
