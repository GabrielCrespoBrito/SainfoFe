<?php

namespace App\Jobs\User;

use Illuminate\Foundation\Bus\Dispatchable;

class SendRegisterActionCode
{
    use Dispatchable;

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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $basic  = new \Nexmo\Client\Credentials\Basic('8bacc1f4', '9lPuSCXREsvD9uow');
        $client = new \Nexmo\Client($basic);
        $message = $client->message()->send([
            'to' =>  $this->user->getPhoneFormat(),
            'from' => $this->getTitle() ,
            'text' => $this->getMessage()
        ]);
    }
}