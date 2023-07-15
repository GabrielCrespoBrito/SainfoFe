<?php

namespace App;

use App\XmlCreatorBaja;
use App\XmlCreatorResumen;
use App\Jobs\Resumen\ProcessCDR;
use App\Jobs\Resumen\SaveTicket;
use App\Jobs\setNumeCorrelative;
use App\Presenter\ResumenPresenter;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Util\Sunat\Request\ResolverWsld;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Resumen\Util\HandleResponseTicket;
use App\Http\Controllers\Traits\SunatHelper;
use App\Jobs\Resumen\ContentCDR;
use App\Jobs\Resumen\UpdateResumen;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Resumen\Util\HandleResponseSendSummary;
use App\Util\Sunat\Request\credentials\CredentialDatabase;
use App\Util\Sunat\Services\ServicesParams;
use App\Util\Sunat\Services\SunatSendSummary\SendSummaryResolver;
use App\Util\Sunat\Services\SunatConsultTicket\ConsultTicketResolver;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Http\Request;

class Resumen extends Model
{
  use 
  UsesTenantConnection,
  ModelEmpresaScope;

  protected $table = 'ventas_ra_cab';
  protected $primaryKey = 'NumOper'; 
  public $incrementing = false;   
  protected $keyType = 'string';   
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  const ID_INIT = "000001";
  const DOC_DIA_INIT = "001";
  const EMPRESA_CAMPO = "EmpCodi";
  const RESUMEN = "R";
  const ANULACION = "A";
  const ABRE_RESUMEN = "RC";
  const ABRE_ANULACION = "RA";
  
  // Estados resumen
  const PROCESANDO_STATE = "98";
  const PENDIENTE_STATE = "9";
  const ACEPTADO_STATE = "0";

  const POR_PROCESAR_STATE = "1";
  const PROCESADO_STATE = null;

  


  const PK = 'NumOper'; 
  public $fillable = ["DocDesc", 'DocFechaE','DocFechaD','DocNume', 'DocTicket', 'DetGrav', 'DetExon','DetInaf', 'DetIGV','DetTota', 'PanAno' , 'PanPeri', 'MesCodi', 'DocCEsta', 'UDelete'];

  public $present;

  public function __construct()
  {
    $this->present = new ResumenPresenter($this);
  }



  /**
   * Obtener la letra del tipo de resumen
   *
   * @return string RC|RA
   */
  public function getTipoResumen()
  {
    return $this->isResumen() ? self::ABRE_RESUMEN : self::ABRE_ANULACION;
  }

  /**
   * Obtener una fecha de documento unida
   *
   * @example 2019-10-11 = 20191011
   * @return string
   */
  public function getFechaUnida()
  {
    return str_replace('-' ,  '' , $this->DocFechaD );
  }

  /**
   * Obtener codigo local para ponerlo en el inicio del correlativo
   *
   * @return string
   */
  public function getLocalCode()
  {
    return $this->local->getResumenCode();
  }

  /**
   * Obtener el DocNume del documento sin el correlativo del documento
   *
   * @example RC-20191011-10 = Solo faltarian los 3 ultimos numeros de la numeración diaria
   * @return string
   */
  public function getDocNumeParcial()
  {
    return $this->getTipoResumen() . '-' . $this->getFechaUnida() . '-' . $this->getLocalCode();
  }

  /**
   * Si el resumen tiene el nuevo formato de correlativo para evitar problemas con la numeración
   *
   * @example RC-20191210-00001 = TRUE
   * @example RC-20191210-10 = FALSE
   * 
   * @return boolean
   */
  public function hasNewFormatCorrelative()
  {
    return  strlen($this->DocNume) == 17;
  }

  /**
   * Obtener correlativo del dia del resumen 
   * 
   * @example 001 = 1
   * @example 010 = 10
   * @return string|int
   */
  public function getCorrelativeDia($integer = false)
  {
    $val = substr( $this->DocNume , -3);

    return $integer ? (int) $val : $val;

  }



