<?php

namespace App\Util\XmlInformation;

class GuiaXmlInformation extends XmlInformation
{
  public function documentoRelacionadoNombre($tipoDocumento)
  {
    return self::CATALOGO_61[ $tipoDocumento ];
  }
}
