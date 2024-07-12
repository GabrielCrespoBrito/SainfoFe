<?php

use App\User;
use App\Moneda;
use App\Empresa;
use Carbon\Carbon;
use App\ErrorSunat;
use App\GuiaSalida;
use App\Jobs\TestCode;
use App\ClienteProveedor;
use App\Helpers\XmlHelper;
use App\TipoDocumentoPago;
use Chumper\Zipper\Zipper;
use App\Helpers\MathHelper;
use App\Helpers\CacheHelper;
use App\Helpers\ConsultarDocumento;
use App\Helpers\NotificacionHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use mikehaertl\wkhtmlto\Pdf as WKPDF;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Util\NumeroALetras\NumeroALetras;
use App\Http\Controllers\Util\Xml\dos_cero\XmlCreator;
use App\Http\Controllers\Util\Xml\dos_cero\XmlCreatorNC;
use App\Http\Controllers\Util\Xml\dos_cero\XmlCreatorND;
use App\TipoDocumento;
use App\Ubigeo;

function math()
{
  return new MathHelper();
}


function nombreDocumentoCliente($tipo)
{
  return TipoDocumento::getNombreReporte($tipo);
}


function auditValues()
{
  return (object) [
    'user' => optional(auth()->user())->usulogi,
    'fecha' => date('Y-m-d H:i:s'),
    'equipo' => gethostname()
  ];
}

function xmlHelper()
{
  return new XmlHelper();
}

function convertCaracter($u)
{
  return iconv('utf-8', 'ascii//TRANSLIT', $u);
}

function settings_api()
{
  return (object)  [
    "scope" => 'https://api-cpe.sunat.gob.pe/',
    "url_token" => "https://api-seguridad.sunat.gob.pe/v1/clientessol/"
    // https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/20604067899-09-T001-1
  ];
}

function getTrueIsEmpresaNCHabit()
{
  $empcodi = empcodi();

  if($empcodi == "001"){
    return false;
  }

  return true;
}


  function remove_empty_arr($array)
  {
    return array_filter($array, '_remove_empty_internal');
  }

  function _remove_empty_internal($value)
  {
    return !empty($value) || $value === 0;
  }


function get_class_name($classname)
{
  if ($pos = strrpos($classname, '\\')) return substr($classname, $pos + 1);
  return $pos;
}

function removeWhiteSpace($text)
{
  $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
  $text = preg_replace('/([\s])\1+/', ' ', $text);
  $text = trim($text);
  return $text;
}

function return_strs_if($condition, ...$strs )
{
  return $condition ? implode($strs) : '';
}


function removeHttp($text)
{
  return str_replace('https://', ' ', str_replace('http://', ' ', $text));
}

function bool_str($var)
{
  return (bool) $var ? 'Si' : 'No';
}


function transformMesCodi($date)
{
  if (is_numeric($date)) {
    $year = substr($date, 0, 4);
    $mes = substr($date, -2);
    $year_mes = $year . '-' . $mes;    
    $fecha_inicio = $year_mes . '-' . "01";
    $carbon = new Carbon($fecha_inicio);
    $fecha_final =  $carbon->lastOfMonth()->format('Y-m-d');
    $fecha_inicio = $year_mes . '-' . "01";
    return [$fecha_inicio, $fecha_final];
  }

  return "000000";
}

function is_ose()
{
  return (bool) (int) get_setting('ose', 0);
}

function is_production()
{
  return config('app.env') === "production"; 
}


function isHtmlStringInstance($var)
{
  return $var instanceof Illuminate\Support\HtmlString;
}

function fechas_reporte()
{
  $carbon = new Carbon();
  $inicio = $carbon->firstOfMonth()->format('Y-m-d');
  $final = date('Y-m-d');

  return (object) [
    'inicio' => $inicio,
    'final' => $final,
  ];
}


function get_real_quantity($ente, $medi, $quantity)
{
  return ($ente / $medi) * $quantity;
}

function get_real_price($ente, $medi, $price)
{
  return (($ente / $medi) * $price);
}


function _dd_if( $show = true, ...$args )
{
  if( $show ){
    _dd( $args ); 
    exit();
  }
}

function dd_if($show = true, ...$args)
{
  if ($show) {
    dd($args);
    exit();
  }
}

