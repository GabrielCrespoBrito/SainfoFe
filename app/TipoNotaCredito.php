<?php

namespace App;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class TipoNotaCredito extends Model
{
  use UsesSystemConnection;

  protected $table       = 'ventas_tipo_notacredito';  
  protected $primaryKey   = "id";  
  protected $keyType   = "string";
  protected $timetamps   = false;

  const CODE_01_ANULACION_OPERACION = '01'; // 'Anulación de la operación'
  const CODE_02_ANULACION_ERROR_RUC = '02'; // 'Anulación por error en el RUC'
  const CODE_03_CORECCION_DESCRIPCION = '03'; // 'Corrección por error en la descripción'
  const CODE_04_DESCUENTO_GLOBAL = '04'; // 'Descuento global'
  const CODE_05_DESCUENTO_ITEM = '05'; // 'Descuento por ítem'
  const CODE_06_DEVOLUCION_TOTAL = '06'; // 'Devolución total'
  const CODE_07_DEVOLUCION_ITEM = '07'; // 'Devolución por ítem'
  const CODE_08_BONIFICACION = '08'; // 'Bonificación'
  const CODE_09_DISMINUCION_VALOR = '09'; // 'Disminución en el valor'
  const CODE_10_OTROS_CONCEPTOS = '10'; // 'Otros Conceptos'
  const CODE_13_AJUSTES_FECHA_MONTO = '13'; // 'Ajuste de montos y/o fechas de pago'


  const DESCRIPCION_01_ANULACION_OPERACION = 'Anulación de la operación';
  const DESCRIPCION_02_ANULACION_ERROR_RUC = 'Anulación por error en el RUC';
  const DESCRIPCION_03_CORECCION_DESCRIPCION = 'Corrección por error en la descripción';
  const DESCRIPCION_04_DESCUENTO_GLOBAL = 'Descuento global';
  const DESCRIPCION_05_DESCUENTO_ITEM = 'Descuento por ítem';
  const DESCRIPCION_06_DEVOLUCION_TOTAL = 'Devolución total';
  const DESCRIPCION_07_DEVOLUCION_ITEM = 'Devolución por ítem';
  const DESCRIPCION_08_BONIFICACION = 'Bonificación';
  const DESCRIPCION_09_DISMINUCION_VALOR = 'Disminución en el valor';
  const DESCRIPCION_10_OTROS_CONCEPTOS = 'Otros Conceptos';
  const DESCRIPCION_13_AJUSTES_FECHA_MONTO = 'Ajuste de montos y/o fechas de pago';

}