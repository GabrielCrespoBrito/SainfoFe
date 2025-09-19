<?php

namespace App\Jobs\ImportFromXmls;

use App\Local;
use App\Venta;
use App\Vendedor;
use App\FormaPago;
use App\VentaItem;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class VentaFromData extends CreateFromDataAbstract
{
  public $venta;
  public $vtaoper;

  public function getFormaPago($forma_pago = null)
  {
    return FormaPago::CODIGO_CONTABLE_GENERAL;
  }
  public function getVentaData()
  {
    $documentoNombre = $this->data['documento_correlativo'];
    $documentoArray = explode( "-", $documentoNombre );
    $documentoSerie = $documentoArray[0];
    $documentoCorrelativo = $documentoArray[1];
    list($year, $month, $day) = explode( "-", $this->data['fecha_emision'] );

    return logger_return([
      'VtaOper' =>  $this->cacheTemp->getVentaOper(),
      'EmpCodi' => $this->empresaId,
      'PanAno' => $year,
      'PanPeri' => $month,
      'TidCodi' => $this->data['tipo_documento'],
      'VtaSeri' => $documentoSerie,
      'VtaNumee' => $documentoCorrelativo,
      'VtaNume' => $documentoNombre,
      'VtaUni' => $this->data['tipo_documento'] . '-' . $documentoNombre,
      'VtaFvta' => $this->data['fecha_emision'],
      'VtaFpag' => $this->data['fecha_emision'],
      'VtaFVen' => $this->data['fecha_emision'],
      'TpgCodi' => "01",
      'PCCodi' => $this->cacheTemp->getCliente(
        $this->data['ruc_cliente'],
        $this->data['nombre_cliente'],
        $this->data['tipo_documento_cliente'],
        $this->empresaId
      ),
      'ConCodi' => $this->getFormaPago($this->data['forma_pago']),
      'ZonCodi' => "0100",
      'MonCodi' => $this->data['moneda'] == "PEN" ? "01" : "02",
      'Vencodi' => $this->cacheTemp->getVendedorDefault()->Vencodi,
      'VtaObse' =>  '',
      'VtaTcam' =>$this->cacheTemp->getTipoCambio($this->data['fecha_emision']),
      'Vtacant' => $this->data['cantidad_items'],
      'Vtabase' => $this->data['total_sinigv'],
      'VtaIGVV' => $this->data['total_conigv'] - $this->data['total_sinigv'],
      'VtaDcto' => 0,
      'VtaInaf' => 0,
      'VtaExon' => 0,
      'VtaGrat' => 0,
      'VtaISC' => 0,
      'VtaImpo' => $this->data['total'],
      'VtaEsta' => "V",
      'UsuCodi' => $this->cacheTemp->getUserPrincipal(),
      'MesCodi' => $year . $month,
      'LocCodi' => $this->cacheTemp->getLocalDefault(),
      'VtaPago' => 0,
      'VtaSald' => $this->data['total'],
      'VtaEsPe' => "NP",
      'VtaPPer' => 0,
      'VtaAPer' => 0,
      'VtaPerc' => 0,
      'VtaTota' => $this->data['total'],
      'VtaSPer' => 0,
      'TipCodi' => '111201',
      'AlmEsta' => 'SA',
      'CajNume' => $this->cacheTemp->getCajNume($this->data['fecha_emision']),
      'VtaSdCa' => 0,
      'VtaHora' => $this->data['hora_emision'],
      'vtafact' => 0,
      'vtaanu' => null,
      'vtaadoc' => null,
      'VtaXML' => 1,
      'TipoOper' => 'N',
      'VtaTotalAnticipo' => 0,
      'VtaPDF' => 1,
      'VtaCDR' => 1,
      'VtaMail' => 0,
      'VtaFMail' => StatusCode::ERROR_0011['code'],
      'VtaPedi' => null,
      'VtaEOpe' => null,
      'User_Crea' => $this->cacheTemp->getUserPrincipalUserName(),
      'User_ECrea' => gethostname(),
    ]);
  }

  public function createVenta()
  {
    $this->venta = new Venta();
    $this->venta->fill($this->getVentaData())->save();
    return $this->venta;
  }

  public function createItems()
  {
    $items = $this->data['items'];
    foreach ($items as $item) {

      $productoData = $this->cacheTemp->getProducto($item['item_descripcion']);
      
      $dataItem = [];
      $dataItem['Linea'] = $this->cacheTemp->getLinea();
      $dataItem['DetItem'] = agregar_ceros( $item['item_orden'], 2);
      $dataItem['VtaOper'] = $this->venta->VtaOper;
      $dataItem['EmpCodi'] = $this->empresaId;
      $dataItem['DetUnid'] = $productoData['UniAbre'];
      $dataItem['DetCodi'] = $productoData['ProCodi'];
      $dataItem['DetNomb'] = $productoData['ProNomb'];
      $dataItem['DetCant'] = $item['item_cantidad'];
      $dataItem['DetPrec'] = $item['item_precio_bruto'];
      $dataItem['DetImpo'] = $item['item_valor_bruto'] + $item['item_igv'];
      $dataItem['DetEsta'] = 'V';
      $dataItem['DetEsPe'] = '0';
      $dataItem['DetBase'] = 'GRAVADA'; 
      $dataItem['DetISC'] = 0;
      $dataItem['DetISCP'] = 0;
      $dataItem['Detfact'] = 1;
      $dataItem['DetIGVV'] = $item['item_igv_porc'];
      $dataItem['DetIGVP'] = $item['item_igv'];
      $dataItem['DetPercP'] = 0;
      $dataItem['TipoIGV'] = 10;
      $dataItem['incluye_igv'] = 1;

      logger('dataItem', $dataItem);

      $vtaItem = new VentaItem();
      $vtaItem->fill($dataItem);
      $vtaItem->save();
      // $vtaItem->fill($dataItem)->save();
    }
  }

  public function consultarSunat()
  {
    $this->venta->searchSunatGetStatus(false, true);
  }

  public function handle()
  {
    logger("handleVentaFromData: " . $this->data['documento_correlativo']);
    $this->createVenta();
    $this->createItems();
    // $this->consultarSunat();
  }
}
