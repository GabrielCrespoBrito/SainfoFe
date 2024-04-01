<?php

namespace App\Jobs\Venta;

class PrepareDataVentaForJavascriptPrint
{
  public $data;
  public $convert_data;
  
  public function __construct($data)
  {
    $this->data = $data;
  }

  public function setDataEmpresa()
  {
    $this->convert_data['empresa_logo_path'] = $this->data['logo_ticket_url'];
    $this->convert_data['empresa_nombre'] = $this->data['empresa']['EmpNomb'];
    $this->convert_data['empresa_nombre_comercial'] = $this->data['empresa']['EmpLin5'];
    $this->convert_data['empresa_direccion'] = removeformatoText($this->data['direccion']);
    $this->convert_data['empresa_ruc'] = 'R.U.C ' .  $this->data['empresa']['EmpLin1'];
    $this->convert_data['empresa_telefonos'] = $this->data['telefonos'];
    $this->convert_data['empresa_correos'] = $this->data['correo'];
  }

  public function setDataCliente()
  {
    $this->convert_data['cliente_razon_social'] = 'Razon Social: ' . $this->data['cliente']->PCNomb;
    $this->convert_data['cliente_documento'] = $this->data['cliente']->getNombreTipoDocumento() . ' ' .  $this->data['cliente']->PCRucc;
    $this->convert_data['cliente_direccion'] = 'Direccion: ' .  $this->data['cliente']->PCDire;
  }

  public function setDataDocumento()
  {
    $this->convert_data['documento_nombre'] = $this->data['nombre_documento'];
    $this->convert_data['documento_id'] = $this->data['documento_id'];
    $this->convert_data['documento_observacion'] = 'Observaciòn: ' .  $this->data['venta']['VtaObse'] ?? '-';
    $this->convert_data['documento_fecha'] = $this->data['venta']['VtaFvta'];
    $this->convert_data['documento_forma_pago'] = $this->data['forma_pago']->connomb;
    $this->convert_data['documento_vendedor'] = $this->data['vendedor_nombre'];
    $this->convert_data['documento_guias'] = implode(',', $this->data['guias']);
    $this->convert_data['documento_responsable'] = $this->data['venta']['User_Crea'];
    $this->convert_data['documento_orden_compra'] = $this->data['venta']['VtaPedi'] ?? '';


    $this->convert_data['documento_hash'] = $this->data['firma'];
    $this->convert_data['documento_hora'] = $this->data['venta']['VtaHora'];
    $this->convert_data['documento_peso'] = decimal($this->data['peso']);
    $this->convert_data['direccion_consulta'] = removeHttp(config('app.url_busqueda_documentos'));
    $this->convert_data['monto_letra'] = 'Son: ' . $this->data['cifra_letra'];
    $this->convert_data['moneda_abbreviatura'] = $this->data['moneda_abreviatura'];
    $this->convert_data['qr_data'] = $this->data['qrData'];
  }

  public function setItemsData()
  {
    foreach ($this->data['items'] as $item) {


      $this->convert_data['items'][] = [
        'unidad' => $item->DetUnid,
        'descripcion' => removeWhiteSpace($item->DetNomb),
        'cantidad' => $item->DetCant,
        'precio_unitario' => decimal( $item->getTotal('precio_unitario'), $this->data['decimals']),
        'importe' => decimal($item->DetImpo, $this->data['decimals']),
      ];

    }    
  }

