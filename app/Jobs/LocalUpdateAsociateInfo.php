<?php

namespace App\Jobs;

use App\M;
use App\User;
use App\Local;
use App\SerieDocumento;
use Illuminate\Http\Request;
use App\Models\UserLocal\UserLocal;

class LocalUpdateAsociateInfo
{  
  public $local;
  public $loccodi;
  public $request;
  public $users;
  public $empcodi;
  public $seriesLocal = null;

  public function __construct(Local $local, Request $request)
  {
    $this->local = $local;
    $this->loccodi = $local->LocCodi;
    $this->empcodi = $local->EmpCodi;
    $this->request = $request;
    $this->users = (array) $request->users;
    if (count($this->users)) {
      $usucodi_owner = get_empresa()->userOwner()->usucodi;
      $this->seriesLocal =
        SerieDocumento::where('empcodi', $this->empcodi)
        ->where('loccodi', $this->loccodi)
        ->where('usucodi', $usucodi_owner)
        ->get();
    }
  }

  public function asociateToUser($usucodi)
  {
    return UserLocal::create_($usucodi, $this->local->LocCodi, $this->empcodi, 0);
  }

  public function createSeries($user)
  {
    foreach ($this->seriesLocal as $serie) {
      $data = $serie->toArray();
      $data['usucodi'] = $user->usucodi;
      SerieDocumento::create($data);
    }
  }

  public function removeUsersFromLocal($users_local, $users_new)
  {
    $users_local_delete = $users_local->whereNotIn('usucodi', $users_new->toArray());

    foreach ($users_local_delete as $user_local_delete) {
      $user = $user_local_delete->user;
      $usuarios_documentos = $user_local_delete->getSeries();
      foreach ($usuarios_documentos as $usuario_documento) {
        $usuario_documento->delete();
      }
      $user_local_delete->deleteShort();
      $user->setDefaultLocal();
    }
  }

  public function addUsersToLocal($usucodis)
  {
    foreach ($usucodis as $usucodi) {
      $user = User::find($usucodi);

      // Asociar al local
      UserLocal::create_($usucodi, $this->loccodi, $this->empcodi, 0);
      $this->createSeries($user);
      $user->setDefaultLocal();
    }
  }

  public function handle()
  {
    $user = get_empresa()->userOwner();
    $users_local = $this->local->usuarios_locales->whereNotIn('usucodi', [$user->usucodi, "01"]);
    $users_local_usucodi = $users_local->pluck('usucodi');
    $users_news = collect($this->users);

    // Asociar usuario principal
    $this->removeUsersFromLocal($users_local,  $users_news);
    $this->addUsersToLocal($users_news->diff($users_local_usucodi));
  }
}
