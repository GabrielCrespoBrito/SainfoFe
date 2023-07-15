<?php

namespace App\Util\XmlInformation;

use Exception;

trait XmlInformationResolver
{
  /**
   * Resolver información necesaria
   * 
   * 
   */
  public function xmlInfo()
  {
    switch (get_class_name(get_class($this))){
      
      case 'Venta':
        return new VentaXmlInformation($this);
      break;
      case 'VentaItem':
        return new VentaItemXmlInformation($this);
      break;
      case 'GuiaSalida':
        return new GuiaXmlInformation($this);
        break;
      default:
        throw new Exception("This model is not supported", 1);      
        break;
    }
  }

}