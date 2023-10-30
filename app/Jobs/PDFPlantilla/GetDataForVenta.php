<?php

namespace App\Jobs\PDFPlantilla;

use App\Venta;
use App\CondicionVenta;
use App\Jobs\Empresa\ImgStringInfo;
use App\PDFPlantilla;

class GetDataForVenta extends GetDataPDFAbstract
{
  public function handle()
  {
    $e = get_empresa();
    $plantilla_data = $this->plantilla_data;
    $forma_pago =  $e->formas_pagos->first();
    $medio_pago_nombre = $e->medios_pagos->last()->TpgNomb;

    $user = $e->userOwner();
    $local = $user->localPrincipal();

    $venta_fake = $plantilla_data->getVenta($forma_pago->conCodi);
    $cliente = $plantilla_data->getCliente();
    $venta = $venta_fake->toArray();
    $firma = $venta_fake->dataQR($e->EmpLin1, $cliente->TDocCodi, $cliente->PCRucc);
    $items = $plantilla_data->items;
    $opciones = $e->opcion;
    // $local = user_()->localPrincipal();
    $empresa =  $e->toArray();
    $empresa['igv_porc'] = $e->opcion->Logigv;
    $bancos = Venta::getFormatBancos($e->bancos->groupBy('BanCodi'));

    // ---------Dirección---------
    $direccion =  $e->getDirecciones();
    $telefonos = $local->LocTele;
    $condiciones = explode("-", CondicionVenta::getDefault($this->empcodi));
    $logoDocumento  =  $e->getLogo($this->formato);
    $decimales = $opciones->DecSole;
    $empresa['EmpLogo'] = null;
    $venta['empresa'] = null;
    $venta['nombre_documento'] = $plantilla_data->nombre_documento;
    $size = $this->formato == Venta::FORMATO_TICKET ? 200 : 100;
    $qr = \QrCode::format('png')->size($size)->generate($firma);
    $qrData = $firma;
    $logoMarcaAguaSizes = null;

    $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
    if ($logoMarcaAgua) {
      $logoMarcaAguaSizes = (new ImgStringInfo($e->FE_RESO))->getInfo();
    }

    $documento_id = $plantilla_data->documento_id;
    $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
    $logoSubtitulo = $e->getLogoEncodeSubtitulo();
    $guias = ['T001-000001'];
    return [
      'title' => '',
      'venta' => $venta,
      'venta2' => $venta_fake,
      'empresa' => $empresa,
      'isPreventa' => false,
      'bancos' => $bancos,
      'venta_rapida' => $e->hasVentaRapida(),
      'hasFormato' => true,
      'logoDocumento'  => $logoDocumento,
      'logoMarcaAgua' => $logoMarcaAgua,
      'logoMarcaAguaSizes' => $logoMarcaAguaSizes,
      'logoSubtitulo' => $logoSubtitulo,
      // 'direccion' => $e->direccionFormato(),
      'cliente_correo' => getNombreCorreo($e->EmpLin3),
      'correo' => $e->EmpLin3,
      'telefonos'     => $telefonos,
      'nombre_documento' =>  $plantilla_data->nombre_documento,
      'documento_id' => $documento_id,
      'condiciones' => $condiciones,
      'guias' => $guias,
      'decimals' => $decimales,
      'direccion' => $direccion,
      'contacto' => '',
      'vendedor' => 'OFICINA',
      'vendedor_nombre' => 'OFICINA',
      'fecha_emision_' => '2022-07-05',
      'observacion' => '',
      'peso' => 10,
      'base' => $this->pdfPlantilla->venta_base,
      'igv_porcentaje' => get_option('Logigv'),
      'igv' => $this->pdfPlantilla->venta_igv,
      'total' => $this->pdfPlantilla->venta_total,
      'mostrar_igv' => true,
      'orden_campos' => [
        'valor_unitario' => false,
        'precio_unitario' => true,
      ],
      'valor_unitario' => 1,
      'valor_unitario' => 1,
      'cifra_letra' => $venta_fake->cifra_letra(),
      'cliente'     => $cliente,
      'moneda'      => $venta_fake->moneda,
      'moneda_abreviatura'  => $venta_fake->moneda->monabre,
      'forma_pago'  => $forma_pago,
      'medio_pago_nombre' => $medio_pago_nombre,
      'items'       => $items,
      'qr'          => $qr,
      'firma'          => '',
    ];
  }
}
