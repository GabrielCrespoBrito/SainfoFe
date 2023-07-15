<?php

namespace App\Woocomerce;

abstract class ProcessResultAbstract
{
  public $result;
  public $client;

  public $data;
  public $success;
  public $error;

  public function __construct($result, $client)
  {
    $this->result = $result;
    $this->client = $client;
    return $this;
  }

  // @TODO
  public function getPaginationData()
  {
    $total = 9;
    $pages = 1;

    return [
      'total' => $total,
      'pages' => $pages,
    ];
  }


  /**
   * Get the value of data
   */ 
  public function getData()
  {
    return $this->data;
  }

  /**
   * Set the value of data
   *
   * @return  self
   */ 
  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * Get the value of success
   */ 
  public function getSuccess()
  {
    return $this->success;
  }

  /**
   * Set the value of success
   *
   * @return  self
   */ 
  public function setSuccess($success)
  {
    $this->success = $success;

    return $this;
  }

  /**
   * Get the value of error
   */ 
  public function getError()
  {
    return $this->error;
  }

  /**
   * Set the value of error
   *
   * @return  self
   */ 
  public function setError($error)
  {
    $this->error = $error;

    return $this;
  }
}
