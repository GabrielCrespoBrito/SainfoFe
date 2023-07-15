<?php

namespace App\Observers;

use App\FormaPago;

class FormaPagoObserver
{
	public function creating(FormaPago $forma_pago)
	{
		// $forma_pago->conCodi = agregar_ceros(FormaPago::max('conCodi'), 2, 1);
		$forma_pago->empcodi = $forma_pago->empcodi ?? empcodi();
	}
}
