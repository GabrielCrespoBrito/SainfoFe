<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\DocumentosPendientesMail;
use App\Empresa;

class NotificarDocumentosPendientes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $empresas = Empresa::all();


      foreach( $empresas as $empresa ){

        if( is_valid_email($empresa->EmpLin3) ){

          if( $empresa->documentosPendientes->count()){

            $data = [];
            $data['cliente_nombre'] = $empresa->EmpNomb;
            $data['cliente_ruc']    = $empresa->EmpLin1;      
            $data['subject']        = 'Notificación de documentos pendientes';
            $data['from_mail']      = get_setting('sistema_email');
            $data['from_nombre']    = get_empresa('sistema_nombre');
            $data['documentos']     = $empresa->documentosPendientes;

            \Config::set('mail.username', get_setting('sistema_email') );
            \Config::set('mail.password', get_setting('sistema_password') );          
            \Mail::to($empresa->EmpLin3)->send(new DocumentosPendientesMail($data));

          }
          
        }
      }

    }
}