function generateReport($view, $options = [])
{
  $defaultGlobalOptions = ['no-outline', 'page-size' => 'Letter'];
  // 
  $pdf = new WKPDF([
    'commandOptions' => [
      'useExec' => true,
      'escapeArgs' => false,
      'locale' => 'es_ES.UTF-8',
      'procOptions' => [
        'bypass_shell' => true,
        'suppress_errors' => true,
      ],
    ],
  ]);

  $pdf->binary = getBinaryPdf();

  $pdf->setOptions($defaultGlobalOptions);
  $pdf->addPage($view);
  // 
  if (!$pdf->send()) {
    throw new \Exception('Could not create PDF: ' . $pdf->getError());
  }
}



function get_error_sunat($error_code)
{
  return ErrorSunat::getErrorDescripcion($error_code);
}

function getXmlProduccion()
{
  return public_path(file_build_path('static', 'webservice', 'production.xml'));
}

function getXmlBeta()
{
  $path =  'static' . getSeparator() . 'webservice' . getSeparator() . 'beta.xml';
  return public_path($path);
}

function descuentoFactor($descuento)
{
  $descuento_str = (string) $descuento;
  $descuentoFactor = strpos($descuento_str, ".") !== false ? str_replace(".", "", $descuento_str) : $descuento_str;
  return $descuento < 10 ? "0.0" . $descuentoFactor : "0." . $descuentoFactor;
}

