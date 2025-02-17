<?php

namespace App\Util\Sire;

class ConsultarEstadoTicketSire extends SireApi
{
  const URL = "https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvierce/gestionprocesosmasivos/web/masivo/consultaestadotickets?perIni=%s&perFin=%s&page=%s&perPage=%s&numTicket=%s";


// https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvierce/gestionprocesosmasivos/web/
// masivo/consultaestadotickets?perIni={perIni}&perFin={perFin}&page={page}&perPage={pe
// rPage}&numTicket={numTicket}

  public function getUrl()
  {
    // logger(sprintf(
    //   self::URL,
    //   optional($this->parameters)->perIni,
    //   optional($this->parameters)->perFin,
    //   optional($this->parameters)->page,
    //   optional($this->parameters)->perPage,
    //   optional($this->parameters)->numTicket
    // )); 
  }
}
