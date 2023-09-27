<?php

namespace App\Http\Controllers;

use App\Util\Sire\ConsultarEstadoTicketSire;
use App\Util\Sire\ConsultarYearPeriodoSire;
use App\Util\Sire\DescargarArchivoSire;
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
      $empresa = get_empresa();

    // $generator = new GenerateTokenSire($empresa);
    // $generator->handle();

    // $res = (new ConsultarYearPeriodoSire($empresa, false, ['periodo' => "140000"]))
    //   ->handle()
    //   ->getResult();

      // Propuesta
      // $res = (new DescargarPropuestaSire($empresa, false, ['periodo' => "202305", 'codTipoArchivo' => 0]))
      // ->handle()
      // ->getResult();
      // +"data": {#2693 ▼
        // +"numTicket": "20230300000014"
      // }

// https://apisire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvierce/gestionprocesosmasivos/web/
// masivo/consultaestadotickets?perIni=202301&perFin=202305&page=1&perPage=20&num
// Ticket=

    // 'numTicket' => "20230300000014"

      // $res = (new ConsultarEstadoTicketSire($empresa, false, ['perIni' => '202307', 'perFin' => '202308', 'page' => 1, 'perPage' => 20 ]))
      // ->handle()
      // ->getResult();


      // ---------------


      $res = (new DescargarArchivoSire($empresa, false, ['nomArchivoReporte' => '65118af97d0da76dd80d9097__LE2054561334520230900014040001EXP2.zip', 'codTipoArchivoReporte' => '01', 'codLibro' => "140000" ]))
      ->setIsJsonResponse(false)
      ->handle()
      ->getResult();

      
      getTempPath("LE205456133452023090014040001EXP2.zip", $res->data );
      
      // _dd("aqui",  $res);
      // exit();

      // -----------
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