  public static function findMultiple($numoper, $docnume)
  {
    return self::where([
      ['NumOper', '=', $numoper],
      ['DocNume', '=', $docnume]
    ])->first();

  }

  public function local()
  {
    return $this->belongsTo( Local::class, 'LocCodi', 'LocCodi' );
  }

  public function items()
  {
  	return $this
    ->hasMany( ResumenDetalle::class , 'numoper' , 'NumOper' )
    ->where('EmpCodi' , $this->EmpCodi)
    ->where('docNume', $this->DocNume );
  }

  public function ventaAnulacion(){
    return $this->items->first()->boleta();
  }

  public function isAnulacion()
  {
    return $this->DocMotivo == self::ANULACION;
  }

  public function isResumenReal()
  {
    return $this->DocMotivo == self::RESUMEN;
  }

  public function empresa()
  {
    return $this->belongsTo( Empresa::class, 'EmpCodi' , 'empcodi' );
  }  

  public function isSend(){
    return $this->DocTicket;
  }

  public function isResumen(){
    return $this->TipoOper == "R";
  }
  /**
   * Si tipo de documento es un Resumen diario
   *
   * @return boolean
   */
  public function isRC(){
    return $this->TipoOper == "R";
  }


  /**
   * Si tipo de documento es un Resumen de baja
   *
   * @return boolean
   */
  public function isRA()
  {
    return $this->TipoOper == "A";
  }


  public function cdrPath()
  {
    return env('SAINFO_CDR') . $this->nameFile(true, ".zip" );
  }

  public function moneda()
  {
  	return $this->belongsTo( Moneda::class , 'MonCodi' , 'moncodi' );
  }

  public function rango()
  {
    $items = ResumenDetalle::where('numoper', $this->NumOper)
    ->where('EmpCodi',$this->EmpCodi)
    ->where('docNume',$this->DocNume)    
    ->get();

    $first = '';
    $last = '';
    if( $items->count() ){
      $first_boleta = $items->first()->toArray();
      $last_boleta = $items->reverse()->first()->toArray();
      $first =  ($first_boleta['detseri']) . '-' . ($first_boleta['detNume']);
      $last =  ($last_boleta['detseri']) . '-' . ($last_boleta['detNume']);
    }

    return [$first,$last];
  }

  public function cliente()
  {
    return $this->belongsTo( ClienteProveedor::class , 'PCCodi' , 'PCCodi' );//->where('TipCodi','C');
  }

	public static function agregate_cero( $numero = false  , $set = 0 )
	{
		$numero = $numero ? $numero->{self::PK} : self::ID_INIT;		
		$cero_agregar = [null,"00000","0000","000","00","0"];
		$codigoNum = ((int) $numero) + $set;
		$codigoLen = strlen((string) $codigoNum);

		return $codigoLen < 6 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
	}  

  public function ha_sido_enviado(){
    return $this->DocCEsta == 0;
  }

  public function no_sido_enviado()
  {
    return !$this->ha_sido_enviado();
  }

  public function nameFile( $respuesta = false , $ext = ""  )
  {   
    $name = $this->empresa->EmpLin1 . '-' . $this->DocNume; 
    return $respuesta ? ('R-' . $name . $ext ) : $name . $ext;
  }

  public function items_(){

    return ResumenDetalle::where('numoper', $this->NumOper)
    ->where('EmpCodi',$this->EmpCodi)
    ->where('docNume',$this->DocNume)        
    ->get();
  }

  public function successEnvio($ticket)
  {
    set_timezone();    
    $this->DocTicket = $ticket;
    $this->DocFechaEv = date('Y-m-d H:i:s');
    $this->DocEstado = "Enviado Resumen SUNAT";
    $this->UDelete = Resumen::PROCESADO_STATE;
    $this->save();

    $items = $this->items_();    
    foreach( $items as $item ){
      $item->documento()->update(['fe_obse' => $this->DocTicket ]);
    }
  }

