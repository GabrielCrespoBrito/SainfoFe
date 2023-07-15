<?php

namespace App\Traits;

trait InteractWhatApp
{
  public function getMessageLink()
  {
    $url = config('app.url');
    $token = config('app.token_cc');
    $nameFile = $this->nameFile('', true);
    $nombreDocumento = $this->getNombreTipoDocumento();
    $numero = $this->numero();
    $enlace = sprintf('%s/pdfv/%s/%s', $url, $token, $nameFile );
    
    return sprintf("Se ha emitido la %s *%s*, puede visualizar en el enlace: %s", 
      $nombreDocumento,
      $numero,
      $enlace
    );
  }


}
