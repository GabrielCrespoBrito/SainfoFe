<?php

namespace App\Util\ConsultTipoCambio;

class ConsultTipoCambioMigoByLatest extends ConsultTipoCambioMigo implements ConsultTipoCambioByLatestInterface
{
	public function getUrl()
	{
		return config('credentials.migo.url_tc_latest');
	}

	public function consult()
	{
		return $this->consult_request();
	}
}
