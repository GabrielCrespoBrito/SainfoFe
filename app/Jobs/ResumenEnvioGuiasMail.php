<?php

namespace App\Jobs;

use App\Mail\ReporteEnvioGuiaMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResumenEnvioGuiasMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            ini_set('max_execution_time', 90);

            $emailToSend = get_setting('email_receptor', 'gabrielc1990poker@gmail.com');
            $tema = "Reporte del envio automatico de guias a la sunat.";

      
            $data = [
                'subject' => $tema,
                'para'    => $emailToSend,
                'name'    => "Soporte",     
                'fecha'   => hoy(),
                'mensaje' => "Informe detallado del envio de guias de remisión a la sunat por parte del sistema",     
            ];
            
            $data['empresas'] = $this->data;
            \Mail::to($emailToSend)->send(new ReporteEnvioGuiaMail($data));
    }
}
