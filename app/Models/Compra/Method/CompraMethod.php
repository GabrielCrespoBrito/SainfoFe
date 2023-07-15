<?php 

namespace App\Models\Compra\Method;

use App\Compra;
use App\Moneda;
use App\CajaDetalle;
use App\Cotizacion;
use App\Models\MedioPago\MedioPago;
use App\TipoDocumentoPago;

trait CompraMethod
{
  public function updateOrdenCompra()
  {
    optional(Cotizacion::ordenCompra($this->Docrefe))
    ->setFacturadoState($this->CpaOper);
  }

  public function nombreDocumento()
  {
    return TipoDocumentoPago::getNombreDocumento($this->TidCodi);
  }

	/**
	 * @TODO Saber si la compra es un ingreso o egreso
	 * @return bool
	 */
	public function isIngreso()
	{
		return true;
	}

	/**
	 * Realizar calculos con items
	 * 
	 * @return void
	 */
	public function calculateTotal()
	{
    $items = $this->items;
		$data = [];
		$igv = 0;
		
		foreach( $items as $item ){
			$igv += $item->calculateIGV();
		}
		
    $data['CpaSdCa'] = decimal($items->sum('DetCant'));
		$data['Cpabase'] = decimal( $items->sum('DetImpo'));
    $data['CpaIGVV'] = decimal( $igv);
    $data['CpaImpo'] = decimal( $data['Cpabase'] + $data['CpaIGVV']);
    $data['Cpatota'] = decimal( $data['CpaImpo']);
    $data['CpaSald'] = decimal( $data['CpaImpo']);
    
		$this->update($data);
	}

  /**
   * Si ya se generarón las guias suficientes del despacho
   * 
   * 
   */
  public function despachoCompletado()
  {
    return $this->CpaSdCa <= 0;
  }

	/**
	 * Actualizar información del movimiento de la caja
	 * 
	 * @return bool
	 */
	public function updateMovimiento()
	{
		$mov = $this->caja_movimiento;

		if (is_null($mov)) {
			return;
		}

		# Actualizar
		$isDolar = $this->isDolar();
		$isIngreso = $this->isIngreso();
		$monto = $this->PagImpo;

		$dataUpdate = [
			'CANINGS' => 0,
			'CANINGD' => 0,
			'CANEGRS' => 0,
			'CANEGRD' => 0,
		];

		# Poner montos 
		if ($isIngreso) {
			$dataUpdate['CANINGS'] = $isDolar ? 0 : $monto;
			$dataUpdate['CANINGD'] = $isDolar ? $monto : 0;
		} else {
			$dataUpdate['CANEGRS'] = $isDolar ? 0 : $monto;
			$dataUpdate['CANEGRD'] = $isDolar ? $monto : 0;
		}

		# Actualizar
		$mov->update($dataUpdate);
	}

	public function registerMovimiento()
	{
		set_timezone();
		$caja = $this->caja;
		$data = [];		
		$data['egreso_tipo'] = "000";
		$data['CajNume'] = $caja->CajNume;
		$data['fecha'] = $this->CpaFCpa;
		$data['nombre'] = 'EGRESO';
		$data['moneda'] = $this->moncodi;
		$data['monto'] = $this->Cpatota;
		$data['DocNume'] = $this->CpaOper;
		$data['autoriza'] = $this->User_Crea;
		$data["otro_doc"] = '';
		$data['motivo'] = "000";
		CajaDetalle::registrarEgresoCaja( $data, $caja );
	}

	/**
	 * Borrar items y quitar del almacen
	 */
	public function deleteItems()
	{
		$this->deleteMany('items', function ($item) {
			$item->reduceInventary();
		});
	}

	public function getImporteAttribute()
	{
		return $this->CpaImpo;
	}

	public function getSaldoAttribute()
	{
		return $this->CpaSald;
	}

	public function getCorrelativeAttribute() 
	{
		return $this->CpaNume;
	}

