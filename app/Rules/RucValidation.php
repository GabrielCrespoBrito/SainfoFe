<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Util\ConsultDocument\ConsultDniInterface;
use App\Util\ConsultDocument\ConsultRucInterface;
use App\Util\ConsultDocument\ConsultRucMigo;

class RucValidation implements Rule
{
	public $message = "El ruc suministrado no es valido";
	public $consulter = "El ruc suministrado no es valido";

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public $searchOnline = false;

	public function __construct($searchOnline = false )
	{
		$this->searchOnline = $searchOnline;
		$this->consulter = new ConsultRucMigo();
	}

	/**
	 * Validar si es un ruc valido
	 *
	 * @param [type] $attribute
	 * @param [type] $value
	 * @return void
	 */
	public function validate($valor)
	{
		$valorLen = strlen($valor);

    if (is_numeric($valor)) {

      if ( $valorLen == 8) {
        $suma = 0;
        for ($i = 0; $i < $valorLen - 1; $i++) {
					// $digito =   $valor . charAt(i) - '0';
					$digito = (int) substr($valor,$i,1);

          if ($i == 0){
						$suma += ($digito * 2);
					} 
          else {
						$suma += ($digito * ($valorLen - $i));
					} 
				}				

				$resto = $suma % 11;
				
				if ($resto == 1){
					$resto = 11;
				}

        // if ( $resto + ( $valor . charAt($valorLen - 1) - '0') == 11) {
				if ( $resto + ( substr($valor, $valorLen-1) ) == 11) {
          return true;
				}
			} 
			
      else if ($valorLen == 11) {

				$suma = 0;				
				$x = 6;				
				
        for ($i = 0; $i < $valorLen - 1; $i++) {
					
					if ($i == 4) {
						$x = 8;
					}

					$digito = substr($valor,$i,1);

					$x--;

					if ($i == 0) {
						$suma += ($digito * $x);
					}
          else {
						// dump( sprintf("suma(%s) digito(%s) x(%s)", $suma , $digito, $x ));
						$suma += ($digito * $x);
					} 
				}
				
				$resto = $suma % 11;
				
				$resto = 11 - $resto;
				
        if ($resto >= 10){
					$resto = $resto - 10;
				} 

        // if ($resto == $valor . charAt($valorLen - 1) - '0') {
				if ( $resto == substr($valor,$valorLen-1) ) {
          return true;
        }
			}
			
		}
		
    return false;
	}



	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{		
		$isValid = $this->validate($value);

		if( $this->searchOnline && $isValid ){
			return $this->searchRuc($value);
		}

		return $isValid;
	}


	public function searchRuc($ruc)
	{
		$response = $this->consulter->consult($ruc);

		if( $response['success'] ){
			return true;
		}

		if(isset($response['error'])){
			$this->message = $response['error'] ? $response['error'] : $this->message();
		}

		return false;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return $this->message;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message();
	}

}
