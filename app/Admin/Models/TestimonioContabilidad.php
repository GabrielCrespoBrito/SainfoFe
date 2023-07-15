<?php

namespace App\Admin\Models;

use App\Admin\Models\Cliente\Cliente;
use App\Models\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class TestimonioContabilidad extends Model
{
  use
    UsesSystemConnection,
    ImageUploadTrait;

  protected $table = "pagina_cont_testimonios";

  public function getImageName()
  {
    return 'testimonio_cont_pagina_' . time();
  }

  public function getImage()
  {
    return $this->imagen;
  }
}
