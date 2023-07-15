<?php

namespace App\Jobs\Empresa;

use Illuminate\Support\Facades\DB;

class CambiarCampoCostos
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getQuery()
    {
      $query = DB::connection('tenant')->table('ventas_detalle')
      ->select([
        "ventas_detalle.Linea as id",
        "ventas_detalle.DetCSol as costo_sol",
        "ventas_detalle.DetCDol as costo_dolar"
      ])
      ->get()
      ->chunk(100);

    return $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      
      $detalles_chunk = $this->getQuery();
      foreach ($detalles_chunk as $detalles) {
        foreach ($detalles as $detalle) {
          DB::connection('tenant')->table('ventas_detalle')
          ->where('Linea', '=',  $detalle->id)
          ->update([
            "DetCSol" => 0,
            "DetCDol" => 0,            
            'DetVSol' => $detalle->costo_sol,
            'DetVDol' => $detalle->costo_dolar,
          ]);
        }
      }
    }
}