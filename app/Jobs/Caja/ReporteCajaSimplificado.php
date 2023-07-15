<?php

namespace App\Jobs\Caja;

use App\Caja;

class ReporteCajaSimplificado
{
  protected $caja;
  protected $data = [];

  public function __construct( Caja $caja)
  {
    $this->caja = $caja;
  }

  public function handle()
  {
    $this->setBasicData();
    return $this;
  }

  public function setBasicData()
  {
    $empresa = get_empresa();

    $cajaMovimientos = $this->caja->getDataMovimientos();

    $data = [
      'reporte_titulo' => 'Reporte de Caja Simplificado',
      'caja_nombre'    => $this->caja->CajNume,
      'fecha_apertura' => $this->caja->CajFech,
      'fecha_cierre' => $this->caja->CajFecC ?? '-',
      'estado'  => $this->caja->getNombreEstado(),
      'usuario' => $this->caja->User_Crea,
      'empresa' => $empresa->nombreRuc(),
      'saldo_apertura' => $cajaMovimientos->saldo_apertura,
      'ingresos' => $cajaMovimientos->ingresos,
      'salidas' => $cajaMovimientos->salidas,
      'total_ventas' => $cajaMovimientos->total_ventas,
      'pago_efectivo' => $cajaMovimientos->pago_efectivo,
      'saldo' => $cajaMovimientos->saldo,
      'metodos_pagos' => $cajaMovimientos->metodos_pagos,
    ];

    $this->data = $data;
  }
  
  public function getData()
  {
    return $this->data;
  }
}