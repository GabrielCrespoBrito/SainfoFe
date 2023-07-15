<?php

namespace App\Woocomerce;

class WoocomerceOrder extends WoocomerceAbstract
{
  public function all($parameters = [])
  {
    if( $this->success ){
      $this->callAllApi( 'orders', $parameters );
    }

    return $this;
  }

  public function get($id, $parameters = [])
  {
    if ($this->success) {
      $this->callGetApi('orders/' . $id, $parameters);
    }

    return $this;
  }

  public function update($id, $data = [])
  {
    if ($this->success) {
      $this->callUpdateApi('orders/' . $id, $data);
    }

    return $this;
  }

  public function delete($id, $force = false )
  {
    if ($this->success) {
      $this->callDeleteApi('orders/' . $id, ['force' => $force]);
    }

    return $this;
  }


}