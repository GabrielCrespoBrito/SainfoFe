<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Procedencia extends Model
{
	protected $table = 'procedencia';
	protected $primaryKey = 'ProcCodi';
	protected $keyType = 'string';
	public $timestamps = false;	
}