  public function getCompleteCorrelativo()
  {
    return sprintf('%s-%s-%s', $this->TidCodi , $this->CpaSerie, $this->CpaNumee  );
  }

	public function deudaSaldada()
	{
		return $this->CpaSald <= 0;
	}

	/**
	 * Comprobar que un monto dado, no es supperior a lo que falta por cancelar en el documento
	 *
	 * @return void
	 */
  public function montoPagoValid( $valor , $moneda_id, $tCambio = null )
  {
    if( $moneda_id != $this->moncodi ){
      $valor = $moneda_id == Moneda::SOL_ID ? ($valor / $tCambio) : ($valor * $tCambio);
    }

    return $valor <= $this->CpaSald;
	}
	
	/**
	 * Actualizar cuando se ha pagado de la deuda, recorriendo los pagos que se han realizado
	 * 
	 * @return $this
	 */
	public function updateSaldo()
	{
		$totalPagado = 0;

		if ($this->pagos->count()) {
			foreach ($this->pagos as $pago) {
				$totalPagado += $pago->getRealImporte();
			}
		}

		$this->update([
			'CpaSald' => $this->importe - $totalPagado,
			'CpaPago' => $totalPagado
		]);

		return $this;
	}


	public function getMoneda()
	{
		return $this->moncodi;
	}


  public function resetProductEnviados()
  {
    $items = $this->items;
    $cant = $items->sum('DetCant');
    
    $this->update([
      'CpaSdCa' => $cant,
      'GuiOper' => null,
    ]);

    foreach( $items as $item ){
      $item->update([
        'DetSdCa' => $item->DetCant
      ]);
    }
  }


	/**
	 * Actualización productos enviado del documento
	 */
	public function updateCantProdEnviados($guiOper = null)
	{
    foreach( $this->items as $item ){
      $item->DetSdCa = 0;
      $item->save();
    }

    $this->CpaSdCa = 0;
    $this->GuiOper = $guiOper;
		$this->save();
	}


	/**
	 * Si la compra es de tipo nota de credito
	 * 
	 * @return bool
	 */
	public function isNC()
	{
		return $this->TidCodi == "07";
	}

	public function numero($separator = '-')
	{
		return $this->CpaSerie . $separator . $this->CpaNumee;
	}

	public function fechaEmision($separator = '-')
	{
		return $this->CpaFCpa;
	}

	public function documentoReferencia($separator = '-')
	{
		return '';
	}

	public function clienteRazonSocial($separator = '-')
	{
		return $this->cliente_with->PCNomb;
	}

	public function monedaAbbreviatura()
	{
		return Moneda::getAbrev($this->getMoneda());
	}

	public function importe()
	{
		return $this->CpaImpo;
	}

	public function saldo()
	{
		return $this->CpaSald;
	}

	public function pago()
	{
		return $this->CpaPago;
	}

	public function condicion()
	{
		return optional($this->forma_pago)->connomb;
	}


	public function estado()
	{
		return $this->CpaEOpe;
	}





  /**
   * Actualizar la deuda de la nota de credito
   * 
   * @return $this
   */
  public function updateDeudaByPagoNotaCredito()
  {
    $this->updateSaldo();
    
    $totalPagado = $this->CpaPago;
    $pagos = $this->pagos_credito;


    if ($pagos->count()) {
      foreach ($pagos as $pago) {
        $totalPagado += $pago->getRealImporte();
      }
      $saldo = $this->importe - $totalPagado;

      $this->update([
        'CpaSald' => $saldo,
        'CpaPago' =>  $totalPagado
      ]);
    }
  }


  /**
   * Metodos para trabajar con la moneda
   */

  public function isDolar()
  {
    return $this->moncodi == Moneda::DOLAR_ID;
  }

  public function isSol()
  {
    return $this->moncodi == Moneda::SOL_ID;
  }

  public function getMonedaNombre()
  {
    return Moneda::getNombre($this->moncodi);
  }

  public function getMonedaAbreviatura()
  {
    return Moneda::getAbrev($this->moncodi);
  }

