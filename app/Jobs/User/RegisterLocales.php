<?php

namespace App\Jobs\User;

use App\User;
use App\SerieDocumento;
use App\Models\UserLocal\UserLocal;

class RegisterLocales
{
  public $user;
  public $userOwner;
  public $locales;
  public $empcodi;
  public $update;
  public $user_locales;
  public $hasLocales;

  public function __construct(User $user, $locales, $update = false)
  {
    $this->user = $user;
    $this->userOwner = get_empresa()->userOwner()->usucodi;
    $this->locales = $locales;
    $this->hasLocales = $locales != null;;
    $this->empcodi =  empcodi();
    $this->update =  $update;
    $this->user_locales = $update ? $user->locales : null;
  }

  public function createSeries($local)
  {
    $series = SerieDocumento::where('empcodi', $this->empcodi)
      ->where('loccodi', $local)
      ->where('usucodi', $this->userOwner)
      ->get();

    foreach ($series as $serie) {
      $data = $serie->toArray();
      $data['usucodi'] = $this->user->usucodi;
      SerieDocumento::create($data);
    }
  }

  public function cleanAll()
  {
    // Eliminar local asociado
    $user_locales = $this->user_locales;
    foreach( $user_locales as $user_local ){
      $user_local->delete();
    }

    // Eliminar series
    $documentos = $this->user->documentos->where('empcodi' , $this->empcodi);

    foreach($documentos as $documento ){
      $documento->delete();
    }
  }

  public function handle()
  {
    if($this->update){      
      if( !$this->hasLocales ){
        $this->cleanAll();
      }
    }


    $this->associate();
  }
  
  public function checkIfAssociate($local)
  {
    if( $this->update ){
      return $this->user_locales->where('loccodi', $local)->count();
    }
    
    return false;    
  }

  public function associate()
  {
    
    if(!$this->hasLocales){
      return;
    }

    foreach ($this->locales as $local) {

      if( $this->checkIfAssociate($local) ){
        continue;
      }

      UserLocal::create_($this->user->usucodi, $local, $this->empcodi, 0);
      $this->createSeries($local);
    }
  
    $this->user->setDefaultLocal();
  }

}
