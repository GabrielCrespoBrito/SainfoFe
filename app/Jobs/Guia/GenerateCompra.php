<?php

namespace App\Jobs\Guia;

use App\Compra;
use App\CompraItem;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateCompra
{
	use Dispatchable;

	/**
  * Create a new job instance.
  *
  * @return void
  */
	public $guia;
	public $request;
	public $compra;

	public function __construct($guia, $request)
	{
		$this->request = $request;
		$this->guia = $guia;
	}

	/**
  * Execute the job.
  *
  * @return void
  */
	public function handle()
	{
		$this->createCompra();
		$this->createItems();
		$this->calculateCompra();
		$this->updateGuia();
		// $this->updateCompra
	}

	public function createCompra()
	{
		$request = $this->request;
		$guia = $this->guia;

		$compra = new Compra();
		$compra->CpaSerie = $request->gen_serie;
		$compra->CpaNumee = $request->gen_num;
		$compra->CpaFCpa  = $request->gen_fecha;
		$compra->CpaFCon  = $request->gen_fecha;
		$compra->CpaFven  = $request->gen_fecha;
		// 
		$compra->PCcodi   = $guia->PCCodi;
		$compra->TidCodi  = $request->gen_tdoc;
		$compra->concodi  = "01";
		$compra->vencodi  = $guia->vencodi;
		$compra->moncodi  = $guia->moncodi;
		$compra->Docrefe  = $guia->GuiNume;
		$compra->GuiOper  = $guia->GuiOper;
		$compra->CpaTCam  = $guia->guiTcam;
		$compra->CpaSdCa  = 0;
		$compra->CpaTPes  = 0;
		$compra->Cpabase  = $guia->guitbas;
		$compra->CpaIGVV  = 0;
		$compra->CpaImpo  = 0;
		$compra->CpaEsta  = "C";
		$compra->save();	
	
		$this->setCompra($compra);
	}

	public function createItems()
	{
		$compra = $this->getCompra();
		$items = $this->guia->items;

		$index = 1;
		foreach ($items as $item) {
			$compra_item = new CompraItem();
			$compra_item->CpaOper = $compra->CpaOper;
			$compra_item->DetItem = agregar_ceros( $index, 2 , 0 );
			$compra_item->UniCodi = $item->UniCodi;
			$compra_item->DetUnid = $item->DetUnid;
			$compra_item->Detcodi = $item->DetCodi;
			$compra_item->MarNomb = $item->MarNomb;
			$compra_item->Detnomb = $item->DetNomb;
			$compra_item->DetCant = $item->Detcant;
			$compra_item->DetPrec = $item->DetPrec;
			$compra_item->DetDct1 = $item->DetDct1;
			$compra_item->DetDct2 = $item->DetDct2;
			$compra_item->DetImpo = $item->DetImpo;
			$compra_item->DetImpo = $item->DetImpo;
			$compra_item->DetPeso = $item->DetPeso;
			$compra_item->Detcodi = $item->DetCodi;
			$compra_item->GuiOper = $item->GuiOper;
			$compra_item->Guiline = $item->Guiline;
			$compra_item->save();
			$index++;
		}
	}

	public function updateGuia()
	{
		$this->guia->saveCompraDocRel($this->getCompra());
	}

	public function updateCompra()
	{
		$compra = $this->getCompra();
		$compra->guioper = $this->guia->GuiOper;
		$compra->save();
	}

	public function calculateCompra()
	{
		$this->getCompra()->calculateTotal();
		$this->getCompra()->update(['CpaSdCa' => 0]);

	}


	/**
	 * Get the value of compra
	 */ 
	public function getCompra()
	{
		return $this->compra;
	}

	/**
	 * Set the value of compra
	 *
	 * @return  self
	 */ 
	public function setCompra($compra)
	{
		$this->compra = $compra;

		return $this;
	}


}
