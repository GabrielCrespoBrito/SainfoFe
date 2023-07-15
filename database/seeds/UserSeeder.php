<?php

use App\User;
use App\UserEmpresa;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      # User DEMO

      if( $user = User::findByUserName("DEMO")){
        $user->delete();
      }
      
      $user = new User;
      $user->usucodi = 22;
      $user->usulogi = "DEMO";      
      $user->usunomb = "DEMO";
      $user->usucla1 = "DEMO";
      $user->usucla2 = "DEMO";
      $user->carcodi = "11";
      $user->ususerf = "F001";
      $user->usudocf = "000000";
      $user->ususerb = "001";
      $user->email = "demo@gmail.com";
      $user->save();
      

      // Usuario contador

      // $codigo_contador = 99;
      // $password_contador = "contador";

      // // $user = new UserEmpresa;
      // // $user->usucodi = $codigo_contador;
      // $user->usulogi = "CONTADOR";      
      // $user->usunomb = "-";
      // $user->usucla1 = "CONTADOR";
      // $user->usucla2 = "CONTADOR";
      // $user->carcodi = "11";
      // $user->ususerf = "";
      // $user->usudocf = "";
      // $user->ususerb = "";
      // $user->email = "";
      // $user->save();

      // UserEmpresa

    }
}
