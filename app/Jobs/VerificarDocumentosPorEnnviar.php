<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Venta;
use App\NotificacionDocumentosPendientes as NP;


class VerificarDocumentosPorEnnviar implements ShouldQueue
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
      // 5 Dias antes de vencer el documento tomando como referencia el dia de hoy
      $date_5 = now()->subDays(5)->toDateString();
      $date_6 = now()->subDays(6)->toDateString();

      $documentos = Venta::where('fe_rpta', '!=' , '0')->get();
      $noti_table = new NP();
      // Facturas
      $facturas = $documentos->where('TidCodi', '!=' ,'03');
      $facturas_hoy = $facturas->where('VtaFvta' , hoy());
      $facturas_vencer =       
      $facturas->where('VtaFvta' , '>=' , $date_6 )->where('VtaFvta' , '<=' , $date_5 );      

      $noti_table->RegisterFactura( NP::LAPSO_TODO , $facturas->count() , $facturas );
      $noti_table->RegisterFactura( NP::LAPSO_HOY , $facturas_hoy->count() , $facturas_hoy );
      $noti_table->RegisterFactura( NP::LAPSO_VENCER , $facturas_vencer->count() , $facturas_vencer );

      // Boletas
      $boletas = $documentos->where('TidCodi', '03');
      $boletas_hoy = $boletas->where('VtaFvta' , hoy());
      $boletas_vencer = $boletas->where('VtaFvta' , '>=' , $date_6 )->where('VtaFvta' , '<=' , $date_5 );            
      $noti_table->RegisterBoleta( NP::LAPSO_TODO , $boletas->count() , $boletas );
      $noti_table->RegisterBoleta( NP::LAPSO_HOY , $boletas_hoy->count() , $boletas_hoy);
      $noti_table->RegisterBoleta( NP::LAPSO_VENCER , $boletas_vencer->count() , $boletas_vencer);
    }
}
