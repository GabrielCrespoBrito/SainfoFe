<?php

namespace App\Listeners\Monitoreo\Empresa;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFolders
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
		# cert
		\File::makeDirectory($event->empresa->getFolderSave('cert'), 0777, true, true);

		# cdr
		\File::makeDirectory($event->empresa->getFolderSave('cdr'), 0777, true, true);
	}
}