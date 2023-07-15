<?php

namespace App\Admin\Models;

use App\Admin\Models\Cliente\Cliente;
use App\Models\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Testimonio extends Model
{
  use 
  UsesSystemConnection,
  ImageUploadTrait;

  protected $table = "pagina_testimonios";

  public function getImageName()
  {
    return 'testimonio_pagina_' . time();
  }
  
  public function getImage()
  {
    return $this->imagen;
  }

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'cliente_id');
  }

  public static function allWithLogoPath()
  {
    $testimonios = Testimonio::with('cliente')->get();
    $testimonioData = [];
    foreach ($testimonios as $testimonio) {
      $data = $testimonio->toArray();
      $data['path'] = $testimonio->pathImage();
      $testimonioData[] = $data;
    }
    return $testimonioData;
  }
}
