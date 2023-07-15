<?php

namespace App\Jobs\Venta;

use App\Helpers\FHelper;
use Chumper\Zipper\Zipper;

class GetFiles
{
  public $fh;
  public $ruc;
  public $file_name;
  public $file_zip;
  public $name_documento;
  public $path_file_compress;

  public $docs_names = [];
  public $docs_download = [];

  public $response = [
    'success' => false,
    'path' => null,
    'file_name' => null,
  ];

  public function __construct( $ruc, $name_documento)
  {
    $this->ruc = $ruc;
    $this->fh = FileHelper($ruc);
    $this->name_documento =   $name_documento;
    $this->file_name =  $this->ruc . '-' . $this->name_documento;
  }

  public function generateFilesNames()
  {
    $this->file_zip = $this->file_name . '.zip';

    $this->docs_names = (object) [
      [ 'name' => 'R-' . $this->file_zip, 'type' => FHelper::CDR ],
      [ 'name' => $this->file_name . '.pdf', 'type' => FHelper::PDF ],
      [ 'name' => $this->file_zip, 'type' => FHelper::ENVIO ],
    ];

    $this->path_file_compress = getTempPath( time() . '-' . $this->file_zip);
  }

  public function searchDocuments()
  {
    foreach( $this->docs_names as $doc ){      
      if ($this->fh->exists($doc['type'], $doc['name'])) {        
        $content_file = $this->fh->getFileBySite($doc['type'], $doc['name']);
        $data = [
          'name' => $doc['name'],
          'content' => $content_file,
          'temp_path' => getTempPath($doc['name'])
        ]; 
        array_push($this->docs_download, $data ); 
      }
    }
  }

  public function compressDocuments()
  {
    if(!count($this->docs_download)){
      return;
    }

    $paths_files = [];

    foreach ($this->docs_download as $file) {
      \File::put( $file['temp_path'], $file['content']);      
      array_push($paths_files, $file['temp_path']);
    }

    # Comprimirlo
    $zipper = new Zipper();
    $zipper
      ->make( $this->path_file_compress )
      ->add($paths_files)
      ->close();

    $this->response['path'] = $this->path_file_compress;
    $this->response['file_name'] = $this->file_zip;
    $this->response['success'] = true;
  }

  public function handle()
  {
    $this->generateFilesNames();
    $this->searchDocuments();
    $this->compressDocuments();
    return $this->response;
  }
}