function getBinaryPdf()
{
  return get_setting('binary_path', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe');
}

function returnValueUnidad($campo, $data, $index, $default_value)
{
  // ToWinTheCookie,YouHasToDoSomeThingBall you feel me?
  // ToWinTheCookie,YouHasToDoSomeThingBall you feel me?
  $campo = $campo . $index;
  if (isset($data[$campo])) {
    return is_null($data[$campo]) ? $default_value : $data[$campo];
  }
  return $default_value;
}

if (!function_exists('FileHelper')) {
  function FileHelper($ruc = null, $codigo = '')
  {
    return new App\Helpers\FHelper($ruc, $codigo);
  }
}


function getLogoFooterSainfo()
{
  return file_build_path(config('app.aws_url_bucket'), 'images' , 'sainfo_banner_footer.jpg');
}

function return_str($v1, $v2, $str_if_true = "selected", $str_if_false = "")
{
  return $v1 == $v2 ? $str_if_true : $str_if_false;
}

function consultar_ruc($ruc)
{
  $search = new ConsultarDocumento($ruc);

  return $search->search();
}

function validateSol($ruc = null, $usuario_sol = null, $clave_sol = null)
{
  $ruc = "20480674414";
  $usuario_sol = "FONSECA";
  $clave_sol = "BLAS";
}


/**
 * Probar codigo por consola
 */
  if(!function_exists('test_code')){
    function test_code($code){
      return (new TestCode($code))->handle();
    }
  }



/**
 * Probar codigo por consola
 */
if (!function_exists('get_number_local')) {
  function get_number_local($loccodi)
  {
    return  (int) ($loccodi);
  }
}

function consultar_dni($dni)
{
  $search = new ConsultarDocumento($dni, false);
  return $search->search();
}

function typeDocument($number)
{
  $number = (string) $number;
  $lenstr = strlen($number);
  if ($lenstr == 11) {
    return 6;
  }

  return $lenstr == 8 ? 1 : ".";
}

function addCero($numero)
{
  return $numero < 10 ? "0{$numero}" : $numero; 
}

function nombreDocumento($codigo)
{
  return TipoDocumentoPago::getNombreDocumento($codigo);
}

function is_online()
{
  return get_setting('is_online');
}

function fixedId($model, $column = "column_name", $ceros = 3)
{
  App\DbHelp::fixedId($model, $column, $ceros);
}

function setInputState($readonly, $required  = true)
{
  if ($readonly) {
    return "disabled=disabled ";
  } else {
    return $required ? "required=required " : "";
  }
}

function setSelectedOption($firstValue = null, $optionSelected  = "00")
{
  return $firstValue == $optionSelected ? "selected=selected " : "";
}

function verificar_db()
{
  $db_verificador = new App\DBHelp();
  return $db_verificador->verificar_db();
}

function ruta_activa()
{
  dd("ra",Route::currentRouteName());
}

function get_setting($field_name, $respuesta_si_no_existe = false, $cache = true)
{
  if ( $cache ) {
    $settings = Cache::rememberForever('settings', function () {
      return App\SettingSystem::all();
    });
  } 
  else {
    return App\SettingSystem::all();
  }


  if ($setting = $settings->where('name', $field_name)->first()) {
    return $setting->value;
  }

  return $respuesta_si_no_existe;
}

function array_unshift_assoc(&$arr, $key, $val)
{
  $arr = array_reverse($arr, true);
  $arr[$key] = $val;
  $arr = array_reverse($arr, true);
  return $arr;
}

function attributes($attributes)
{
  $html = [];

  foreach ((array) $attributes as $key => $value) {
    $element = $this->attributeElement($key, $value);

    if (!is_null($element)) {
      $html[] = $element;
    }
  }

  return count($html) > 0 ? ' ' . implode(' ', $html) : '';
}

/**
 * Datos para la conexion con la base de datos de la tienda virtual
 *
 * @return string
 */
function conexionTienda($field = false, $current = false)
{
  $empresa = $current ? get_empresa() : Empresa::first();

  $data = $empresa->getDataConexionTienda();

  // Devolver un campo especifico
  if ($field) {
    return $data->{$field};
  }

  // Devolver toda la informacion
  return $data;
}


function update_setting($field, $value)
{
  $setting = App\SettingSystem::where('name', $field)
    ->firstOrfail();

  $setting->value = $value;
  $setting->save();
}


function hoy($formato = 'Y-m-d')
{
  return date($formato);
}

function ayer()
{
  return date('Y-m-d', strtotime('-1 days'));
}

function implode_prueba1()
{
  $arr =  config('app.path_archivos.xml_data');
  $res = implode( getSeparator() , $arr );
  return [$arr, $res];
}

function implode_prueba2()
{
  $arr =  config('app.path_archivos.xml_data');
  $res = implode($arr, getSeparator());
  return [ $arr ,$res];
}


function isWindow()
{
  return windows_os();
}

function asset_force_https( $url,$secure = false, $mix = false )
{  
  $asset = $mix ? asset(mix($url),$secure) : asset($url,$secure);

  if ( $secure ) {
    $httpsExists = strpos( $asset , 'https' ) !== false;
    return $httpsExists ? $asset :  str_replace('http' , 'https', $asset);
  }
  return $asset;
}

function getSeparator()
{
  return isWindow() ? '\\' : '/';
}

function getUrlValid()
{
  $request = request();
  $protocol = $request->secure() ? 'https://' : 'http://';
  $base = env('APP_URL_BASE');
  $port = $request->getPort() ? ':' . $request->getPort() : '';
  $url = $request->getPathInfo();
  return $protocol . $base . $port . $url;
}


function file_build_path(...$segments)
{
  return join(DIRECTORY_SEPARATOR, $segments);
}

function formatoText( $texto )
{
  return str_replace('[nl]', '<br>', $texto );
}

function removeformatoText($texto)
{
  return str_replace('<br>', '', $texto);
}

function deleteFiles(...$path_files)
{
  $path_files = is_array($path_files) ? $path_files : func_get_args();

  foreach ($path_files as $file) {
    unlink($file);
  }
}

function getTempPath($name_file = null, $content = false)
{
  $path =  public_path('temp' .  getSeparator()   .  $name_file);
  if ($content) {
    \File::put($path, $content);
  }

  dar_permisos($path);

  return $path;
}


function get_option($opcion, $value = false, $empcodi = null)
{
  $nameCache = "option_empresa" . ( $empcodi ?? empcodi());
  $empresa = get_empresa();
  if ($value !== false) {
    return $empresa->opcion->updateOpcion($opcion,  $value);
    \Cache::forge($nameCache);
  }

  $opciones = \Cache::rememberForever($nameCache, function () use ($empresa) {
    return $empresa->opcion;
  });
 
  return $opciones->{$opcion};
}



function get_value_in_array($arr, $field, $defualtValue = '')
{
  return array_key_exists($field, $arr) ? $arr[$field]  : $defualtValue;
}

function getFilenameFromZip($content, $ext = '.xml')
{
  // $folderExtract = str_random(5);    
  $file_name = str_random(5) . '.zip';
  $path = getTempPath($file_name, $content);

  $zipper = new Zipper;
  $zipper->make($path);
  $files = $zipper->listFiles();

  foreach ($files as $file) {
    if (strpos($file, ".xml") !== false) {
      return $file;
    }
  }

  return;
}

function extraer_values_string($prop, $content)
{
  return App\Http\Controllers\Util\Xml\XmlHelperNew::getValue($prop, $content);
}

function extraer_from_content($content, $name, $values)
{
  return App\Http\Controllers\Util\Xml\XmlHelperNew::extract_from_content($content, $name, $values);
}

function extraer_value_zip($a, $b, $c, $d = false)
{
  return App\XmlHelper::extract_value($a, $b, $c, $d);
}

function dd_in($elemento_to_show, $indice, $indice_cuando_mostrar)
{
  if ($indice == $indice_cuando_mostrar) {
    dd($indice, $indice_cuando_mostrar, $elemento_to_show);
  }
}

function p_name( ...$strs )
{ 
  return PermissionSeeder::getName($strs);
}

/**
 * Funcion para devolver los nombres correctos de los permisos 
 * 
 */
function p_midd( ...$strs )
{
  return PermissionSeeder::getNameForMiddleware( $strs );
}

function concat_space( ...$args )
{
  return implode(' ' , $args);
}

function empresa_bd_tenant($empresa_id)
{ 
  (new ActiveEmpresaTenant(Empresa::find($empresa_id)))->handle();
}

function xmlCreador($documento)
{

  if ($documento instanceof GuiaSalida) {
    return new App\Http\Controllers\Util\Xml\dos_uno\GuiaRemision_2_1($documento);
  }

  return new App\Http\Controllers\Util\Xml\dos_uno\Factura_2_1($documento);

  $is_2_1 = $documento->empresa->isXml_2_1();

  // Xml 2_1
  if ($is_2_1) {
    return new App\Http\Controllers\Util\Xml\dos_uno\Factura_2_1($documento);
  }

  // Xml 2_0    
  else {

    if ($documento->isFactura()) {
      return new XmlCreator($documento);
    } else if ($documento->isNotaCredito()) {
      return new XmlCreatorNC($documento);
    } else if ($documento->isNotaDebito()) {
      return new XmlCreatorND($documento);
    }
  }
}

function is_valid_email($str)
{
  return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
}

function hay_internet($sCheckHost = 'www.google.com')
{
  if (get_setting('is_online')) {
    return true;
  }
  return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
}

function return_false($msj)
{
  return false;
}

function set_timezone($timezone = 'America/Lima')
{
  date_default_timezone_set($timezone);
}


function convertBooleanNumber($number)
{
  return (bool) ((float) $number );
}

/**
 * Usar la funcion date() pero con la zona horario de LIMA
 */

if (!function_exists('datePeru')) {

  function datePeru($format, $timeZone = 'America/Lima')
  {
    set_timezone($timeZone);

    return date($format);
  }
}



function active_route($name_route, $className = "active")
{
}

function  get_meses()
{
  return [
    "",
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre"
  ];
}


/**
 * Reemplazar caracteres especiales
 * 
 * @return string
 */

if (!function_exists('str_convert_caracters_especials')) {
  function str_convert_caracters_especials($str)
  {
    return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($str));
  }
}

