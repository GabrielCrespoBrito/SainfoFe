<?php

namespace App\Util\ConsultTipoCambio;

abstract class ConsultTipoCambioMigo 
{
  /**
   * Parametros para la consultar
   * 
   * @param array 
   */ 
	protected $params_request = []; 

  /**
   * Obtener el end-point para la consulta
   * 
   * @return string 
   */
  abstract protected function getUrl();

  /**
   * Obtener para para la consulta
   * 
   * @return array
   */
	public function getParamsRequest()
	{
		return $this->params_request;
	}

  /**
   * Establecer parametros para la consulta
   * 
   * @return array
   */
	public function setParamsRequest( array $params = [] )
	{
		$this->params_request = $params;
	}

  /**
   * Campos enviados
   * 
   * @return array
   */
  public function getPostData()
  {
    return array_merge(
      $this->params_request, 
      [ 'token' => config('credentials.migo.token') ]
    ); 
  }

  /**
   * Curl data
   * 
   * @return array
   */
	public function getCurlData()
	{
		return [
			CURLOPT_URL => $this->getUrl(),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $this->getPostData(),
      ];
	}
  
	public function getResponse( $response, $err)
	{
		$success = true;
		$data_response = [];

		if ($err) {
			$success = false;
		} 
		else {
			$data_response = json_decode($response);
		}

		return [
			'success' => $success,
			'error' => $err,
			'data' => $data_response,
		];
	}

	public function consult_request()
	{
		$curl = curl_init();
		curl_setopt_array($curl, $this->getCurlData());
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		return $this->getResponse( $response, $err);
	}

}
