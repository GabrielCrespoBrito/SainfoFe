<?php

namespace App\Util\ConsultDocument;

use Faker\Factory;

class ConsultDniFake implements ConsultDniInterface
{
  public function consult($dni)
  { 
    $faker = Factory::create();

    $data_response = [
      'razon_social' => $faker->firstName() . ' ' . $faker->lastName()
    ];

    return [
      'data' => $data_response,
      'error' => '',
      'success' => true,
    ];
  }
}

