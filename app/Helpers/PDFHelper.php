<?php

namespace Helpers;

use mikehaertl\wkhtmlto\Pdf;

class PDFHelper
{
	// Packetes
	const DOMPDF = "DOM";
	const WKPDF = "WKPDF";

	// Opciones por defecto para los packetes
	public $defaults = [
		
		// DOMPDF
		self::DOMPDF => [],

		// WKPDF
		self::WKPDF  => [

			// Command options
			'commandOptions' => [
		  'useExec' => true,
		  'escapeArgs' => false,
		  'locale' => 'es_ES.UTF-8',
		  'procOptions' => [
				'bypass_shell' => true,
				'suppress_errors' => true ],
			],
			// --------------------

			// Global Options
			'globalOptions' => [ 
				'no-outline', 
				'page-size' => 'Letter' ,
				 'orientation' => 'landscape' 
			]
			// --------------------

		],

	];

	// Paquete a utilizar
	public $package;
	// Vista que se va a renderizar
	public $page;
	// Parametros
	public $options;


	public function __construct( $package , $page , Array $options = [] )
	{
		$this->page = $page;
		$this->package = $package;
		$this->options = $options;
	}

	public function isDOMPDF(){
		return $this->package == self::DOMPDF;		
	}

	public function isWKPDF(){
		return ! $this->isDOMPDF;		
	}


	public function replaceOption( &$options_default, $options_new )
	{		
		// $option = array_merge_recursive( $options_default, $options_new );
		// $options_default = $option;

		// foreach ($options_new as $key => $value) {

		// 	if( array_key_exists( $key, $options_default ) ){

		// 		if( is_array($options_default[$key]) ){
					
		// 			if( ! is_array( $value ) ){
		// 				dd("Error");
		// 			}

		// 			$this->replaceOption( $options_default[$key] , $value );
		// 		}
		// 		else {
		// 			$options_default[$key] = $value;
		// 		}
		// 	}
		// 	else {
		// 		$options_default[$key] = $value;
		// 	}
		// }
	}

	public function processOptions(){

		if( $this->isWKPDF() ){
			if( array_key_exists("commandOptions", $this->options ) ){
				$this->replaceOption( $this->defaults[self::WKPDF]['commandOptions'] , $this->options['commandOptions'] );
			}
			if( array_key_exists("globalOptions", $this->options ) ){
				$this->replaceOption( $this->defaults[self::WKPDF]['globalOptions'] , $this->options['globalOptions'] );				
			}			
		}
	}

	public function stream(){

		if( $this->isWKPDF() ){			
			return $this->streamWKPDF();
		}

		else {
			return $this->streamDOMPDF();			
		}
	}

	public function initLibrary(){
		if( $this->isWKPDF() ){		
			$pdf = newPdf($defaults[self::WKPDF]['commandOptions']);
			$pdf->setOptions($defaults[self::WKPDF]['globalOptions']);
		}

		else {
		}		
	}

	public function streamWKPDF(){

		$pdf = $this->initLibrary();
		return $pdf->addPage( $this->page );
	}

	public function streamDOMPDF(){

	}


}