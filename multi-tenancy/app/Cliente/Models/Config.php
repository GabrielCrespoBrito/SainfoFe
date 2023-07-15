<?php
namespace App\Cliente\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Config extends Model
{
  use UsesTenantConnection;
}