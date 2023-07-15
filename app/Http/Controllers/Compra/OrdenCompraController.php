<?php

namespace App\Http\Controllers\Compra;

use Illuminate\Http\Request;
use App\Http\Controllers\CotizacionAbstractController;

class OrdenCompraController extends CotizacionAbstractController
{
  public function __construct()
  {
    parent::__construct();
  }
}