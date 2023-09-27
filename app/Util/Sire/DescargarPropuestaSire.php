<?php

namespace App\Util\Sire;

class DescargarPropuestaSire extends SireApi
{
  const URL = "https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvie/propuesta/web/propuesta/202307/exportapropuesta?mtoTotalDesde=&mtoTotalHasta=&fecDocumentoDesde=&fecDocumentoHasta=&numRucAdquiriente=&numCarSunat=&codTipoCDP=&codTipoInconsistencia=&codTipoArchivo=0";


//   https://apisire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvie/propuesta/web/propuesta/{perPer
// iodoTributario}/exportapropuesta?mtoTotalDesde={mtoTotalDesde}&mtoTotalHasta={mto
// TotalHasta}&fecDocumentoDesde={fecDocumentoDesde}&fecDocumentoHasta={fecDocumentoHasta}&numRucAdquiriente={numRucAdquiriente}&numCarSunat={numCarSunat}&
// codTipoCDP={codTipoCDP}&codTipoInconsistencia={codTipoInconsistencia}&codTipoArchiv
// o={codTipoArchivo}

  public function getUrl()
  {
    return sprintf( self::URL, 
      $this->parameters->periodo,
      optional($this->parameters)->mtoTotalDesde,
      optional($this->parameters)->TotalHasta,
      optional($this->parameters)->fecDocumentoDesde,
      optional($this->parameters)->fecDocumentoHasta,
      optional($this->parameters)->numRucAdquiriente,
      optional($this->parameters)->numCarSunat,
      optional($this->parameters)->codTipoCDP,
      optional($this->parameters)->codTipoInconsistencia,
      optional($this->parameters)->codTipoArchivo
    ); 
  }
}
