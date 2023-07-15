<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eliminar:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar los archivos en la carpeta Temp dentro del directorio public';

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
        function removeDirectory($path)
        {

            $files = glob($path . getSeparator() . '*');
            foreach ($files as $file) {
                is_dir($file) ? removeDirectory($file) : unlink($file);
            }
            return;
        }


        removeDirectory( public_path('temp') );



        
    }
}
