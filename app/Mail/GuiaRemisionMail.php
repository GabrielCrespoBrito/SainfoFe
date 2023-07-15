<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GuiaRemisionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        $mail = $this
        ->subject( $data['subject'] )
        ->from( env('MAIL_USERNAME') , get_setting('nombre') )
        ->view( $data['view']  , $data );


        foreach( $this->data['attach'] as $path  ){
            $this->attach($path);
        }

        return $mail;
        // ->attach( $data['attach'] );
    }
}
