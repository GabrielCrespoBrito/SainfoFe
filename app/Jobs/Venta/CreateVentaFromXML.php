<?php

namespace App\Jobs\Venta;

use App\Venta;
use App\Moneda;
use App\Producto;
use App\VentaItem;
use App\ClienteProveedor;
use Illuminate\Support\Facades\Log;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Http\Controllers\Util\Sunat\DocumentoStatus;
use App\TipoIgv;

class CreateVentaFromXML
{
	public $pathXml;
	public $empresa;
	public $empresa_id;
	public $lista_id;
	public $producto;
	public $unidad;
	public $user;
	public $loccodi;
	public $xml;
	public $currentBoleta;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($pathXml, $empresa, $user = null, $lista_id = null, $producto, $unidad, $loccodi)
	{
		$this->pathXml = $pathXml;
		$this->empresa = $empresa;
		$this->empresa_id = $empresa->id();
		$this->lista_id = $lista_id;
		$this->producto = $producto;
		$this->unidad = $unidad;
		$this->loccodi = $loccodi;
		$this->user = $user;

		if (! file_exists($this->pathXml)) {
			throw new \Exception("The xml {$this->pathXml} is not found", 1);
		}

		$this->xml = simplexml_load_file($this->pathXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	}


	public function getNodo($nodoPath)
	{
		return $this->xml->xpath($nodoPath);
	}

	public function getNodoSingleValue($nodoPath)
	{
		$simpleXmlElement = $this->getNodo($nodoPath);

		if( count($simpleXmlElement)  ){
			return ((array) $simpleXmlElement[0])[0];
		}

		return null;
	}
	
	public function getClienteCodigo( $boleta )
	{
		$nombre_cliente = 'Simon Bustamantee';
		$documento_cliente =((array)((array) $boleta->xpath('cac:AccountingCustomerParty/cbc:CustomerAssignedAccountID'))[0])[0];
		$tipo_documento_cliente =((array)((array) $boleta->xpath('cac:AccountingCustomerParty/cbc:AdditionalAccountID'))[0])[0];
		$documento_cliente =  ($documento_cliente == '-' || $documento_cliente == '') ? '.' : $documento_cliente;
		$tipo_documento_cliente =  ($tipo_documento_cliente == '-' || $tipo_documento_cliente == '') ? '0' : $tipo_documento_cliente;
		$cliente_proveedor = ClienteProveedor::findOrCreateByRuc( $documento_cliente, $nombre_cliente, $tipo_documento_cliente, $this->empresa_id, $this->user );
		return $cliente_proveedor->PCCodi;
	}

	public function createVentaCab()
	{
		$boleta = $this->currentBoleta;
		// Obtener fecha
		$fecha_obj =  get_date_info($this->getNodoSingleValue('//cbc:IssueDate'));
		// Obtener hora
		$hora = date('H:i:s');   
		// Pedido
		$pedido =  '';
		// guia
		$guia = null;
		// Tipo de documento
		$tipo_documento = ((array)((array) $boleta->xpath('cbc:DocumentTypeCode'))[0])[0];;
		// Nobre documento
		$nombreDocumento = nombreDocumento($tipo_documento);
		// Codigo de cliente
		$pccodi = $this->getClienteCodigo($boleta);
		$correlativo = explode('-', ((array)((array) $boleta->xpath('cbc:ID'))[0])[0]  );
		// Moneda
		$moneda = Moneda::SOL_ID;
		// Firma @TODO
		$firma = '';

		$tipo_nota = '';
		$tipo_documento_referencia = '';
		$serie_documento_referencia = '';
		$correlativo_documento_referencia = '';
		$fecha_documento_referencia = null;
		$totales = '';

	
		$cantidad = 1;
		$descuento = 0;
		$base = 0;
		$igv = 0;
		$inafecta = 0;
		$exonerada = ((array)((array) $boleta->xpath('sac:TotalAmount'))[0])[0];
		$gratuita = 0;
		$isc = 0;
		$total = $exonerada;
		$this->totalBoleta = $total;
		$venta = new Venta();
		$venta->VtaOper =  Venta::UltimoId($tipo_documento);
		$venta->EmpCodi = $this->empresa_id;
		$venta->PanAno = $fecha_obj->year;
		$venta->PanPeri = $fecha_obj->month;
		$venta->TidCodi = $tipo_documento;
		
		$venta->VtaSeri = $correlativo[0];
		$venta->VtaNumee = $correlativo[1];
		$venta->VtaNume = implode('-', $correlativo);
		
		$venta->VtaFvta = $fecha_obj->full;
		$venta->vtaFpag = null;
		$venta->VtaFVen = $fecha_obj->full;

		$venta->PCCodi = $pccodi;
		$venta->ConCodi = '01';
		$venta->ZonCodi = '0100';
		$venta->MonCodi = $moneda;
		$venta->Vencodi = '1OFI';
		$venta->DocRefe = $venta->VtaNume;
		
		$venta->GuiOper = '';

		
		$venta->VtaObse = '';
		$venta->VtaTcam = 4;
		$venta->Vtacant = $cantidad;
		$venta->Vtabase = $base;
		$venta->VtaIGVV = $igv;
		$venta->VtaDcto = $descuento;
		$venta->VtaInaf = $inafecta;
		$venta->VtaExon = $exonerada;
		$venta->VtaGrat = $gratuita;
		$venta->VtaISC = $isc;
		$venta->VtaImpo = $total;
		$venta->VtaEsta = 'V';
		$venta->UsuCodi = $this->user->usucodi;
		$venta->MesCodi = $fecha_obj->mescodi;
		$venta->LocCodi = $this->loccodi;
		$venta->VtaPago = 0;
		$venta->VtaSald = $venta->VtaImpo;
		$venta->VtaPago = 0;
		$venta->VtaEsPe = 'NP';
		$venta->VtaPPer = '0.00';
		$venta->VtaAPer = '0.00';
		$venta->VtaPerc = '0.00';
		$venta->VtaTota = $venta->VtaImpo;
		$venta->TipCodi = '111201';
		$venta->AlmEsta = 'SA';
		$venta->CajNume = 'todo';
		$venta->VtaSdCa = '0';
		$venta->VtaHora = $hora;
		$venta->vtafact = 0;
		$venta->vtaanu = null;
		$venta->vtaadoc = $tipo_nota;
		$venta->VtaPedi = $pedido;
		$venta->User_Crea =  $this->user->usulogi;
		$venta->User_FCrea = $fecha_obj->full . ' ' . $hora ;
		$venta->User_ECrea = gethostname();
		$venta->fe_estado = "ESTADO SUNAT(0)";
		
		$venta->fe_obse =  "La {$nombreDocumento} numero {$venta->VtaNume}, ha sido aceptada";
		$venta->fe_rpta =  0;
		$venta->fe_rptaa =  2;
		$venta->fe_firma =  $firma;
		// Documento Referencia si es NOTA
		$venta->VtaTDR = $tipo_documento_referencia;
		$venta->VtaSeriR = $serie_documento_referencia;
		$venta->VtaNumeR = $correlativo_documento_referencia;
		$venta->VtaFVtaR =  $fecha_documento_referencia;
		//
		$venta->VtaXML = '1';
		$venta->VtaPDF = '1';
		$venta->VtaCDR = '1';
		$venta->VtaMail = 0;
		$venta->VtaFMail = StatusCode::ERROR_0011['code'];
		$venta->Numoper = $guia;
		$venta->TipoOper = 'N';
		$venta->fe_version = null;
		$venta->VtaDetrPorc = null;
		$venta->VtaDetrTota = null;
		$venta->VtaTotalDetr = null;

		
		$totales = (object) [
		'total_valor_bruto_venta' => 0,
		'total_valor_venta' => 0,
      	'valor_venta_por_item_igv' => 0,
      	'descuento_global' => 0,
      	'total_importe' => 0,
      	'impuestos_totales' => 0,
		];


		$venta->CuenCodi = $totales;
		$venta->VtaDetrCode = '0';
		$venta->VtaAnticipo = '0';
		$venta->VtaNumeAnticipo = '';
		$venta->VtaTidCodiAnticipo = null;
		$venta->VtaTotalAnticipo = 0;
		$venta->contingencia = 0;
		$venta->icbper = 0; 

		$venta->save();
		$this->venta = $venta;
	}


	public function getTipoTagLine()
	{
		return [
			'01' => 'InvoiceLine' , 
			'03' => 'InvoiceLine',
			'07' => 'CreditNoteLine' , 
			'08' => 'DebitNoteLine',
		][$this->venta->TidCodi];
	}


	public function getProducto( $nombreProducto )
	{
		return Producto::getProductoByNombre($nombreProducto, $this->empresa_id, $this->lista_id );
	}

	public function createDetalles()
	{
			$vtaOper = $this->venta->VtaOper;

			$producto_nombre  = "POR EL SERVICIO DE ENVIÓ DE DÓLARES ";
			$cantidad  = 1;
			$precio  = $this->totalBoleta;
			$total = $this->totalBoleta;
			$igv_total = 0;
			$tipo_igv = TipoIgv::DEFAULT_EXONERADA;

			$producto = $this->producto;
			$unidad = $this->unidad;
			$unicodi =  $unidad->Unicodi;
			$tieCodi = $producto->tieCodi;
			$descuento_porc = 0;
			$descuento_total = 0;

			$it = new VentaItem();
			$it->Linea = VentaItem::nextLinea();
			$it->DetItem = 1;
			$it->VtaOper = $vtaOper;
			$it->EmpCodi = $this->empresa_id;
			$it->DetUnid = $unidad->UniAbre;
			$it->UniCodi = $unicodi;
			$it->DetCodi = $producto->ProCodi;
			$it->DetNomb = $producto_nombre;      
			$it->MarNomb = '';
			$it->DetCant = $cantidad;
			$it->DetPrec = $precio;
			$it->DetPeso = 0;
			$it->DetEsta = "V";
			$it->DetEspe = 0;
			$totales = [];
			$totales['precio_unitario'] = "0.00";
			$totales['valor_unitario'] = "0.00";
			$totales['valor_noonorosa'] = "0.00";
			$totales['valor_venta_bruto'] = "0.00";
			$totales['valor_venta_por_item'] = "0.00";
			$totales['valor_venta_por_item_igv'] = "0.00";
			$totales['impuestos_totales'] = "0.00";
			$it->lote = $totales;
			$it->DetCSol = 0;
			$it->DetCDol = 0;
			$it->GuiOper = null;      
			$it->DetSdCa = $cantidad;
			$it->DetDcto = $descuento_porc;
			$it->DetDctoV = $descuento_total;
			$it->Detfact = 1;

			$it->DetIGVV = 18;
			$it->DetIGVP = $igv_total;
			$it->DetISC = 0;
			$it->DetISCP = 0;
			$it->icbper_value = 0;
			$it->icbper_unit = 0;
			$it->DetImpo = $total;
			$it->DetDeta = '';
			$it->Estado  = $tieCodi;
			$it->DetBase =  "EXONERADA";
			$tipo_igv = $tipo_igv;
			$it->incluye_igv = 0;
			$it->DetPercP = 0;
			$it->TipoIGV = $tipo_igv;
			$it->save();
	}


	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$boletas = $this->getNodo('//sac:SummaryDocumentsLine');

		
		foreach( $boletas as $boleta ){
			$this->currentBoleta = $boleta;
			$this->createVentaCab();
			$this->createDetalles();
		}
		return true;

	}

}