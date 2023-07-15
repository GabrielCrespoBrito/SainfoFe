<?php

namespace App\Console\Commands;

use App\PDFPlantilla;
use Illuminate\Console\Command;

class CreatePDFPlantilla extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:crear_pdf_plantilla {nombre} {vista} {tipo} {formato} {descripcion?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear Registro para una nueva plantilla de PDF';

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
      // php artisan system_task:crear_pdf_plantilla "Cotizacion a4 Hoja Completa Azul" a4_cot6 cotizaciones a4  
      $nombre = $this->argument('nombre');
      $description = $this->argument('descripcion');
      $vista = $this->argument('vista');
      $tipo = $this->argument('tipo');
      $formato = $this->argument('formato');

      PDFPlantilla::createNew($nombre,$vista,$tipo, $formato, $description);
    }
}
