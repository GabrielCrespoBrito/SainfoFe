<?php

namespace App\Jobs\Venta\CreateNC;

use Exception;

class CreateNCProvider
{
  public static function GetCreator($tipo,  $documento, $data)
  {
    switch ($tipo) {
      case '1':
        return new CreateNCTotal($documento, $data);
        break;
      case '2':
        return new CreateNCParcial($documento, $data);
        break;        
      case '3':
        return new CreateNCConcepto($documento, $data);
        break;        
      case '4':
        return new CreateNCAjuste($documento, $data);
        break;
      default:
        throw new Exception("Error Throw Creator: {$tipo} Don't Exists", 1);
        break;
    }
  }
}
