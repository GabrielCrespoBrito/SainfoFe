<?php
namespace App\Util\SendMessage;

use App\Util\SendMessage\InterfaceSendMessage;

class SendMessageNexmo implements InterfaceSendMessage
{
  public $user;
  public function __construct($user)
  {
    $this->user = $user;
  }

  public function getMessage()
  {
    return "Su codigo de activacion es {$this->user->getVerificationCode()}";
  }

  public function getTitle()
  {
    return 'Sainfo';        
  }

  public function getNumber()
  {
    return  $this->user->getPhoneFormat();
  }

  public function send()
  {
      $basic  = new \Nexmo\Client\Credentials\Basic(config('credentials.nexmo.user'), config('credentials.nexmo.api_key'));
      $client = new \Nexmo\Client($basic);
      return $client->message()->send([
          'to' =>  $this->getNumber(),
          'from' => $this->getTitle() ,
          'text' => $this->getMessage()
      ]);
  }
}