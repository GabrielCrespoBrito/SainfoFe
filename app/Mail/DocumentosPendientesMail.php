<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentosPendientesMail extends Mailable
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

    public function build()
    {
      
      $mail = $this
      ->subject( $this->data['subject'] )       
      ->from( $this->data['from_mail'] , $this->data['from_nombre'] )
      ->view( 'mails.documentos_pendientes' , $this->data );            

      return $mail;
    }
}
