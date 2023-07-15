<?php

namespace App\Admin\Models\Cliente;

use App\Models\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Cliente extends Model
{
  use ImageUploadTrait;


  const STATUS_ACTIVE = 1;
  const STATUS_INACTIVE = 0;

  use UsesSystemConnection;

  protected $table = "pagina_clientes";

  public function getImage()
  {
    return $this->logo_path;
  }

  public function getImageName()
  {
    return 'logo_pagina_' . time();
  }
  
  public function scopeActive($query)
  {
    return $query->where('active', Cliente::STATUS_ACTIVE);
  }

  public function scopeInactive($query)
  {
    return $query->where('active', Cliente::STATUS_INACTIVE);
  }

  public static function allWithLogoPath($searchAll = null )
  {
    if($searchAll === null){
      $clients = Cliente::all();
    }
    else {
      $clients = $searchAll ? Cliente::Active()->get()  : Cliente::Inactive()->get();
    }
    
    $clientsData = [];
    foreach ($clients as $client) {
      $data = $client->toArray();
      $data['path'] = $client->pathImage();
      $clientsData[] = $data;
    }
    return $clientsData;
  }
}
