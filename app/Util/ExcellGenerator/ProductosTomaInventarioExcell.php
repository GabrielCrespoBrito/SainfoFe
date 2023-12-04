<?php

namespace App\Util\ExcellGenerator;

use Illuminate\Support\Facades\DB;


class ProductosTomaInventarioExcell extends ExcellGenerator
{
  use LineTrait;

  const HEADER = ["CODIGO", "NOMBRE", 'STOCK'];

  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";

  public $linea = 1;
  public $title_hoja = "";



  public function __construct($data, $title_hoja)
  {
    parent::__construct($data, true, null);

    $this->title_hoja = $title_hoja;
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa,
      $this->title_hoja,
      $this->fecha_inicio . ' - ' . $this->fecha_final,
      datePeru('Y-m-d H:m:s')
    ];
  }

  public function headerSheet(&$sheet)
  {
    $sheet->row($line = $this->getLineaAndSum(), $this->getHeader());
    $sheet->row($line, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });
  }

  public function query()
  {
    //
    $listas = auth()->user()->listasCode();
    // $term = str_replace('*', '%', $request->search['value']);
    // $campo = $request->input('campo_busqueda', 'nombre');
    // $grupo = $request->input('grupo');
    // $familia = $request->input('familia');
    // $marca = $request->input('marca');

    // $busqueda = Producto::query()
    //   ->with([
    //     'marca_',
    //     'marca',
    //     'unidades_' => function ($q) use ($listas) {
    //       $q->whereIn('LisCodi', $listas);
    //     },
    //     'unidades_.lista'
    //   ])->whereHas('unidades_', function ($q) use ($listas) {
    //     $q->whereIn('LisCodi', $listas);
    //   });

    //


    $query = DB::connection('tenant')->table('productos')
      ->join('unidad', 'unidad.ID', '=', 'productos.Id')
      ->select('ProCodi', 'ProNomb')
      ->whereIn('unidad.LisCodi',  $listas)
      ->orderBy('ProCodi', 'asc')
      ->get()
      ->chunk(50);

    return $query;
  }

  public function processProductos(&$sheet)
  {
    $itemsGroup = $this->query();

    foreach ($itemsGroup as $items) {
      foreach ($items as $item) {
        $sheet->row($this->getLineaAndSum(), (array) $item);
      }
    }
  }

  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
      $this->headerSheet($sheet);
      $this->processProductos($sheet);
    });
  }
}
