<?php

namespace App\Woocomerce;

class ProcessResultOrder extends ProcessResultAbstract
{
  public function getOne($order)
  {
    $metaData = collect($order->meta_data );
    $dataRequest = $metaData->where('key', '_raq_request')->first()->value;
    // $linkOrder = 
    // dd( $this->result );
    // exit();
    if($order->status == 'ywraq-new'){
      $estado = [
        'text' => 'Pendiente', 
        'completado' => false, 
      ];
    }
    else {
      $estado = [
        'text' => 'Completado',
        'completado' => true,
      ];
    }

    return [
        'id' => $order->id,
        'created_at' => $order->date_created,
        'updated_at' => $order->date_modified,
        'user' => [
          'id' => $order->customer_id,
        ],
      'cliente' => [
        'razon_social' => optional(optional($dataRequest)->razon_social)->value,
        'documento' => optional(optional($dataRequest)->documento)->value,
        'email' => optional(optional($dataRequest)->email)->value,
        'telefono' => optional(optional($dataRequest)->telefono)->value,
        'mensaje' => optional(optional($dataRequest)->message)->value,
      ],
        'mensaje' => optional(optional($dataRequest)->message)->value,
        'estado' => $estado,
        'items_count' => count($order->line_items),
        'items' => $order->line_items
    ];
  }


  public function all()
  {
    $orders = $this->result;

    $data = [
      'orders' => [],
      'pagination' => $this->getPaginationData()
    ];

    foreach( $orders as $order ){
      array_push( $data['orders'] ,  $this->getOne($order)  );
    }

    $this->setSuccess(true);
    $this->setData($data);

    return $this;
  }

  public function get()
  {
    $this->setSuccess(true);
    $this->setData($this->getOne($this->result));
    return $this;
  }

  public function update()
  {
    $this->setSuccess(true);
    $this->setData($this->result);
    return $this;
  }

  public function delete()
  {
    $this->setSuccess(true);
    $this->setData($this->result);
    return $this;
  }


  


}
