<?php
namespace App\Http\Controllers\Reportes;

abstract class ReportePDF 
{
  # Vista donde se va a procesar
  protected $view = '';
  # Información que se va a pasar a la vista
  protected $data = '';
  # Titulo
  protected $title = '';

  protected $extends = 'layouts.master';

  # Generador del pdf
  protected $generator = 'dompdf';


  public function __construct()
  {
  }



  public function stream()
  {
  }

  public function download()
  {
  }

}