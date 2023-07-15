<?php 

namespace App\Presenter;
use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{
	protected $model;
	protected $args;
	
	function __construct( Model $model , $args = null )
	{
		$this->model = $model ;
		$this->args = $args;
	}
}