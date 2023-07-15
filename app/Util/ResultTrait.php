<?php

namespace App\Util;


trait ResultTrait
{
  protected $result = [
    'success' => null,
    'data' => null,
  ];

  public function setResult($success, $data)
  {
    $this->result = [
      'success' => $success,
      'data' => $data
    ];

    return $success;
  }

  public function setError($data)
  {
    return $this->setResult(false, $data);
  }

  public function isSuccess()
  {
    return $this->getResult()->success;
  }

  public function isError()
  {
    return !$this->isSuccess();
  }
  
  public function setSuccess($data)
  {
    return $this->setResult(true, $data);
  }

  public function getResult()
  {
    return (object) $this->result;
  }
}
