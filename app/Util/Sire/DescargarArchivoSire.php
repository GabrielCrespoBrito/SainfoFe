<?php

namespace App\Util\Sire;

class DescargarArchivoSire extends SireApi
{
  const URL = "https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvierce/gestionprocesosmasivos/web/masivo/archivoreporte?nomArchivoReporte=%s&codTipoArchivoReporte=%s&codLibro=%s";

  public function getUrl()
  {
    return sprintf( self::URL, 
      optional($this->parameters)->nomArchivoReporte,
      optional($this->parameters)->codTipoArchivoReporte,
      optional($this->parameters)->codLibro
    ); 
  }
}
