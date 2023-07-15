<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDocumento extends Mailable
{
  use Queueable, SerializesModels;

  public $data;

  /**
   * Create a new message instance.
   *
   * @return void
   */
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
    $mail = $this
    ->from( config('mail.from.address')  , config('mail.from.name') )
    // ->from( 'ventas@pernossa.com', 'Empresa Pernos SA')       
    ->subject($this->data['subject'])       
    ->view( $this->data['view']  , $this->data );
    if( count($this->data['attach']) ){
      foreach( $this->data['attach'] as $documento ){    
        $mail->attach( $documento );
      }     
    }

    return $mail;
  }
}
