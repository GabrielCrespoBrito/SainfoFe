<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;

class ActualizarAT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empresa:actualizar_periodo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el año de trabajo de la empresa';

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
        $empresas = Empresa::all();
        
        foreach( $empresas as $empresa ){
            $empresa->updateYear();
        }
    }
}
