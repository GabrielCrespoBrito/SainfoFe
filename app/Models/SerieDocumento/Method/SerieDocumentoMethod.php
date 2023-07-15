<?php 

namespace App\Models\SerieDocumento\Method;


trait SerieDocumentoMethod
{
  public static function getContingenciaSerie()
  {
  	return self::where('empcodi' , empcodi() )
  	->where('contingencia', self::CONTINGENCIA_STATE)
  	->get();
  }


	public static function isContingencia( $tidCodi, $serie ) : bool
	{
		return (bool)
    self::where('tidcodi', $tidCodi)
		->where('sercodi' , $serie)
		->where('contingencia' , self::CONTINGENCIA_STATE )
		->count();
	}

}