  public function updateDataItems()
  {
    $items = $this->items_();

    foreach ($items as $item) {
        $doc = $item->documento();
        $item->DetGrav = $doc->Vtabase;
        $item->DetExon = $doc->VtaExon;
        $item->DetInaf = $doc->VtaInaf;
        $item->DetIGV = $doc->VtaIGVV;
        $item->DetTota = $doc->VtaTota;
        $item->save();
    }
    return true;
  }

  public function successEnvioAnulacion($ticket)
  {
    set_timezone();    
    $this->DocTicket = $ticket;
    $this->DocFechaEv = date('Y-m-d H:i:s');
    $this->DocEstado = "ANULADO(0)";
    $this->UDelete = Resumen::PROCESADO_STATE;
    $this->save();

    foreach( $this->items as $item ){
      $item->documento()->anular();
    }
  }

 public function grabarError($sent)
  {
    $descripcion = substr( $sent["message"] ,  0 , 140 ) ;
    $docesta = is_numeric($sent['code']) ? $sent['code'] : '9';

    $this->DocCEsta = $docesta;
    $this->DocCDR = 99;
    $this->DocDesc = $descripcion;
    $this->DocEstado = "ERROR AL ENVIO SUNAT";
    $this->UDelete = Resumen::POR_PROCESAR_STATE;
    $this->save();
  }

  public function saveError($code, $descripcion, $errorCodigo = null)
  {
    $descripcion =  substr( str_replace(["  ", "\n" , "\t", '"' ], "", $descripcion) , 0 , 150 );
    $errorCodigo = $errorCodigo ?? $errorCodigo;
    $this->DocCEsta = $code;
    $this->DocCDR = 0;
    // $this->DocDesc = $descripcion;
    $this->DocEstado = "ERROR {$errorCodigo} AL ENVIO SUNAT";
    $this->UDelete = Resumen::POR_PROCESAR_STATE;
    $this->save();
  }

  public function successValidacion($data = null )
  {
    $cdr = FileHelper()->getCdr( $this->nameFile(true,".zip"));
    $path = getTempPath( $this->nameFile(true,".zip") , $cdr );

    $info = XmlHelper::extract_value(["cbc:Description","cbc:ResponseCode"] , $path ,  $this->nameFile(true,".xml")  , true );
    $descripcion = substr( $info[0] , 0 , 140 );

    $this->saveSuccessValidacion( $info[1] , $descripcion );
  }


  /**
   * Dependiendo si es un resumen de anulaciòn o regular validarlo
   */
  public function saveSuccessValidacionByEstado($docEsta, $docDesc, $withCdr = true)
  {
    $this->isAnulacion() ?
    $this->saveSuccessAnulacion($docEsta, $docDesc, $withCdr) :
    $this->saveSuccessValidacion($docEsta, $docDesc, null , $withCdr);
  }

  /**
   * Dependiendo del estado del documento a enviar/anular, si tiene el estado correcto, validar el resumen de lo contrario dejarlo como esta
   * 
   * */  
  public function saveSuccessValidacionByEstadoDocumento()
  {
    $documento = $this->getDocumento();

    // Si el estado corresponde al estado del resumen, no hay nada que hacer.
    if(($this->isAnulacion() && $documento->isAnulada())  || ($this->isResumen() && $documento->isAceptado())) {
      $this->saveSuccessValidacionTicket(0, '', false);
      return true;
    }
    //

    $this->saveSuccessValidacionByEstado(0, '');
  }

  public function saveSuccessValidacion( $docEsta, $docDesc = null , $hash = null, $withCdr = true )
  {
    $this->saveSuccessValidacionTicket($docEsta, $hash, $withCdr);
    $this->setEnviadoItems();   
  }

  public function updateRango()
  {
    $rango = $this->rango();
    $this->DocDesc = 'Documentos: ' . $rango[0] . '-' . $rango[1];
    $this->save();
  }

