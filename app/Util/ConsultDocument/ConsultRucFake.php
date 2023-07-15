<?php

namespace App\Util\ConsultDocument;

use App\Ubigeo;
use Faker\Factory;

class ConsultRucFake implements ConsultRucInterface
{
  public function consult($dni)
  {
    $faker = Factory::create();
    $ubigeo = Ubigeo::with(['departamento','provincia'])->inRandomOrder()->first();

    $data_response = [
      'razon_social' => $faker->firstName() . ' ' . $faker->lastName(),
      'ubigeo' => $ubigeo->ubicodi,
      'ubigeo_nombre' => $ubigeo->completeName(),
      'direccion' => $faker->address(),
      'email' => $faker->email(),
    ];

    return [
      'data' => $data_response,
      'error' => '',
      'success' => true,
    ];
  }
}


