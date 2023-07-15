<?php

namespace App\Http\Controllers\Resumen;

use App\Resumen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resumen\ResumenDestroyRequest;
use App\Http\Requests\Resumen\ResumenUpdateTicketRequest;

class ResumenController extends Controller
{
	public function index()
	{
		return view('resumens.index');
	}

	public function destroy(ResumenDestroyRequest $request)
	{
		Resumen::findMultiple($request->id_resumen, $request->docnume)->delete_all();
	}

	public function updateTicket( ResumenUpdateTicketRequest $request)
	{
		Resumen::findMultiple($request->id_resumen, $request->docnume)->delete_all();
	}
  
}