<?php

namespace App\Jobs\Empresa;


class UploadCertificate 
{
  public $request;
  public $ruc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $request, $ruc )
    {
      $this->request = $request;
      $this->ruc = $ruc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
  public function handle()
  {
    $fileHelper = FileHelper($this->ruc);

    if ($this->request->has('cert_key')) {
      $fileHelper->save_cert(
        '.key',
        file_get_contents($this->request->file('cert_key')->getRealPath())
      );
    }

    if ($this->request->has('cert_cer')) {
      $fileHelper->save_cert(
        '.cer',
        file_get_contents($this->request->file('cert_cer')->getRealPath())
      );
    }

    if ($this->request->has('cert_pfx')) {
      $fileHelper->save_cert(
        '.pfx',
        file_get_contents($this->request->file('cert_pfx')->getRealPath())
      );
    }
  }
}
