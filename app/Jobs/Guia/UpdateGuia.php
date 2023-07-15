<?php

namespace App\Jobs\Guia;

use App\GuiaSalidaItem;
use App\ClienteProveedor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateGuia implements ShouldQueue
{
	use Dispatchable;

	/**
  * Create a new job instance.
  *
  * @return void
  */
	public $guia;
	public $request;

	public function __construct($guia, $request)
	{
		$this->guia = $guia;
		$this->request = $request;
	}

	/**
  * Execute the job.
  *
  * @return void
  */
	public function handle()
	{
		$guia = $this->guia;
		$this->updateInfo();
		$guia->cancel(true);;
		GuiaSalidaItem::createItems($guia->GuiOper , $this->request->items);
		$guia->calculateTotal();
	}

	public function updateInfo()
	{
    $guia = $this->guia;
    $data = $this->request->toArray();

    if( $guia->isIngreso() ){
      $guia->GuiSeri = $data['GuiSeri'];
      $guia->GuiNumee  = $data['GuiNumee'];   
    }

    $id_almacen = $data['id_almacen'];
    $guia->TmoCodi = $data['id_tipo_movimiento'];     
    $guia->PCCodi  = $data['cliente_documento'];
    $guia->zoncodi  = "0100";     
    $guia->vencodi = $data["vendedor"];;
    $guia->Loccodi = $id_almacen;    
    $guia->moncodi = $data["moneda"];
    $guia->guiTcam = $data['tipo_cambio'];
    $guia->guiobse = $data["observacion"];
    $guia->guipedi = $data["nro_pedido"];;
    $guia->guicant = "0.00";
    $guia->guitbas = "0.00"; 
    $guia->guiporp = 0;
    $guia->GuiEsPe = "NP";
    $guia->concodi = "01";
		$guia->save();
	}
}

