<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M extends Model
{
	protected $connection = 'mysql2';
	protected $table = "cnm_plan_cta";
}