  public function saveSuccessValidacionTicket($docEsta, $hash = "", $withCdr = true)
  {
    $rango = $this->rango();
    $this->DocXML = 1;
    $this->DocPDF = 1;
    $this->DocCDR = (int) $withCdr;
    $this->DocCEsta = $docEsta;
    $this->DocDesc = 'Documentos: ' . $rango[0] . '-' . $rango[1];
    $this->DocCHash = $hash;
    $this->DocEstado = "ACEPTADO SUNAT (" . $docEsta . ")";
    $this->UDelete = Resumen::PROCESADO_STATE;
    $this->save();
  }
  
  public function setEnviadoItems()
  {
    $items = $this->items_();

    foreach ($items as $item) {
      $documento = $item->documento();
      $documento->update([
        'fe_fenvio'  => $this->DocFechaE,
        'fe_estado' => "ENVIADO SUNAT(" . $this->DocCEsta . ")",
        'fe_obse' => $this->DocTicket,
        'fe_rpta' => $this->DocCEsta,
        "VtaXML" => 1,
        "VtaPDF" => 1,
        "VtaCDR" => 1,
      ]);
      $documento->updateStatusByResumen($this->DocCEsta , $this->isAnulacion());
    }   
  }

  public function setTotalesToItems()
  {
    $items = $this->items_();

    foreach ($items as $item) {
      $documento = $item->documento();
      $documento->setTotalesCantidad();
    }   
  }

  public function successAnulacion($info)
  {
    $this->saveSuccessAnulacion( $info[0] , substr($info[1], 0, 140) );
  }

  public function saveSuccessAnulacion($DoCEsta, $DocDesc, $withCdr = true)
  {
    $this->saveSuccessValidacionTicket($DoCEsta, null , $withCdr);
    $this->setAnuladoStateItems($DoCEsta, $DocDesc);
  }

  /**
   * Poner estado anulacion documento asociados
   * 
   *  @return bool
   */
  public function setAnuladoStateItems()
  {
    $items = $this->getItems();  

    foreach ( $items as $item ) {
      $documento = $item->documento();
      $documento->updateStatusCode( StatusCode::CODE_EXITO_0003 );
      $documento->processStatus();
    }
  }

  public function errorValidacion($data)
  {
    if( $data['status']  == 0 ){
      return;
    }
    
    if( ! isset( $data['code']) ){
      Log::error( $data['code'] );
    }

    $this->DocCEsta  = $data['code'];
    // $this->DocDesc = $data['code'];      
    $this->DocEstado = $data['code'];
    $this->UDelete = Resumen::POR_PROCESAR_STATE; 
    $this->save();
  }

  public static function UltimoCorrelativo()
  {
    set_timezone();
  	$fecha = "RC-" . date('Ymd-');
  	$ultimo_correlativo = self::OrderByDesc('DocNume')->where("DocNume" , 'LIKE' ,  $fecha . '%' )->first();

  	if( $ultimo_correlativo ){
  		$codigo = $ultimo_correlativo->docNume;
  		$ultimoSecuencia = explode( "-" , $codigo )[2];
  		$codigoInt = ((int) $ultimoSecuencia) +1;
      $nuevoCodigo = $codigoInt;
  		return ($fecha . $nuevoCodigo);
  	}

  	return $fecha . '1';
  }

  public static function UltimoId()
  {
  	$ultima_resumen = self::OrderByDesc( self::PK )->first();  	
  	return self::agregate_cero( $ultima_resumen , 1 );
  }

  /**
   * Generar correlativo DocNume
   * @param string $fecha String yyyy-mm-dd
   * @param boolean $resumen 
   * 
   * @return void
   */

