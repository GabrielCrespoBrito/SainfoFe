<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;

class EmpresaMigrateInfo extends Command
{
    protected $signature = 'db:migrate {empcodi}';
    protected $description = 'Migrara información de la empresa a las bases de datos individuales';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $empcodi = $this->argument('empcodi');
        $empresa = Empresa::find($empcodi);
        $empresa->migrateInfo( "tenant" );
    }
}
