<?php

namespace App\Listeners\Empresa;

use App\Jobs\Empresa\UploadCertificate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveCert
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object  $event
	 * @return void
	 */
	public function handle($event)
	{
    (new UploadCertificate( $event->request, $event->empresa->ruc()))->handle();

		// $fileHelper = FileHelper($event->empresa->ruc());

		// if ($event->request->has('cert_key')) {
		// 	$fileHelper->save_cert(
		// 		'.key',
		// 		file_get_contents($event->request->file('cert_key')->getRealPath())
		// 	);
		// }

		// if ($event->request->has('cert_cer')) {
		// 	$fileHelper->save_cert(
		// 		'.cer',
		// 		file_get_contents($event->request->file('cert_cer')->getRealPath())
		// 	);
		// }

		// if ($event->request->has('cert_pfx')) {
		// 	$fileHelper->save_cert(
		// 		'.pfx',
		// 		file_get_contents($event->request->file('cert_pfx')->getRealPath())
		// 	);
		// }

	}
}
