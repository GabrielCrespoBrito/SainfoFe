<?php

namespace App\Jobs\Empresa;

use App\Helpers\FHelper;

class ResourceLocal
{
  protected $empresa;
  protected $empresa_id;

  public function __construct($empresa)
  {
    $this->empresa = $empresa;
    $this->empresa_id = $empresa->id();
    $this->fileHelper = new FHelper($empresa->ruc());
  }

  public function getStorageFolderPath()
  {
    $storage_path = storage_path(file_build_path('app', 'public',  $this->empresa->id()));
    return $storage_path;
  }

  public function getLogoTicket()
  {
    return $this->fileHelper->img_exists($this->empresa->image);
  }

  public function fileExists()
  {
  }
}
