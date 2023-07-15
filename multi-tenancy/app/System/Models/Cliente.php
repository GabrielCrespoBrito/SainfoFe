<?php

namespace App\System\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Cliente extends Model
{
  use UsesSystemConnection;
}