<?php

namespace App\Http\Controllers\Util\TransactionDBHelper;


class TransactionDBHelper
{
  public function make( callback $callback, $messageIsSuccess = null )
  {
    $success = true;
    $result = null;
    $message = $messageIsSuccess;
    $codeHttp = 400;

    try {
      DB::transaction(function () use ($callback, &$result) {
        $result = $callback();
      });
    } catch (\Throwable $th) {
      $success = false;
      $message = $th->getMessage();
      $codeHttp = $this->getCode();

    } 

    return (object) [
      'success' => $success,
      'result' => $result,
      'message' => $message,
      'codeHttp' => $codeHttp
    ];

  }


  public function aplly()
  {
    $success = true;
    $result = null;
    $message = '';

    \DB::beginTransaction();
    try {
      $result = $callback();
      \DB::commit();
    } catch (\Exception $e) {
      $success = false;
      $message = $e->getMessage();
      \DB::rollback();
    }

    return [
      'success' => $success,
      'result' => $result,
      'message' => $message
    ];
  }
}