function dar_permisos()
{
  // foreach ( func_get_args() as $file ) {
  // chmod( $file , 0777 );
  // }
}

function get_caja()
{
}


function get_cliente()
{

  if (session()->get('PCCodi')) {

    $empcodi = is_online() ?
      Empresa::find(session()->get('EmpCodi_Cliente'))
      ->empcodi : '001';

    return ClienteProveedor::where('PCCodi', session()->get('PCCodi'))
      ->where('TipCodi', 'C')
      ->where('EmpCodi', $empcodi)
      ->first();
  }
  return false;
}

function get_empresa($field = false, $fake = false)
{
  $id =  $fake || !session()->get('empresa') ? "001" : session()->get('empresa');
  $cache_key = 'empresa' . $id;
  $empresa = Cache::rememberForever( $cache_key, function () use ($id) {
    return App\Empresa::findByCodi($id);
  });

  if ($field) {
    return $field == "id" ? $empresa->empcodi : $empresa->{$field};
  }    
  else {
    return  $empresa;
  }
}

function usucodi()
{
  return auth()->user()->usucodi;
}

function empcodi($extrinct = false)
{
  return session()->get('empresa') ?? "001";
}

function periodo()
{
  return session()->get('periodo');
}

function get_ruc()
{
  return get_empresa('EmpLin1');
}


