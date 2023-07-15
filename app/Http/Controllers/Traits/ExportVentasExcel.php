<?php 

namespace App\Http\Controllers\Traits;

use App\Venta;
use App\VentaItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Whoops\Exception\ErrorException;


class ExportVentasExcel
{
  // Empresa id
  public $empcodi;
  // Ids Ventas
  public $ids;  

	public function __construct( $ids , $empcodi ){
    $this->empcodi = $empcodi;
    $this->ids = $ids;
	}



  public function make(){
    
  }




}
