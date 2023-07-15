<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use App\Models\Suscripcion\OrdenPago;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrdenPagoCreatedNotification extends Notification
{
    use Queueable;

    public $orden_pago;
    public $empresa;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( OrdenPago $orden_pago )
    {
        $this->orden_pago = $orden_pago;
        $this->empresa = $orden_pago->empresa;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
		$saludo = new HtmlString("<h2>Estimado Cliente, <strong>{$notifiable->nombre()} </strong> </h2>");
		$numero = config('app.phones.contacto');
		$correo = config('app.mail.pagos');
		$informacionContacto = new HtmlString("<br> <br> Para cualquier pregunta o inconveniente puedes comunicarte con nosotros al {$numero} o por el correo electronico {$correo}");
    $nombreEmpresa = $this->empresa->nombre();
		$rucEmpresa = $this->empresa->ruc();
	  $message = new HtmlString(sprintf('Se ha generado satisfactoriamente la Orden de Pago <strong>#%s </strong> para su empresa <strong> %s %s </strong>, en el adjunto estara consignada toda la informacion necesaria para su cancelación.', $this->orden_pago->getIdFormat(), $nombreEmpresa, $rucEmpresa));

        $orden_pago_filename = $this->orden_pago->fileName();
        $pathFile =  $this->empresa->ruc() .  "/ordenes_pago/" . $orden_pago_filename;
        $fileContent = Storage::disk('s3')->get($pathFile);
        $pathTemp = getTempPath($orden_pago_filename, $fileContent );

        return (new MailMessage)
			->subject("Orden de pago generada satisfactoriamente")
			->line($saludo)
            ->line($message)
            ->attach($pathTemp, [
                'as' => $orden_pago_filename,
                'mime' => 'text/pdf'
            ]) 
			->line($informacionContacto);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
