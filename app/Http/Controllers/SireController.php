<?php

namespace App\Http\Controllers;

use App\Util\Sire\ConsultarYearPeriodoSire;
use App\Util\Sire\DescargarPropuestaSire;
use Illuminate\Http\Request;

class SireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    // $generator = new GenerateTokenSire(get_empresa());
    // $generator->handle();

    // $res = (new ConsultarYearPeriodoSire(get_empresa(), false, ['periodo' => "140000"]))
    //   ->handle()
    //   ->getResult();

      $res = (new DescargarPropuestaSire(get_empresa(), false, ['periodo' => "202305", 'codTipoArchivo' => 0]))
      ->handle()
      ->getResult();



      _dd("aqui",  $res);
      exit();
      
      return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
