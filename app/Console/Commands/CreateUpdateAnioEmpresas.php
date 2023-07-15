<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;

class CreateUpdateAnioEmpresas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:actualizar_anio_trabajo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el año de trabajo de las empresas';

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
      $empresas = Empresa::with('periodos')->get();

      foreach( $empresas as $empresa  ){
        $empresa->updateAnioTrabajo();
      }
    }
}