	public static function itemDia( string $fecha, bool $resumen = true )
	{
    $fecha = str_replace( '-' , '' , $fecha );
    
  	$docNume = ($resumen ? "RC-" : "RA-") . $fecha;
  	$resumenLast = self::OrderByDesc('DocNume')
    ->where("DocNume" , 'LIKE' ,  $docNume . '%' )
    ->where("EmpCodi" , empcodi() )
    ->first();

    // Item
    $itemDocNume = "01";

    if( $resumenLast ){
      $docNumeLast = explode( "-" , $resumenLast->DocNume);
      $itemDocNume = ((int) end($docNumeLast)) + 1;      
      $itemDocNume = math()->baseCero($itemDocNume);
  	}
    
  	return $docNume . '-' . $itemDocNume;
	}


  public function fillNume()
  {
    setNumeCorrelative::dispatchNow($this);
  }

  public static function createAnulacion( $data = null , $descripcion = "" )
  {
    $resumen = new self();
    $resumen->EmpCodi = get_empresa('empcodi');
    $resumen->TipoOper = "A";
    $resumen->NumOper = self::UltimoId();
    $resumen->DocFechaE = $data["fecha_generacion"];
    $resumen->DocFechaD = $data["fecha_documento"];
    $resumen->DocXML = 0;
    $resumen->DocPDF = 0;
    $resumen->DocDesc = $descripcion;
    $resumen->DocMotivo = "A";
    $resumen->DocCDR = 0;
    $resumen->DocCEsta = 9;
    $resumen->DocEstado = "PENDIENTE DE ENVIO";   
    $resumen->User_Crea = optional(auth()->user())->usulogi;
    $resumen->User_ECrea = gethostname();
    $resumen->LocCodi = optional(optional(user_())->LocalCurrent())->loccodi;
    $resumen->MonCodi = "01";
    $resumen->UDelete = Resumen::POR_PROCESAR_STATE ;   
    $resumen->save();
    return $resumen;
  }

  public function setDates()
  {
    $fechaDoc =  $this->DocFechaD;
    $fechaDocArr = explode('-', $fechaDoc);

    $this->fill([
      'PanAno' => $fechaDocArr[0],
      'PanPeri' => $fechaDocArr[1],
      'MesCodi' => $fechaDocArr[0] . $fechaDocArr[1]
    ]);

  }

	public static function createResumen( $data , $fecha , $baja = false , $empcodi = false , $serie = "", $descripcion = "" )
	{
    set_timezone();
    
    $empcodi = $empcodi ? $empcodi : session()->get('empresa');
    $user = is_null(auth()->user()) ? null : auth()->user()->usulogi;

    optional();
    $numSerie = substr($serie, -1);
    $correlative = self::itemDia($fecha, true, $numSerie);
		$resumen = new self;
		$resumen->EmpCodi   = $empcodi;
		$resumen->TipoOper  = "R";
		$resumen->NumOper   = self::UltimoId();
    $resumen->DocNume   = $correlative;
		$resumen->DocFechaE = $data["fecha_generacion"];
		$resumen->DocFechaD = $data["fecha_documento"];
    $resumen->DocMotivo = $baja ? "A" : "R";
    $resumen->DocXML    = 0;
    $resumen->DocPDF    = 0;
    $resumen->DocCDR    = 0;
    $resumen->DocDesc   = $descripcion;
		$resumen->DocCEsta  = 9;
		$resumen->DocEstado = "PENDIENTE DE ENVIO";		
		$resumen->User_Crea = $user;
		$resumen->User_ECrea= gethostname();
		$resumen->MonCodi   = "01";		
		$resumen->LocCodi = user_()->LocalCurrent()->loccodi;
    $resumen->UDelete = Resumen::POR_PROCESAR_STATE ;
		$resumen->save();
		return $resumen;
	}

  public function delete_all()
  {
    foreach( $this->items as $item ){
      $item->delete();
    }
    $this->delete();
  }

  public function updateNombre($numero)
  {
    foreach($this->items as $item ){
      $item->update(['docNume' => $numero ]);
    }
    
    $this->update(['DocNume' => $numero]);
  }

