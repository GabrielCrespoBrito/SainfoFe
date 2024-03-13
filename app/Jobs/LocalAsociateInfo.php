<?php

namespace App\Jobs;

use App\Local;
use App\ListaPrecio;
use App\SerieDocumento;
use Illuminate\Http\Request;
use App\Jobs\CreateUnidsForLista;
use App\Models\UserLocal\UserLocal;
use App\User;

class LocalAsociateInfo 
{
  public $loccodi;
  public $request;
  public $users;
  public $empcodi;
  public $nuevasSeries;

  public function __construct(Local $local, Request $request)
  {
    $this->request = $request;
    $this->loccodi = $local->LocCodi;
    $this->empcodi = $local->empcodi;
    $this->users = $request->users;
    $this->nuevasSeries = get_empresa()->getNuevaSeriesInfo( $local->SerLetra, true )->series;
  }

  public function asociateToUser($usucodi)
  {
    UserLocal::create_($usucodi, $this->loccodi, $this->empcodi, 0);

    User::find($usucodi)->setDefaultLocal($this->loccodi);
  }

  public function createSeries( $usucodi )
  {
    foreach ($this->nuevasSeries as $nuevaSerie) {
      $data = [
        'empcodi' => $this->empcodi,
        'usucodi' => $usucodi,
        'tidcodi' => $nuevaSerie['tidcodi'],
        'sercodi' => $nuevaSerie['serie'],
        'a4_plantilla_id'     => $nuevaSerie['a4_plantilla_id'],
        'a5_plantilla_id'     => $nuevaSerie['a5_plantilla_id'],
        'ticket_plantilla_id' => $nuevaSerie['ticket_plantilla_id'],
        'numcodi' => agregar_ceros($this->request->input($nuevaSerie['codigo']), 6, 0),
        'defecto' => $nuevaSerie['defecto'],
        'loccodi' => $this->loccodi,
        'estado' => 1,
        'contingencia' => 0,
      ];
      SerieDocumento::create($data);
    }
  }


  public function createListaPrecio()
  {
    $data = [
      'LisCodi' => $liscodi = ListaPrecio::getId(),
      'LisNomb' => $this->request->lista_nombre,
      'empcodi' => $this->empcodi,
      'LocCodi' => $this->loccodi,
    ];

    // Crear Lista de Precio
    ListaPrecio::create($data);

    // Copiar de Lista 
    $unidCreator = new CreateUnidsForLista($liscodi , $this->request->lista_copy_id );
    $unidCreator->handle();
  }


  public function handle()
  {
    $user = get_empresa()->userOwner();
    $users = (array) $this->users;
    
    // Asociar usuario principal
    array_unshift( $users, $user->usucodi );

    foreach( $users as $usucodi ){
      $this->asociateToUser($usucodi);
      $this->createSeries($usucodi);
    }
    // Crear lista de precio
    $this->createListaPrecio();
  }
}