  public function getMonedaAbreviaturaSunat()
  {
    return Moneda::getAbrevSunat($this->moncodi);
  }

  /**
   * 
   */
  public static function createCustom($data, $totales)
  {
    $data['usuCodi'] = user_()->usucodi;
    $data['CpaEsta'] = 'C';    
    $data['Cpabase'] = $totales->valor_venta_por_item_igv;
    $data['CpaIGVV'] = $totales->igv;
    $data['CpaImpo'] = $totales->total_cobrado;
    $data['IGVImpo']  = get_option('Logigv');
    $data['IGVEsta']  = $data['IGVEsta'] ?? 0;
    $data['CpaSald'] = $totales->total_cobrado;
    $data['Cpatota'] = $totales->total_cobrado;
    $data['CpaPago'] = 0;
    $data['CpaSdCa'] = $totales->total_cantidad;
    $data['CpaNumee'] =  agregar_ceros($data['CpaNumee'],8,0);
    return Compra::create($data);
  }

  public function updateCustom($data, $totales)
  {
    $data['usuCodi'] = user_()->usucodi;
    $data['CpaEsta'] = 'C';
    $data['Cpabase'] = $totales->valor_venta_por_item_igv;
    $data['CpaIGVV'] = $totales->igv;
    $data['IGVEsta']  = $data['IGVEsta'] ?? 0;
    $data['CpaImpo'] = $totales->total_cobrado;
    $data['IGVImpo'] = get_option('Logigv');
    $data['CpaSald'] = $totales->total_cobrado;
    $data['Cpatota'] = $totales->total_cobrado;
    $data['CpaPago'] = 0;
    $data['CpaSdCa'] = $totales->total_cantidad;
    $this->update($data);
  }


	public function setProductsUltimoCosto()
	{
		foreach( $this->items as $item ){
			$item->producto->updateProductUltimoCosto();
		}
	}

  public function getMedioPagoNombre()
  {
    return optional($this->medio_pago)->TpgNomb;
  }

  public function getMedioPagoNombreForPDF()
  {
    return ($this->TpgCodi == null || $this->TpgCodi == MedioPago::SIN_DEFINIR) ?
      '' :
      $this->getMedioPagoNombre();
  }



  public function dataPdf()
  {
    $proveedor = $this->proveedor;

    $empresa = get_empresa();
    $orden_campos = [
      'valor_unitario' => true,
      'precio_unitario' => false,
    ];
    $decimals = 2;
    return [
      'nombre' => $proveedor->PCNomb,
      'telefonos' => $proveedor->PCTel1,
      'direccion' => $proveedor->PCDire,
      'ruc' => $proveedor->PCRucc,
      'correo' => $proveedor->PCCMail,
      'nombre_documento' => $this->nombreDocumento(),
      'documento_id' => $this->CpaNume,
      'empresa' => [
        'EmpLin1' => $proveedor->PCRucc,
      ],
      'nombre_empresa' => $empresa->EmpNomb,
      'decimals' => $decimals,
      'documento_campo_nombre' => 'RUC'. ':',
      'ruc_empresa' => $empresa->EmpLin1,
      'direccion_empresa' => $empresa->EmpLin2,
      'contacto' => '',
      'vendedor' => optional($this->vendedor)->vennomb,
      'fecha_emision' => $this->CpaFCpa,
      'observacion' => $this->Cpaobse,
      'orden_campos' => $orden_campos,
      'items' => $this->items, 
      'mostrar_igv' => true,
      'igv_porcentaje' => $this->IGVImpo,
      'moneda_abreviatura' => $this->getMonedaAbbre(),
      'peso' => $this->DetPeso,
      'base' => $this->Cpabase,
      'igv' => $this->CpaIGVV,
      'total' => $this->CpaImpo,
    ];
  }

  public function getMonedaAbbre()
  {
    return Moneda::getAbrev($this->moncodi);
  }

  public function incluyeIgv()
  {
    return $this->IGVEsta == Compra::INCLUYE_IGV; 
  }

}	