function get_codigo()
{
  return get_empresa('codigo');
}


/**
 * Enviar información por session a la vista para ser interpretada con libreria javascript para enviar notificaciones al usuario
 * 
 * @returne NotificacionHelper
 */
function noti( array $options = [] )
{
  return new NotificacionHelper($options);
}


// 50 56 19 84 28
function notificacion($titulo = '', $mensaje, $tipo = "success", array $options = [])
{

  $showHideTransition = $options['showHideTransition'] ?? 'showHideTransition';
  $hideAfter = $options['hideAfter'] ?? 2000;

  session()->flash('notificacion', true);
  session()->flash('titulo', $titulo);
  session()->flash('mensaje', $mensaje);
  session()->flash('N_showHideTransition', $showHideTransition);
  session()->flash('N_hideAfter', $hideAfter);

  session()->flash('tipo', $tipo);
}

function get_mes()
{
  return App\Mes::all();
}


function fixedValueCustom($value, $decimal = 2)
{
  return $value == "" ? "0" : ($value === 0 ? "0" : fixedValue($value, $decimal));
}


function fixedValue($value, $decimal = 2)
{
  return number_format((float)$value, $decimal, '.', '');
}

function newformat_date($time, $format = "Y/m/d")
{
  return date($format, strtotime($time));
}

function numero_a_letras( $numero, $decimales, $moneda )
{
  $moneda_str = $moneda == Moneda::SOL_ID ? 'soles' : 'dolares'; 
  $formatter = new NumeroALetras();
  return $formatter->toInvoice( $numero, $decimales, $moneda_str );
}


function agregar_ceros($numero, $cantidad_ceros = false, $sumar = 1)
{
  $cant_ceros = $cantidad_ceros ? $cantidad_ceros : strlen((string) $numero);
  $ceros = "00000000000";
  $value = ((int) $numero + $sumar);
  $len_value = strlen((string) $value);

  if ($len_value != $cant_ceros) {
    $ceros_agregar = $len_value - $cant_ceros;
    return substr($ceros, $ceros_agregar) . $value;
  }

  return $value;
}

function decimal($valor, $longitud = 2, $positive = false)
{
  $value = fixedvalue($valor, $longitud);
  return $positive ? abs($value) : $value;
}

function deci($valor)
{
  return number_format((float)$valor, 2, '.', '');
}

function add_libreria($librerias, $tipo = "js")
{
  $enlaces = [
    'js' => "<script src='[direccion]'></script>",
    'css' => "<link rel='stylesheet' href='[direccion]'/>",
  ];

  $secure = config('app.env') == 'production';

  $librerias_direcciones = [
    'js' => [
      'datatable' => [
        asset_force_https('plugins/datatable/jquery.dataTables.min.js', $secure), 
        asset_force_https('plugins/datatable/dataTables.bootstrap.js', $secure )
      ],
      'select2' => [
        asset_force_https('plugins/select2/select2.js', $secure), 
      ],
      'popover' => [
        asset_force_https('plugins/popover/script.js', $secure),
      ],
    ],

    'css' => [
      'datatable' => [
        asset_force_https('plugins/datatable/dataTables.bootstrap.css', $secure )
      ],
      'select2' => [
        asset_force_https('plugins/select2/select2.css', $secure)
      ],
      'popover' => [
        asset_force_https('plugins/popover/style.css', $secure),
      ],
    ]
  ];

  $direcciones = "";
  foreach ((array) $librerias as $libreria) {
    $links_direcciones = $librerias_direcciones[$tipo][$libreria];
    foreach ($links_direcciones as $link_direccion) {
      $link_current =  str_replace("[direccion]", $link_direccion, $enlaces[$tipo]);
      $direcciones .= "\n" . $link_current;
    }
  }
  return $direcciones;
}


function get_assets($libreria)
{
  // dd( "helper" );
  $helper  = new App\Helpers\ViewHelper();
  return $helper->getPathLibrerias($libreria);
}

function get_asset_js($libreria)
{
  $helper  = new App\Helpers\ViewHelper();
  return $helper->getPathJs($libreria);
}

function getEmpresaDecimals()
{
  return (object) [
    'soles' => get_option('DecSole'),
    'dolares' => get_option('DecDola'),
  ];
}



