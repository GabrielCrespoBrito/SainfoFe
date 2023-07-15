<?php 

namespace App\Presenter;

class ProductoPresenter extends Presenter
{
	public function userName()
	{
		return $this->args;
	}

	public function showBtn()
	{
		$inspiration = "%s %s";
		return sprintf( $inspiration, 'aa' , 'bb');
	}
}

?>