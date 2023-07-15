<?php

namespace App\Jobs\PDFPlantilla;

use App\Venta;
use App\Moneda;
use App\CondicionVenta;
use App\MotivoTraslado;
use App\Jobs\Empresa\ImgStringInfo;

class GetDataForGuia extends GetDataPDFAbstract
{
  public function getItems()
  {
    $items = $this->plantilla_data->items;

    return $items->map( function($item){
      return (object) [
          'DetCodi' => $item->codigo_producto,
          'Detcant' => $item->cantidad,
          'DetUnid' => $item->unidad,
          'DetDeta' => '',
          'DetNomb' => $item->nombre_item,
          'DetPeso' => 250,
      ];
    });
  }


  public function handle()
  {

    $guia_ = $this->plantilla_data->getGuia();
    
    $guia = $guia_->toArray();
    $firma = 'lorempipsumodlor';
    $e = $this->empresa;

    $empresa =  $e->toArray();
    $empresa['igv_porc'] = $e->opcion->Logigv;
    $bancos = $e->bancos->groupBy('BanCodi');
    $condiciones = explode("\n", CondicionVenta::getDefault());
    $logo  =  $e->logoEncode();
    $logo2 = $e->logoEncode(2);

    $onlyShowLogo2 = $e->fe_formato == 0;
    $allLogos = $e->fe_formato == 1;

    $empresa['EmpLogo'] = null;
    $guia['empresa'] = null;
    $guia['nombre_documento'] = $this->plantilla_data->nombre_documento;
    $qr = \QrCode::format('png')->size(100)->generate($firma);
    $logoDocumento = $e->getLogo(Venta::FORMATO_A4);
    $hasFormato = true;
    $numero_documento = $hasFormato ?  $guia_->numero() : $this->GuiOper;
    $items =  $this->getItems();


    $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
    $logoMarcaAguaSizes = null;
    if ($logoMarcaAgua) {
      $logoMarcaAguaSizes = (new ImgStringInfo($e->FE_RESO))->getInfo();
    }

    $data = [
      // 'title'       => $this->nameEnvio('.pdf'),
      'title'       => $guia_->namePDF($e->ruc()),
      'guia'       => $guia,
      'guia2'       => $guia_,
      'motivos_traslado' => MotivoTraslado::all(),
      'empresa'     => $empresa,
      'hasFormato'     => $hasFormato,
      'logoDocumento' => $logoDocumento,
      'logoMarcaAgua' => $logoMarcaAgua,
      'logoMarcaAguaSizes' => $logoMarcaAguaSizes,
      'moneda_abreviatura' => Moneda::getAbrev($this->plantilla_data->venta_moneda),
      'bancos'     => $bancos,
      'bancos'     => $bancos,
      'direccion'     => $e->getDirecciones(),
      'telefonos'     => $e->EmpLin4,
      'cliente_correo'     => getNombreCorreo($e->EmpLin3),
      'correo'     => $e->EmpLin3,
      'nombre_documento' => $guia['nombre_documento'],
      'documento_id' => $numero_documento,
      //  $this->GuiSeri . '-' . $this->GuiNumee,
      'condiciones' => $condiciones,
      'logo'        => $logo,
      'onlyShowLogo2' => $onlyShowLogo2,
      'logo1'        => $logo,
      'logo2'        => $logo2,
      'allLogos' => $allLogos,
      // 'cliente'     => $this->cliente,
      // 'moneda'      => $this->moneda,
      // 'forma_pago'  => $this->forma_pago,
      // 'items'       => $this->items,
      // 'qr'          => $qr,
      // 'firma'          => $this->fe_firma,
      'cliente'     => $this->plantilla_data->getCliente(),
      'moneda'      => $this->plantilla_data->venta_moneda,
      'forma_pago'  => null,
      'items'       => $items,
      'qr'          => $qr,
      'firma'          => $firma,      
    ];
    return $data;
  }
}