function _dd(...$args)
{
  $content = "<!DOCTYPE html><html><body>";

  foreach ($args as $arg) {
    $val = (new \Symfony\Component\VarDumper\Cloner\VarCloner)->cloneVar($arg);
    $dumper = new \Symfony\Component\VarDumper\Dumper\HtmlDumper;
    $content .= $dumper->dump($val, true);
  }

  $content .= "</body></html>";

  return response($content, 500)->header('Content-Type', 'text/html')->send();
}


function putUndercore($v)
{
  return $v ? $v : "$v";
}
function isSalida($v)
{
  return $v == "S";
}
function calculate($tipo, $v)
{
  if (isSalida($tipo)) {
  }
}

function ubigeoNombre($ubicodi = null)
{
  if($ubicodi == null || $ubicodi == "" || $ubicodi == "-" ){
    return "";
  }

  return Ubigeo::PLAIN_UBIGEOS[$ubicodi];
}

function convertNegative($v)
{
  return $v * (-1);
}

function convertNegativeStr($v)
{
  return '-' . (string) $v;
}

function convertNegativeIfTrue( $value, $isTrue )
{
  return $isTrue ? convertNegative($value) : $value;
}

function dividir($v1, $v2)
{
  return $v2 == 0 ? 0  : fixedValue($v1 / $v2);
}

function streamPdf($view, $data, $name = "document.pdf")
{
  $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => 'true']);
  $dompdf->loadHtml(view($view, $data));
  $dompdf->render();
  return $dompdf->stream($name, array("Attachment" => false));
}


function fixedDate($fecha)
{
  $meses = get_meses();
  $fecha_split = explode("-", $fecha);
  $year = $fecha_split[0];
  $month = $fecha_split[1];
  $day = $fecha_split[2];
  $month_letra = $meses[(int) $month];
  return $day . " de " . $month_letra . " del " . $year;
}


function str_concat($separator, ...$strings)
{
  return implode($separator, $strings);
}

/**
 * Mostrar un string si es true la condicion
 *
 * @return string
 */
if (!function_exists('show_if_true')) {

  function show_if_true( $condition , $str_if_true = '' , $str_if_false = '' )
  {
    return $condition ? $str_if_true : $str_if_false;
  }
}

/**
 * Mostrar un string si es true la condicion
 *
 * @return string
 */
if (!function_exists('isJson')) {

  function isJson($string)
  {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;    
  }
}


/**
 * Function para buscar información del cache
 *
 * @return App\Helpers\CacheHelper
 */
if (!function_exists('cacheHelper')) {

  function cacheHelper($name = false)
  {
    $cacheHelper = new CacheHelper();

    return $name ? $cacheHelper->get($name) : $cacheHelper;
  }
}

if (!function_exists('user_')) {
  function user_()
  {
    return auth()->user() ? auth()->user() : User::first();
  }
}


if (!function_exists('removeComillaToString')) {
  function removeComillaToString($value)
  {

    if( $value == '' || $value == null || is_int($value) || is_float($value)  ){
      return $value;
    }

    $value = trim($value);

    $comilla = "'";
    
    
    if ($value[0] == $comilla) {
      $value = substr($value, 1);
    }
    
    $str_length = strlen($value);

    Log::info( sprintf("%s" , $value) );

    if( $str_length -1 == -1 ){
      return $value;
    }

    if ($value[$str_length - 1] == $comilla) {
      $value = substr($value, 0, $str_length - 1);
    }

    return $value;
  }
}



if (!function_exists('transactions')) {
  function xasdasdas()
  {
    return auth()->user() ? auth()->user() : User::first();
  }
}

if (!function_exists('arrReorder')) {
  function arrReorder(array $arr)
  {
    $arr = ["A", "B", "C", "D", "E", "F"];
    $arrOriginal = $arr;
    $countArr = count($arr);
    $newArr = [];

    for ($i = 0; $i < $countArr; $i++) {

      $randomIndex = rand(0, count($arr) - 1);
      // obtener elemento aleatorio
      $ele = $arr[$randomIndex];
      // eliminar indice
      unset($arr[$randomIndex]);
      // reindexar
      $arr = array_values($arr);

      array_push($newArr, $ele);
    }

    return $newArr;
  }

  $arr = [
    "jose",
    "luis",
    "william",
  ];
}


