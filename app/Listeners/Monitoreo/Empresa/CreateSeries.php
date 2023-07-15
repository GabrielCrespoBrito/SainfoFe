<?php

namespace App\Listeners\Monitoreo\Empresa;

class CreateSeries
{
  public function __construct()
  {
  }

  public function handle($event)
  {
    for ($i = 0; $i < count($event->request->serie); $i++) {
      $serie = $event->request->serie[$i];
      $tipo_documento = $event->request->tipo_documento[$i];

      $event->empresa->series()->create([
        'serie' => strtoupper($serie),
        'tipo_documento' => $tipo_documento,
      ]);
    }
  }
}