  public function setInfoAdicionalsData()
  {
    $infos_adicional = [];

    $venta = $this->data['venta2'];
    $decimals = $this->data['decimals'];
    $moneda_abreviatura = $this->data['moneda_abreviatura'];

    if ($this->data['placa'] ) {
      $infos_adicional[] = ['descripcion' => sprintf('Placa: %s', $this->data['placa']) ];
    }

    if ($venta->isNota()) {
      $infos_adicional[] = [
        'descripcion' => sprintf('Referencia: %s %s %s %s %s',
            $venta->VtaTDR,
            $venta->VtaSeriR,
            $venta->VtaNumeR,
            $venta->VtaFVtaR,
            $venta->VtaObse )];
    }

    if ($venta->hasDetraccion()) {
      $infos_adicional[] = [
        'descripcion' => sprintf(
          'Detracciòn: %s (%s) Porcentaje: %s%% Total: %s',
          $venta->detraccion->descripcion,
          $venta->VtaDetrCode,
          $venta->VtaDetrPorc,
          decimal($venta->VtaDetrTota) )];
    }


    if ($venta->hasMontoRetencion()) {
      $infos_adicional[] = [
        'descripcion' => sprintf(
          'Información de la retención: Base imponible: %s %s Porcentaje: %s%% Monto: %s %s',
          $moneda_abreviatura,
          fixedValue($venta->VtaImpo,$decimals), 
          $venta->retencionPorc(),
          $moneda_abreviatura,
          $venta->retencionMonto())];
    }

    if ($venta->hasAnticipo()) {
      $infos_adicional[] = [
        'descripcion' => sprintf(
          'Doc: %s %s Total: %s %s',
          $venta->getNombreTipoDocucumentoAnticipo(),
          $venta->VtaNumeAnticipo,
          $moneda_abreviatura,
          $venta->VtaTotalAnticipo)];
    }       

    $this->convert_data['infos_adicional'] = $infos_adicional;
  }


  public function setTotalsData()
  {
    $totals = [];

    $venta = $this->data['venta2'];
    // Original
    // $decimals = $this->data['decimals'];
    $decimals = 1;

    $totals[] = [
      'descripcion' => 'OP. GRAVADAS.:',
      'value' =>  fixedValue($venta->Vtabase, $decimals)
    ];

    if( $venta->hasMontoExonerado()){
      $totals[]= [
        'descripcion' => 'OP. EXONERADAS.:',
        'value' =>  fixedValue($venta->VtaExon, $decimals)
      ];
    }

    if ($venta->hasMontoInafecto()) {
      $totals[] = [
        'descripcion' => 'OP. INAFECTAS.:',
        'value' =>  fixedValue($venta->VtaInaf, $decimals)
      ];
    }

    if ($venta->hasMontoGrauito()) {
      $totals[] = [
        'descripcion' => 'OP. GRATUITA.:',
        'value' =>  fixedValue($venta->VtaGrat, $decimals)
      ];
    }

    if ($venta->hasMontoDcto()) {
      $totals[] = [
        'descripcion' => 'TOTAL DCTO.:',
        'value' =>  fixedValue($venta->VtaDcto, $decimals)
      ];
    }

    if ($venta->hasMontoICBPER()) {
      $totals[] = [
        'descripcion' => 'ICBPER.:',
        'value' =>  fixedValue($venta->icbper, $decimals)
      ];
    }

    if ($venta->hasMontoISC()) {
      $totals[] = [
        'descripcion' => 'ISC.:',
        'value' =>  fixedValue($venta->VtaISC, $decimals)
      ];
    }

    // $totals[] = [
    //   'descripcion' => 'IGV.:',
    //   'value' =>  fixedValue($venta->VtaIGVV, $decimals)
    // ];

    if ($venta->hasMontoPercepcion()) {
      $totals[] = [
        'descripcion' => 'PERCECPIÒN.:' . $venta->percepcionPorc() . '%' ,
        'value' =>  fixedValue($venta->percepcionMonto(), $decimals)
      ];
    }

    if ($venta->hasAnticipo()) {
      $totals[] = [
        'descripcion' => 'ANTICIPO.:',
        'value' =>  fixedValue($venta->VtaTotalAnticipo, $decimals)
      ];
    }
    
    // Total
    $totals[] = [
      'descripcion' => 'TOTAL.:',
      'value' =>  fixedValue($venta->VtaImpo, $decimals)
    ];
  
    $this->convert_data['totals'] = $totals;
  }

  public function setBancosData()
  {
    $this->convert_data['bancos'] = $this->data['bancos'];
  }

  public function convert()
  {
    $this->setDataEmpresa();
    $this->setDataCliente();
    $this->setDataDocumento();
    $this->setItemsData();
    $this->setInfoAdicionalsData();
    $this->setTotalsData();
    $this->setBancosData();
    return $this->convert_data;
  }
}