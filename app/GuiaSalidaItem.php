<?php

namespace App;

use App\GuiaSalida;
use App\Jobs\Guia\CreateDetalleFromTomaDetalle;
use App\Models\TomaInventario\TomaInventarioDetalle;
use App\Models\Traits\InteractWithStock;
use App\Util\ModelUtil\ModelUtil;
use App\Models\Traits\InventaryTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class GuiaSalidaItem extends Model
{
  use
    UsesTenantConnection,
    ModelUtil,
    InteractWithStock,
    InventaryTrait;

  const TIPO_COMPRA = "C";
  const TIPO_VENTA = "V";

  protected $table       = 'guia_detalle';
  protected $primaryKey = 'Linea';
  protected $keyType = 'string';
  const INIT = "00000001";
  public $timestamps = false;
  protected $fillable = [
    'DetItem',
    'GuiOper',
    'Linea',
    'UniCodi',
    'DetNomb',
    'MarNomb',
    'Detcant',
    'DetPrec',
    'DetDct1',
    'DetDct2',
    'DetImpo',
    'DetPeso',
    'DetUnid',
    'DetCodi',
    'DetCSol',
    'DetCDol',
    'CCoCodi',
    'CpaVtaCant',
    'CpaVtaOpe',
    'CpaVtaLine',
    'DetTipo',
    'DetEsPe',
    'DetFact',
    'TidCodi',
    'DetDcto',
    'DetIng',
    'DetSal',
    'DetDeta',
    'lote',
    'detfven',
    'empcodi',
  ];

  public function guia()
  {
    return $this->belongsTo(GuiaSalida::class, 'GuiOper', 'GuiOper');
  }

  public function setDetUnid()
  {
    $this->update(['DetUnid' => $this->unidad->UniAbre]);
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'DetCodi', 'ProCodi')->withoutGlobalScope('noEliminados');
  }

  public function unidad()
  {
    return $this->belongsTo(Unidad::class, 'UniCodi', 'UniCodi');
  }

  public static function lastId()
  {
    $l = self::OrderByDesc('Linea')->first();
    return $l ? $l->Linea : false;
  }

  public static function agregate_cero($numero = false, $set = 0)
  {
    $numero = $numero ? $numero : self::INIT;
    $cero_agregar = [null, "0000000", "000000", "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);
    return $codigoLen < 8 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public static function createItem($vta = null, $id_guia = null, $tipo = "V", $agregateInventary = true)
  {
    $guia = GuiaSalida::find($id_guia);
    $isIngreso = $guia->isIngreso();
    $cantidad_relativa = $vta->DetCant * ($vta->DetFact ?? $vta->Detfact ?? 1);
    $cantidad_absoluta = convertNegativeIfTrue($cantidad_relativa,  $isIngreso == false);
    $guiaItem = new GuiaSalidaItem;
    $guiaItem->DetItem = $vta->DetItem;
    $guiaItem->GuiOper = $guia->GuiOper;
    $guiaItem->Linea   = self::agregate_cero(self::lastId(), 1);
    $guiaItem->UniCodi = $vta->UniCodi;
    $guiaItem->DetNomb = $vta->DetNomb ?? $vta->Detnomb;
    $guiaItem->MarNomb = $vta->MarNomb;
    $guiaItem->Detcant = $vta->DetCant;
    $guiaItem->DetPrec = $vta->DetPrec;
    $guiaItem->DetDct1 = 0;
    $guiaItem->DetDct2 = 0;
    $guiaItem->DetImpo = $vta->DetImpo;
    $guiaItem->DetPeso = $vta->DetPeso;
    $guiaItem->DetUnid = $vta->DetUnid;
    $guiaItem->DetCodi = $vta->DetCodi ?? $vta->Detcodi;
    $guiaItem->DetCSol = $vta->DetCSol;
    $guiaItem->DetCDol = $vta->DetCDol;
    $guiaItem->CCoCodi = null;
    $guiaItem->CpaVtaCant = $cantidad_absoluta;
    $guiaItem->CpaVtaOpe = $vta->GuiOper;
    $guiaItem->CpaVtaLine = NULL;
    $guiaItem->DetTipo =  $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA;
    $guiaItem->DetEsPe = $vta->DetEsPe;
    $guiaItem->DetFact = $vta->Detfact;
    $guiaItem->TidCodi = "";
    $guiaItem->DetDcto = $vta->DetDcto;
    $guiaItem->DetIng = null;
    $guiaItem->DetSal = null;
    $guiaItem->DetDeta = $vta->DetDeta;
    $guiaItem->lote = null;
    $guiaItem->detfven = null;
    $guiaItem->empcodi = $guia->EmpCodi;
    $guiaItem->save();
    $guiaItem->updateStock2($guiaItem->DetCodi);
  }

  public function a()
  {
    return 2;
  }

  public static function createItems($id_guia, $data_items, $tipo = "V")
  {
    $guia = GuiaSalida::find($id_guia);
    $i = 1;
    $empcodi = $guia->EmpCodi;
    $isIngreso = $guia->isIngreso();

    foreach ($data_items as $item) {
      $guiaItem = new GuiaSalidaItem;
      $unidad = Unidad::find($item['UniCodi']);
      $factor = $unidad->getFactor();
      $cant = $item['DetCant'] ?? $item['Detcant'];
      $cantidad_relativa = $cant * $factor;
      $cantidad_absoluta = convertNegativeIfTrue($cantidad_relativa,  $isIngreso == false);
      $guiaItem->DetItem = agregar_ceros($i, 2, 0);
      $guiaItem->GuiOper = $guia->GuiOper;
      $guiaItem->Linea   = self::agregate_cero(self::lastId(), 1);
      $guiaItem->UniCodi = $item['UniCodi'];
      $guiaItem->DetNomb = $item['DetNomb'];
      $guiaItem->MarNomb = isset($item['Marca']) ? $item['Marca'] : "SIN DEFINIR";
      $guiaItem->Detcant = $cant;
      $guiaItem->DetPrec = $item['DetPrec'];
      $guiaItem->DetPeso = $unidad->UniPeso * $guiaItem->Detcant;
      $guiaItem->DetDct1 = null;
      $guiaItem->DetDct2 = null;
      $guiaItem->DetImpo = decimal($guiaItem->Detcant * $guiaItem->DetPrec, 2);
      $detUnid = isset($item["DetUniNomb"]) ? explode('-', $item["DetUniNomb"])[0] :  '00000000';
      $guiaItem->DetUnid = trim($detUnid);
      $guiaItem->DetCodi = $item['DetCodi'];
      $guiaItem->CCoCodi = null;
      $guiaItem->CpaVtaLine = NULL;
      $guiaItem->CpaVtaCant = $cantidad_absoluta;
      $guiaItem->DetTipo = $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA;;
      $guiaItem->DetEsPe = "0";
      $guiaItem->DetFact = $factor;
      $guiaItem->empcodi = $empcodi;
      $guiaItem->DetIng = null;
      $guiaItem->save();
      $guiaItem->setDetUnid();
      $guiaItem->calculateTotal();
      $guiaItem->updateStock2($guiaItem->DetCodi);
      $i++;
    }
  }


  /**
   * Agregar o reducir el stock del producto 
   * 
   * 
   * @return void
   */
  public function stockProductUpdate()
  {
  }



  public function getDetPesoAttribute()
  {
    return decimal($this->attributes['DetPeso']);
  }

  public function PesoTotal()
  {
    return decimal($this->DetCant * $this->DetPeso);
  }


  public function sinProductosEnviados()
  {
    $this->DetSdCa = $this->DetCant;
    $this->save();
  }



  public function calculateTotal()
  {
  }

  public function restoreInvetario()
  {
    $producto = $this->producto;
    $cantidad_guia = $this->Detcant;
    $cantidad_stock = $producto->prosto1;
    $producto->prosto1 = $producto->prosto1 + $cantidad_guia;
    $producto->save();
  }

  public function itemVenta()
  {
    return $this
      ->guia
      ->venta
      ->items
      ->where('UniCodi', $this->UniCodi)
      ->where('DetCodi', $this->DetCodi);
  }

  public function createFromTomaDetalle(TomaInventarioDetalle $detalle)
  {
    return (new CreateDetalleFromTomaDetalle($detalle, true, true))->handle();

    #Code here ............
  }


  public function calcularItemPorEnviar()
  {
    // return $this->
  }

  public function updateCantAbsoluta(bool $isIngreso)
  {
    $cantidad_absoluta = $this->DetFact * $this->Detcant;
    $cantidad_absoluta = convertNegativeIfTrue($cantidad_absoluta, $isIngreso);
    $this->update([
      'CpaVtaCant' => $cantidad_absoluta,
      'DetTipo' => $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA
    ]);
  }
}