/**
 * Convert Anything To UTF-8
 * @param mixed $var The variable you want to convert.
 * @param boolean $deep Deep convertion? (*Default: TRUE).
 * @return mixed
 */
function anything_to_utf8($var, $deep = TRUE)
{
  if (is_array($var)) {
    foreach ($var as $key => $value) {
      if ($deep) {
        $var[$key] = anything_to_utf8($value, $deep);
      } elseif (!is_array($value) && !is_object($value) && !mb_detect_encoding($value, 'utf-8', true)) {
        $var[$key] = utf8_encode($var);
      }
    }
    return $var;
  } elseif (is_object($var)) {
    foreach ($var as $key => $value) {
      if ($deep) {
        $var->$key = anything_to_utf8($value, $deep);
      } elseif (!is_array($value) && !is_object($value) && !mb_detect_encoding($value, 'utf-8', true)) {
        $var->$key = utf8_encode($var);
      }
    }
    return $var;
  } else {
    return (!mb_detect_encoding($var, 'utf-8', true)) ? utf8_encode($var) : $var;
  }
}

function getMonedaAbreviaturaSunat($moneda_id)
{
  return Moneda::getAbrevSunat($moneda_id);
}

function isMobile()
{
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}


/**
 * Transformar un codigo de mes a las fechas de inicio y final de ese mes
 *
 * @param string|int $mescodi
 * @example $mescodi 202001 = [2020-01-01,2020-01-30] 
 * @return array
 */
function mes_to_fecha_inicio_final($mescodi)
{
  $year = substr($mescodi, 0, 4);
  $mes = substr($mescodi, 4, 6);
  $fecha_inicio =  "{$year}-{$mes}-01";
  $carbon = new Carbon($fecha_inicio);
  $fecha_final =  $carbon->lastOfMonth()->format('Y-m-d');
  return [$fecha_inicio, $fecha_final];
}


if (!function_exists('substr_custom')) {

  function substr_custom($str, $length, $str_end = '')
  {
    return (strlen($str) > $length) ? (substr($str, 0, $length) . $str_end) : $str;
  }
}

function array_flat($arr)
{
  $arr_flat = [];
  $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));
  foreach ($it as $v) {
    $arr_flat[] = $v;
  }

  return $arr_flat;
}

function getNombreCorreo($email)
{
  if (strpos($email, 'gmail') !== false) {
    return "Gmail:";
  }

  if (strpos($email, 'hotmail') !== false) {
    return "Hotmail:";
  }

  if (strpos($email, 'outlook') !== false) {
    return "Outlook:";
  }

  return "Email:";
}

if (! function_exists('times_to_execute')) {
  function times_to_execute()
  {
    $start = microtime(true);
    for ( $i = 0; $i < 10000; $i++ ) { 
    }
    $end = microtime(true);
    $time_elapsed_secs = $end - $start;
    return [$start, $end, $time_elapsed_secs];
  }
}

if (! function_exists('is_positive')) {

  function is_positive($val)
  {
    return (float) $val > 0;
  }
}

if (! function_exists('get_igv')) {

  function get_igv()
  {
    return get_option('Logigv');
  }
}


if (!function_exists('return_value')) {
  function return_value($name, $value = null)
  {
    return $value;
  }
}


if (!function_exists('get_date_info')) 
{
  function get_date_info( $date )
  {
    list($year,$month, $day) = explode('-', $date);

    return (object) [
      'day'   => $day,
      'month' => $month,
      'year'  => $year,
      'mescodi'  => $year . $month,
      'full'  => $date,
    ];
  }
}



if (!function_exists('xmlToArray')) 
{
  function xmlToArray(SimpleXMLElement $xml): array
  {
      $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
          $nodes = $xml->children();
          $attributes = $xml->attributes();

          if (0 !== count($attributes)) {
              foreach ($attributes as $attrName => $attrValue) {
                  $collection['attributes'][$attrName] = strval($attrValue);
              }
          }

          if (0 === $nodes->count()) {
              $collection['value'] = strval($xml);
              return $collection;
          }

          foreach ($nodes as $nodeName => $nodeValue) {
              if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                  $collection[$nodeName] = $parser($nodeValue);
                  continue;
              }

              $collection[$nodeName][] = $parser($nodeValue);
          }

          return $collection;
      };

      return [
          $xml->getName() => $parser($xml)
      ];
  }
}
