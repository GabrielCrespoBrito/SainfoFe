<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class SunatProducto extends Model
{
    use UsesSystemConnection;    
    protected $table = "sunat_productos";    
}
