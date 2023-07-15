<?php

namespace App\Util\ConsultTipoCambio;

class ConsultTipoCambioMigoByDate extends ConsultTipoCambioMigo implements ConsultTipoCambioByDateInterface
{
	protected $params_request = [];

	public function getUrl()
	{
		return config('credentials.migo.url_tc_date');
	}

	public function consult($fecha)
	{
		$this->setParamsRequest([
			'fecha' => $fecha
		]);

		return $this->consult_request();
	}
}
