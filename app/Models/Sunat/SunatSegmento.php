<?php

namespace App\Models\Sunat;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class SunatSegmento extends Model
{
    use UsesSystemConnection;
    protected $table = "sunat_segmentos";
}

