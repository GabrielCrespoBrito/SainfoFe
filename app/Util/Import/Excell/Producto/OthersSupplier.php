<?php

namespace App\Util\Import\Excell\Producto;

use App\Moneda;
use App\Producto;

class OthersSupplier extends SupplierAbstract
{
  const MONEDAS = [
    'soles' => Moneda::SOL_ID,
    'dolares' => Moneda::DOLAR_ID,
  ];


  protected $currentLastId = null;

  public function setInitData()
  {
    
  }

  public function getProductoLastId()
  {
    if( $this->currentLastId == null ){
      return $this->currentLastId = Producto::UltimoID();
    }

    return $this->currentLastId = math()->addCero( $this->currentLastId + 1 , 6 );
  }


  public function getMoneda($moneda)
  {
    return SELF::MONEDAS[strtolower($moneda)];
  }

  /**
   * @TODO, implementar una soluciòn que genere un codigo tomando en cuenta los datos del producto
   * 
   */
  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader('id')] = $this->getProductoLastId();
    $dataProcess['empcodi'] = $this->empcodi;
    $dataProcess['moncodi'] = $this->getMoneda($this->campoValue);
    $dataProcess['User_ECrea'] = gethostname();
    $dataProcess['prosto1'] = 0;
    $dataProcess['prosto2'] = 0;
    $dataProcess['prosto3'] = 0;
    $dataProcess['prosto4'] = 0;
    $dataProcess['prosto5'] = 0;
    $dataProcess['prosto6'] = 0;
    $dataProcess['prosto7'] = 0;
    $dataProcess['prosto8'] = 0;
    $dataProcess['prosto9'] = 0;
    $dataProcess['prosto10'] = 0;
    $dataProcess['provaco'] = 0;
    $dataProcess['proigco'] = 0;
    $dataProcess['ProMarg1'] = 0;
    $dataProcess['ProPUVS1'] = 0;
    $dataProcess['ProPUVD1'] = 0;
    $dataProcess['ISC'] = 0;
    $dataProcess['Promini'] = 0;
    $dataProcess['icbper'] = 0;
  }
}
