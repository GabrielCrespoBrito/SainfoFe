<?php

namespace App\Http\Controllers\Consolidacion;

use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsolidacionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Venta::all();
		return view('consolidacion.index');
	}
}