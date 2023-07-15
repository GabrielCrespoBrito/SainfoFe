<?php

namespace App\Jobs;

use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\User;
use App\Jobs\Empresa\GetSeriesDefecto;

class TestCode
{
  public function __construct($code)
  {
    $this->code = $code;
  }

  public function handle()
  {
    switch ($this->code) {
      case 'series_defecto':
        return (new GetSeriesDefecto)->handle();
        break;
      case 'bd_test':
        empresa_bd_tenant('058');
        function convert($size)
        {
          $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
          return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        }
        $memory_init = round(memory_get_usage() / 1048576, 2) . '' . ' MB';
        $sum = GuiaSalidaItem::where('DetCodi' , 21011421 )->sum('CpaVtaCant');
        $memory_finish = round(memory_get_usage() / 1048576, 2);
        return convert($memory_finish);
        // return  sprintf('Memoria inicial: %s - suma %s , memoria usada %s', $memory_init, $sum, $memory_finish );

        $items = GuiaSalidaItem::where('DetCodi', 21011421)->get();
        for ($i=0; $i < 100 ; $i++) { 
          foreach( $items as $item ){
            $data = $item->toArray();
            $data['Linea'] = agregar_ceros(GuiaSalidaItem::lastId(), 8, 1);
            GuiaSalidaItem::create($data);
          }
        }
          break;        
      
      default:
        throw new \Exception("No existe una prueba para {$this->code}", 1);      
        break;
    }
  }
}