  public function updateTicket($ticket = null)
  {
    $this->DocTicket = $ticket;
    $this->DocFechaEv = date('Y-m-d h:m:s');
    $this->UDelete = Resumen::POR_PROCESAR_STATE;
    $this->save();
  }


  public function dataPdf()
  {
    if( $this->isAnulacion() ){
      $data =  $this->ventaAnulacion()->dataPdf();
      $data['resumen'] = $this;
      return $data;
    }

    else {
      $e = $this->empresa;
      $empresa =  $e->toArray();        
      $logo  =  $e->logoEncode();
      $logo2 = false;
      if($e->isA42() && $e->EmpLogo1 ){
        $logo2 = $this->empresa->logoEncode(2);      
      }

      $documentos = [];
      foreach( $this->items as $item ){
        $name = $item->detseri . '-' . $item->detNume;
        array_push( $documentos , $name );
      }

      $documentos_name =  collect($documentos)->implode(','); 

      $empresa['EmpLogo'] = null;
      $venta['empresa'] = null;
      $documento_id = $this->DocNume;
      $data = [       
        'title' => $this->nameFile('.pdf'),      
        'nombre_documento' => "RESUMEN DIARIO",
        'resumen'       => $this,
        'documentos_name' => $documentos_name,
        'empresa'     => $empresa, 
        'documento_id' => $documento_id,
        'logo'        => $logo,
        'logo2'        => $logo2,      
        'cliente'     => $this->cliente,
      ];
    }

    return $data;
  }

  public function getTextSunat()
  {
    $txts = "";
    $ruc = get_empresa()->ruc();
    $fecha = explode('-',$this->DocFechaD);
    $fecha = array_reverse($fecha);
    $fecha = implode('/' , $fecha);
    $firstLine = true;

    foreach($this->items as $item){

      // $endCaracter = $firstLine ? "\n" : '';
      $line = str_concat( '|' , 
      $ruc, 
      $item->tidcodi, 
      $item->detseri ,
      (int) $item->detNume,
      $fecha, 
      $item->DetTota) .
      "\n";

      $txts .= $line;
      $firstLine = false;

    }

    return trim($txts);
  }


  /**
   * Obtener cliente especifico para el servicio
   *
   * @param string $type = send|ticket
   * @return mixed
   */
  public function getCommunicator($type = 'send' )  
  {    
    if( $type != "send" && $type != "ticket" ){
      throw new \Exception("{$type} service dont exists", 1);     
    } 

    $empresa = $this->empresa;
    $data = $empresa->getSunatData();
    $credentialer = new CredentialDatabase($empresa);

    $resolver = $type === "send"  ? 
    new SendSummaryResolver( $data->proveedor, $credentialer , $data->ambiente ) : 
    new ConsultTicketResolver( $data->proveedor, $credentialer , $data->ambiente );

    return $resolver->getCommunicator();
  }
  

  public function getItems()
  {
    $items = $this->items;

    if( ! $items->count() ){
      $items = ResumenDetalle::where('EmpCodi', $this->EmpCodi )
      ->where('NumOper', $this->NumOper)
      ->where('DocNume', $this->DocNume)
      ->get();
    }

    return $items;
  }


  public function enviarSunat()
  {
    $data = $this->createXML();
    $sent = $this->getCommunicator();
    
    $r = $sent
    ->setParams(ServicesParams::getFormatSummary($data['fileName'], $data['contentFile']))
    ->communicate()
    ->getResponse();
    
    return $this->handleSendSunat($r);
  }

  public function enviarValidarTicket()
  {
    $response = $this->enviarSunat();

    if( $response->success == false ){
      return $response;
    }

    return $this->validateTicket();
  }


  public function isOkey()
  {
    // $this->DocCEsta =
  }

  public function handleSendSunat( $r )
  {
    $process = new HandleResponseSendSummary( $this , $r, ResolverWsld::NUBEFACT );
    
    $process->processResponse();

    return $process->getResponse();
  }

