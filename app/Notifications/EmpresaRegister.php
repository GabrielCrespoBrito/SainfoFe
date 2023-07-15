<?php

namespace App\Notifications;

use App\Empresa;
use App\Helpers\NotificacionDatabaseHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmpresaRegister extends Notification
{
  use Queueable;

  public $empresa;
  public $user;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct( Empresa $empresa )
  {
    $this->empresa = $empresa;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['database'];
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toDatabase($notifiable)
  {
    $nombre = $this->empresa->EmpLin5;
    $nombre_empresa = strlen($nombre) > 10 ? 
      (substr($nombre,0,10) . '..') : 
      $nombre;
      

    // Titulo
    $titulo = sprintf("Nueva Empresa Registrada",    $nombre_empresa );

    // Descripción
    $descripcion = sprintf(
      "(%s ha sido registrada en el sistema, <br> <strong>Dirección</strong>: %s  <br> <strong>Telefono:</strong> %s <br> <strong>Correo Electronico: </strong> %s <br> <strong>Plan Registro:</strong> %s", 
      $this->empresa->nombreFull(),
      $this->empresa->direccion(),
      $this->empresa->telefonos(),
      $this->empresa->email(),
      $this->empresa->getPlanRegistroNombre()
    );

    return [
      'titulo' => $titulo,
      'descripcion' => $descripcion,
      'empresa_id' => $this->empresa->empcodi,
      'type' => 'success',
      'code' => NotificacionDatabaseHelper::EMPRESA_REGISTRO,
      'action' => true,
    ];
  }
}
