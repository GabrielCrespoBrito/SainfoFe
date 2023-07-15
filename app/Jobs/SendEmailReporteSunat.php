<?php

namespace App\Jobs;

use App\Mail\ReporteSunatDocumentos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmailReporteSunat implements ShouldQueue
{
	use Dispatchable;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$email = get_setting('sistema_email');
		Mail::to($email)->send(new ReporteSunatDocumentos($this->data));
	}
}


// Antecedentes Penales
// Antecedentes Penales
// Antecedentes Penales