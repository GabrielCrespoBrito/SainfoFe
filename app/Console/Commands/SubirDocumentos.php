<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SubirDocumentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'util:subir_archivos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subir los archivos de los documentos a la nube';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      // Ya no se puede enviar docmento por implementacnión de tenant
      return;
      set_time_limit(3000);
      $empcodi = empcodi();

      if( get_setting('is_online') ){ return; }

      $ayer = now()->yesterday()->format('Y-m-d');
      $hoy = now()->today()->format('Y-m-d');

      $ventasNoUploads = 
      \DB::table("ventas_cab")    
      ->select('ventas_cab.VtaOper')
      ->where('ventas_cab.EmpCodi' , '=' , $empcodi )    
      ->where('ventas_cab.fe_rpta' , '=' , 0 )
      ->whereBetween('ventas_cab.VtaFvta' , [ $ayer , $hoy ] )
      ->get();

      $fileHelper = fileHelper();

      foreach( $ventasNoUploads->chunk(50) as $ventas ){
        foreach( $ventas as $venta ){
          $fileHelper->saveVenta( $venta->VtaOper );  
        }
      }
    }
}
