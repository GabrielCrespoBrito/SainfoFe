<?php

namespace App\Admin\Models;

use App\Admin\Models\Cliente\Cliente;
use App\Models\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class ContCaracteristica extends Model
{
  use UsesSystemConnection;

  protected $table = "pagina_cont_caracteristicas";
}