  public function handleSendTicket($r)
  {
    $process = new HandleResponseTicket($this, $r, get_empresa()->getProveedor() );
    $process->processResponse();
    return $process->getResponse();
  }  


  public function validateTicket()
  {
    $this->fresh();

    $ticket = $this->getOriginal('DocTicket');

    if( ! $ticket ){
      throw new \Exception("Este resumen no tiene ticket", 1);
    }

    $sent = $this->getCommunicator('ticket');

    $response = $sent
    ->setParams(ServicesParams::getFormatTicket($ticket))
    ->communicate()
    ->getResponse();

    return $this->handleSendTicket($response);
  }

  public function getNameFileZip()
  {
    return $this->nameFile(false,'.zip');
  }

  public function getNameFileCDRZip()
  {
    return $this->nameFile(true,'.zip');
  }

  public function getNameFileCDRXML()
  {
    return $this->nameFile(true,'.xml');
  }

  public function createXML()
  {
    $input = $this->isRC() ? new XmlCreatorResumen($this) : new XmlCreatorBaja($this);
    
    $data = $input->guardar();

    return [
      'path' => $data['path'],
      'contentFile' => $data['contentZip'],
      'fileName' => $this->getNameFileZip(),
    ];
  }

  public function saveTicket($ticket)
  {
    SaveTicket::dispatchNow( $this , $ticket );
  } 

  public function updateDesc($desc = '')
  {
    return $this->update([
      'DocDesc' => $desc,
    ]);
  }

  public function updateEstadoSunat( $code, $message = '' )
  {
    return $this->update([
      'DocDesc' => $message,
      'DocCEsta' => $code,
    ]);
  }

  /**
   * Procesar cdr del resumen
   * 
   * @return array
   */
  public function processCDR( $content = null )
  {
    $cdr = new ContentCDR( $content, $this->getNameFileCDRZip() , $this->getNameFileCDRXML() );
    return $cdr
      ->saveTemp()
      ->saveCdr()
      ->extraerContent(["cbc:Description", "cbc:ResponseCode", 'cbc:ID']);
  }

  /**
   * Si el documento esta completamente validado
   * 
   * @return boolean
   */
  public function hasValidado()
  {
    return $this->DocCEsta === "0";
  }

  /**
   * Si el resumen tiene ticket
   *
   * @return boolean
   */
  public function hasTicket()
  {
    return (bool) $this->DocTicket;
  }

  public function codeResumenExists()
  {
    $DocEsta = $this->DocCEsta; 
    return ($DocEsta == "2324" || $DocEsta == "2403" || $DocEsta == "4000");
  }

  /**
   * Undocumented function
   *
   * @return boolean
   */
  public function canDelete()
  {
    if( $this->hasTicket() == false ){
      return true;
    }

    if( $this->codeResumenExists() ){
      return false;
    }
  }


  public function setProcesandoState()
  {
    return $this->update([
      'DocCEsta' => self::PROCESANDO_STATE
    ]);
  }

  public function updateResumen( Request $request  )
  {
    UpdateResumen::dispatchNow( $this,  $request);
  }


  public function isValidTicket()
  {
    return $this->DocCEsta == "0"
    || $this->DocCEsta == "98"
    || $this->DocCEsta > "4000";

  }

  public function canEdit()
  {
    if( $this->hasTicket() ){
      return $this->isValidTicket() == false;
    }

    return true;
  }

  /**
   * Para cuando el resumen es de un solo documento, acceder a el
   * 
   * 
   */
  public function getDocumento()
  {
    return $this->items->first()->documento();
  }

  /**
   * Validar el resumen por su estado de anulación
   * 
   * @return mixed
   */
  public function validatePorEstado()
  {
    $documento = $this->getDocumento();

    $statusData = $documento->checkIfStatusOrConsult( StatusCode::CODE_0003 );

    if( $statusData->success ){
      $this->saveSuccessValidacionByEstado(0, '');
    }

    return  $statusData;
  }

}