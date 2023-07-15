<?php

namespace App\Jobs\Guia;

use App\ClienteProveedor;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\Local;
use App\Models\Guia\GuiaIngreso;
use App\MotivoTraslado;

class Traslado
{
  public $data;
  public $guia_salida;
  public $guia_ingreso;

  public function __construct(GuiaSalida $guia_salida, $data)
  {
    $this->data = $data;
    $this->guia_salida = $guia_salida;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this
      ->createGuia()
      ->createDetalles()
      ->updateDataGuiaSalida();

    return $this->guia_ingreso->GuiOper;
  }

  public function createGuia()
  {
    $almacen_actual = $this->guia_salida->almacen;
    $almacen_destino = Local::find($this->data['almacen_id']);
    $user = auth()->user();
    $data_guia = $this->guia_salida->toArray();
    $data_guia["GuiOper"] = agregar_ceros(GuiaSalida::lastId(), 6, 1);
    $data_guia["PanAno"] = date('Y');
    $data_guia["PanPeri"] = date('m');
    $data_guia["EntSal"] = "I";
    $data_guia["GuiSeri"] = "";
    $data_guia["GuiNumee"] = "";
    $data_guia["GuiNume"] = '';
    $data_guia["PCCodi"] = ClienteProveedor::DEFAULT_CODIGO_ALMACEN;
    $data_guia["GuiFemi"] = date('Y-m-d');
    $data_guia["GuiFDes"] = date('Y-m-d');
    $data_guia["TmoCodi"] = $this->data['tipo_movimiento'];
    $data_guia["GuiEsta"] = "I";
    $data_guia["Loccodi"] = $this->data['almacen_id'];
    $data_guia["tracodi"] = "";
    $data_guia["guiobse"] = "Nº ORIGEN {$this->guia_salida->GuiOper}";
    $data_guia["docrefe"] = $this->guia_salida->GuiOper;
    $data_guia["guipedi"] = null;
    $data_guia["guidirp"] = $almacen_actual->LocDire;
    $data_guia["guidisp"] = $almacen_actual->LocDist;
    $data_guia["guidill"] = $almacen_destino->LocDire;;
    $data_guia["guidisll"] = $almacen_destino->LocDist;
    $data_guia["VehCodi"] = '';
    $data_guia["concodi"] = "01";
    $data_guia["mescodi"] = date('Ym');
    $data_guia["usucodi"] = $user->usucodi;
    $data_guia["cpaOper"] = $this->guia_salida->GuiOper;
    $data_guia["CtoOper"] = null;
    $data_guia["TraOper"] = null;
    $data_guia["GuiEFor"] = "1";
    $data_guia["GuiEOpe"] = "P";
    $data_guia["TippCodi"] = "P";
    $data_guia["User_Crea"] = $user->usulogi;
    $data_guia["User_FCrea"] = date('Y-m-d H:i:s');
    $data_guia["User_ECrea"] = gethostname();
    $data_guia["User_Modi"] = null;
    $data_guia["User_FModi"] = null;
    $data_guia["User_EModi"] = null;
    $data_guia["UDelete"] = null;
    $data_guia["GuiXML"] = 0;
    $data_guia["GuiPDF"] = 1;
    $data_guia["GuiCDR"] = 0;
    $data_guia["GuiMail"] = 0;
    $data_guia["fe_rpta"] = 9;
    $data_guia["fe_obse"] = '';
    $data_guia["fe_firma"] = '';
    $data_guia["motcodi"] = MotivoTraslado::TRASLADO_MISMA_EMPRESA;
    $data_guia["e_conformidad"] = GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE;
    unset($data_guia["almacen"]);
    unset($data_guia["GuiUni"]);
    $this->guia_ingreso =  GuiaIngreso::forceCreate($data_guia);
    return $this;
  }

  public function createDetalles()
  {
    $stock_ingreso = $this->guia_ingreso->getAlmacen();
    $items = $this->guia_salida->items;    
    foreach ($items as $detalle_salida) {
      // Crear Detalle del ingreso
      $data_detalle_ingreso = $detalle_salida->toArray();
      $data_detalle_ingreso["GuiOper"] = $this->guia_ingreso->GuiOper;
      $data_detalle_ingreso["Linea"] = agregar_ceros(GuiaSalidaItem::lastId(), 8, 1);
      $data_detalle_ingreso["CpaVtaCant"] = abs($data_detalle_ingreso["CpaVtaCant"]);
      $data_detalle_ingreso["DetTipo"] = "C";

      unset($data_detalle_ingreso['guia'], $data_detalle_ingreso['producto'], $data_detalle_ingreso['unidad']);
      $detalle_ingreso = GuiaSalidaItem::forceCreate($data_detalle_ingreso);
      $detalle_ingreso->agregateInventary($stock_ingreso);
    }

    return $this;
  }

  /**
   * Actualizar Guia de salida
   * 
   * @return self
   */
  public function updateDataGuiaSalida()
  {
    $this->guia_salida->update([
      'CtoOper' => $this->guia_ingreso->GuiOper,
      'docrefe' => $this->guia_ingreso->GuiOper,
      'guiobse' => "Nº TRASLADO :{$this->guia_ingreso->GuiOper}",
      'GuiEOpe' => GuiaSalida::CERRADA,
      'e_traslado' => GuiaSalida::ESTADO_TRASLADO_CERRADO,
      "motcodi" => MotivoTraslado::TRASLADO_MISMA_EMPRESA
    ]);
    return $this;
  }
}
