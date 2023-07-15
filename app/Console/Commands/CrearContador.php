<?php

namespace App\Console\Commands;

use App\User;
use App\UserEmpresa;
use Illuminate\Console\Command;

class CrearContador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'util:crear_contador';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuario contador por defecto';

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
      if( $user = User::findByUserName("CONTADOR")){
        $user->delete();
      }
        
      $codigo_contador = 99;
      $password_contador = "contador";

      $user = new User;
      $user->usucodi = $codigo_contador;
      $user->usulogi = "CONTADOR";      
      $user->usunomb = "-";
      $user->usucla1 = $password_contador;
      $user->usucla2 = "CONTADOR";
      $user->carcodi = "11";
      $user->ususerf = "";
      $user->usudocf = "";
      $user->ususerb = "";
      $user->email = "";
      $user->save();      

      $user_empresa = new UserEmpresa();
      $user_empresa->usucodi = $codigo_contador;      
      $user_empresa->empcodi = "001";
      $user_empresa->save();

    }
}
