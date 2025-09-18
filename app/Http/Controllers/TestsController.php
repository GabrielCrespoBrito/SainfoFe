<?php

namespace App\Http\Controllers;

use App\M;
use App\User;
use App\Sunat;
use App\Venta;
use App\Empresa;
use App\Resumen;
use App\Producto;
use Peru\Jne\Dni;
use Carbon\Carbon;
use App\GuiaSalida;
use App\PDFPlantilla;
use Peru\Jne\DniParser;
use App\ClienteProveedor;
use App\Jobs\Venta\XmlToRC;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
use Peru\Http\ContextClient;
use Peru\Sunat\UserValidator;
use App\Jobs\Test\ExecuteTask;
use App\Util\PDFGenerator\PDFDom;
use App\Helpers\ConsultarDocumento;
use App\Jobs\Venta\validateResumen;
use Illuminate\Support\Facades\Log;
use App\Jobs\Venta\CreateVentaFromXML;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Controllers\SunatController;
use App\Jobs\ImportFromXmls\ImportFromXml;
use App\Http\Controllers\Traits\SunatHelper;
use Peru\Sunat\{HtmlParser, Ruc, RucParser};
use App\Util\Sunat\Request\credentials\CredentialManual;

class TestsController extends Controller
{
  public function inventary($param1 = null, $param2 = null, $param3 = null, $param4 = null)
  {
    return Venta::getBoletasNoEnviadas(
      $param1,
      $request->id_resumen,
      $request->docnume,
      $$param12
    );

    $model = Venta::first();
  }



  public function sunatRequest()
  {
    $sunatConsult = new SunatConsultDocumentOficinalProduction(
      new CredentialManual('20600409876ARROYO19', 'Arroyo2019'),
      'getStatus'
    );

    // 201904541194315

    // $parameters = [
    //   'ruc' => '2060040987',
    //   'tipo_documento' => '03',
    //   'serie' => 'B001',
    //   'numero' => '984',
    // ];

    $parameters = [
      'ticket' => '2060040987',
    ];

    $sunatConsult->communicate($parameters);

    return $sunatConsult->getResponse();
  }

  // public function verificar_process(Request $request)
  // {
  //   // ------------------------------------------------------
  //   // $c = new CredentialManual("MAXISACO","Maxisaco2019");
  //   // $venta = Venta::find(18);
  //   // $communicator = $venta->getCommunicator(false);
  //   // ------------------------------------------------------

  //   // ------------------------------------------------------
  //   // $sent $communicator
  //   //   ->setParams($venta->getParamsConsult("20553997357"))
  //   //   ->communicate()
  //   //   ->getResponse();
  //   // ------------------------------------------------------


  //   $params = [
  //     'rucComprobante' => "20601039908", // $request->ruc_empresa,
  //     'tipoComprobante' => $request->tipo_documento,
  //     'serieComprobante' => strtoupper($request->serie_documento),
  //     'numeroComprobante' =>  $request->numero,
  //   ];

  //   $data = [
  //     'ruc' => "20601039908", //$params['rucComprobante'],
  //     'usuario_sol' => "OVALLE20", // $request->usuario_sol,
  //     'clave_sol' =>  "Ovalle2020", // $request->clave_sol,
  //   ];

  //   $nameFile =
  //     $params['rucComprobante'] . '-' .
  //     $params['tipoComprobante'] . '-' .
  //     $params['serieComprobante'] . '-' .
  //     $params['numeroComprobante'] . '.zip';

  //   $sent = SunatHelper::getStatusCdr($params, $nameFile, false, $data);

  //   dd($sent);

  //   if (isset($sent->statusCdr->content)) {
  //     getTempPath($nameFile, $sent->statusCdr->content);
  //   }

  //   return redirect()->back();
  // }



  public function pdf()
  {
    $pdf = new Pdf([
      'commandOptions' => [
        'useExec' => true,
        'escapeArgs' => false,
        'procOptions' => [
          // This will bypass the cmd.exe which seems to be recommended on Windows
          'bypass_shell' => true,
          // Also worth a try if you get unexplainable errors
          'suppress_errors' => true,
        ],
      ],
    ]);

    $globalOptions = ['no-outline', 'page-size' => 'Letter'];
    $pdf->setOptions($globalOptions);
    // $pdf->addPage('https://www.marca.com/');
    $pdf->addPage(view('example.example'));
    $pdf->binary = 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe';

    if (!$pdf->send()) {
      throw new Exception('Could not create PDF: ' . $pdf->getError());
    }
  }



  public function tasa_cambio()
  {
    $sUrl = 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes=01&anho=2019';
    $sContent = file_get_contents($sUrl);

    // dd($sContent);
    // create new DOMDocument
    $doc = new \DOMDocument('1.0', 'UTF-8');
    // set error level
    $internalErrors = libxml_use_internal_errors(true);
    $doc->loadHTML($sContent);
    $xpath = new \DOMXPath($doc);
    $tablaTC = $xpath->query("//table[@class='class=\"form-table\"']"); //obtenemos la tabla TC
    $filas = [];
    foreach ($tablaTC as $fila) {
      $filas = $fila->getElementsByTagName("tr"); //obtiene todas las tr de la tabla de TC
    }
    $tcs = array(); //array de tcs, por dia como clave
    foreach ($filas as $fila) { //recorremos cada tr
      $tds = [];
      $tds = $fila->getElementsByTagName("td");
      $i = 0;
      $j = 0;
      $arr = [];
      $dia = "";
      foreach ($tds as $td) { //recorremos cada td
        if ($j == 3) {
          $j = 0;
          $arr = [];
        }
        if ($j == 0) {
          $dia = trim(preg_replace("/[\r\n]+/", " ", $td->nodeValue));
          $tcs[$dia] = [];
        }
        if ($j > 0 && $j < 3) {
          $tcs[$dia][] = trim(preg_replace("/[\r\n]+/", " ", $td->nodeValue));
        }
        $j++;
      }
    }

    dd($tcs);
  }

  // blade


  public function envio_resumen($id_resumen)
  {
    dd(Sunat::sentResumenOAnulacion($id_resumen));
  }

  public function editor()
  {
    return view('tests.ck5');
  }

  public function blade()
  {
    return view('tests.blade');
  }

  public function verificar_documentos()
  {
    return view('tests.verificar_documentos');
  }

  

  public function verificar_ticket(Request $request)
  {
    ob_end_clean();

    $data = [
      'ruc' => $request->ruc_empresa,
      'usuario_sol' => $request->usuario_sol,
      'clave_sol' => $request->clave_sol,
      'ticket' => $request->ticket,
    ];


    $sent = SunatHelper::ticket($request->ticket, "", true, $data);

    if (isset($sent->status->content)) {
      $nameFile =  time() . ".zip";
      $tempPath = getTempPath($nameFile, $sent->status->content);
      return response()->file($tempPath);
    }

    dd("sent", $sent);

    return redirect()->back();
  }


  public function calcular($id_venta = "000004")
  {
    $venta = Venta::find($id_venta);
    $d1 = $venta->toArray();
    $venta->calcularTotales();
    $d2 = $venta->toArray();
    return dd($d1, $d2);
  }

  public function envio_factura($id_factura)
  {
    $documento = Venta::find($id_factura);
    $input = XmlCreador($documento);;
    $data = $input->guardar();
    $sunat_controller = new SunatController();
    dd($sunat_controller->anulacion($id_factura));

    if ($enviar_sunat) {
      return Sunat::sendFactura($id_factura, false);
    }
  }

  public function validar_ticket($ticket)
  {
    $resumen = Resumen::where('DocTicket', $ticket)->first();
    $nameFile = $resumen->nameFile(true, '.zip');
    $sent = Sunat::verificarTicket($ticket, $nameFile);

    if ($sent['status']) {
    }
  }


  public function guardar_pdf($id_venta)
  {
    $venta->savePdf();
  }

  public function permisos()
  {
    \Artisan::call('db:seed', ['--class' => "EmpresaSeeder"]);
  }

  public function anulacion($id_factura = "000003")
  {
    $error = '';
    \DB::beginTransaction();
    try {
      $venta = Venta::find($id_factura);
      $ids = (array) $id_factura;
      $data = [];
      $data["fecha_generacion"] = hoy();
      $data["fecha_documento"] = $venta->VtaFvta;

      $resumen =
        Resumen::createResumen($data, hoy(), true);
      ResumenDetalle::createDetalle($resumen, $ids, true);
      $sent = Sunat::sentResumen($resumen->NumOper);

      if ($sent['status']) {
        $ticket = $sent['message'];
        $sent = Sunat::verificarTicket($sent['message'], $resumen->nameFile(true, '.zip'));
        $nameFile = $resumen->nameFile(true, '.xml');
        $content = $sent['content'];
        $datos = ["ResponseCode", "Description"];
        $data = extraer_from_content($content, $nameFile, $datos);
        if ($sent['status']) {
          $resumen->successAnulacion($data);
        } else {
          $resumen->errorValidacion($data);
        }
        $sent['content'] = "";
        $sent['message'] = $resumen->DocDesc;
      }
      \DB::commit();
      $success = true;
    } catch (\Exception $e) {
      $success = false;
      $error = $e->getMessage();
      \DB::rollback();
      return response()->json('Se ha producido un error ' . $error, 500);
    }


    if ($success) {
      return $sent;
    }
  }


  public function guia($id_guia = "000001")
  {
    $guia = GuiaSalida::findOrfail($id_guia);
    $guia->saveDespacho($request->all());
    $sent = Sunat::sendGuia($guia->GuiOper);
    // dd(Sunat::sendGuia($guia->GuiOper));
    if ($sent['status']) {
      $guia->successEnvio($sent);
      dd("ok");
    }
  }

  public function random($param1 = null, $param2  = null, $param3  = null, $param4  = null)
  {
    dump(
      "test",
      empcodi(),
      func_get_args(),
      Venta::getBoletasNoEnviadas(
        $param1,
        null,
        null,
        $param2
      )
    );
  }


  public function js()
  {
    return view('tests.js');
  }


  public function searchDocumento($documento, $type = "ruc")
  {

    $isRuc = $type == "ruc";

    $search = new ConsultarDocumento($documento, $isRuc);
    $data = $search->search();

    // searchDniOpti

    return $data;
  }

  public function events($documento  = "32301376", $searchDni = 1)
  {

    if ($searchDni) {
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', 'http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI=' . $documento);
      dd((string) $res->getBody(true));
    } else {
    }
  }

  public function m()
  {
    dd(file_get_contents("https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl"));

    $models = M::where('Emp_cCodigo', "001")
      ->where('Pan_cAnio', "2018")->get();


    // ->count();
    $data_format = [
      'empresa_id' => 'null,',
      'active' => "1,",
      'code' => '',
      'descripcion' => '',
      'titulo' => '',
      'centro_costo' => '',
      'provision' => '',
      'documento' => '',
      'tipoentidad_id' => null,
      'cuenta_debe_id' => null,
      'cuenta_haber_id' => null,
      'cuenta_funcion_id' => null,
      'cuenta_naturaleza_id' => null,
    ];

    $data = [];

    foreach ($models as $model) {

      // dd($model);
      $current_data = $data_format;
      $current_data['code'] = $model['Pla_cCuentaContable'] . ',';
      $current_data['descripcion'] = "'" . $model['Pla_cNombreCuenta']  . "',";
      $current_data['titulo'] = ($model['Pla_cTitulo'] == "S" ? "1," : "0,");
      $current_data['centro_costo'] = ($model['Pla_cCentroCosto'] ? $model['Pla_cCentroCosto'] : "null") . ",";
      $current_data['provision'] = ($model['Pla_cProvision'] ? $model['Pla_cProvision'] : 0) . ",";
      $current_data['documento'] = ($model['Pla_cDocumento'] ? $model['Pla_cDocumento'] : 0) . ",";

      $current_data['tipoentidad_id'] = ($model['Pla_cTipoCta'] ? ("'" . $model['Pla_cTipoCta'] . "'") : "null")  . ",";

      $current_data['cuenta_debe_id'] = $model['Pla_cCptoBG'] ? ("'" . $model['Pla_cCptoBG'] . "'") : "null,";
      $current_data['cuenta_haber_id'] = $model['Pla_cCptoBGDual'] ?  ("'" . $model['Pla_cCptoBGDual'] . "'") : "null,";
      $current_data['cuenta_funcion_id'] = $model['Pla_cCptoResFun'] ?  ("'" . $model['Pla_cCptoResFun'] . "'") : "null,";
      $current_data['cuenta_naturaleza_id'] = $model['Pla_cCptoResNat'] ?  ("'" . $model['Pla_cCptoResNat'] . "'") : "null,";

      array_push($data, $current_data);
    }

    $path = public_path('temp\\' . 'filename.txt');
    $fp = fopen($path, 'w');
    fwrite($fp, print_r($data, TRUE));
    fclose($fp);
  }

  public function notify()
  {
    // Clde
    $titulo = "Anulación exitosa";
    $message = "enlaces  <a href='#'> Suscribete Aquí aqui </a>";
    $tipo = "success";

    notificacion(
      $titulo,
      $message,
      $tipo,
      ['hideAfter' => false]
    );

    return redirect()->route('home');

    // return auth()->user()->notify(new InvoicePaid());
  }


  public function consultDocument($document = "20010271", $type = "dni")
  {
    $search = null;

    switch ($type) {

      case 'ruc':
        $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));
        break;

      case 'dni':
        $cs = new Dni(new ContextClient(), new DniParser());
        break;

      case 'sol':
        $cs = new UserValidator(new ContextClient());
        $ruc = "20605379801";
        $user = "GIROSELF";
        $valid = $cs->valid($ruc, $user);
        if ($valid) {
          return 'Válido';
        } else {
          return 'Inválido';
        }
        break;
    }

    $search = $cs->get($document);

    if (!$search) {
      echo 'Not found';
      exit();
    }

    echo json_encode($search);
  }


  public function messageTlf($tlf, $proveedor = 'nexmo')
  {
    if ($proveedor == "nexmo") {
      $basic  = new \Nexmo\Client\Credentials\Basic('8bacc1f4', '9lPuSCXREsvD9uow');
      $client = new \Nexmo\Client($basic);
      $message = $client->message()->send([
        'to' => '51924015334',
        'from' => 'Vonage APIs',
        'text' => 'Hello from Vonage SMS API'
      ]);

      /*
      $basic  = new \Nexmo\Client\Credentials\Basic('8bacc1f4', '9lPuSCXREsvD9uow');
      $client = new \Nexmo\Client($basic);
      $message = $client->message()->send([
          'to' => '51935857187',
          'from' => 'Vonage APIs',
          'text' => 'Hello from Vonage SMS API'
      ]);


      Allowed memory size of 1610612736 bytes exhausted


      COMPOSER_MEMORY_LIMIT=-1 composer require nexmo/client

      nextmo

      clave
      4310881Abuela_
      */
      // Allowed memory size of 1610612736 bytes exhausted
      // COMPOSER_MEMORY_LIMIT=-1 composer require nexmo/client
      // nextmo
      // clave
      // 4310881Abuela_
    } else {
    }
  }



  public function pdfMobil()
  {
    $oldPath = public_path('rc/default.jpg');

    $images_name = [
      'volkswagen.jpg',
      'sindefinir.jpg',
      'volvo.jpg',
      'mitsubishi.jpg',
      'chevrolet.jpg',
      'pastillasdefreno.jpg',
      'cremallerasvolante.jpg',
      'scania.jpg',
      'accesoriosbombasfreno.jpg',
      'taponculata.jpg',
      'nissan.jpg',
      'hyundai.jpg',
      'ponchos.jpg',
      'toyota.jpg',
      'isuzu.jpg',
      'dodge.jpg',
      'datsun.jpg',
      'kia.jpg',
      'multihojas.jpg',
      'parabolicos.jpg',
      'carreta.jpg',
      'focos.jpg',
      'miscelania.jpg',
      'bombasdegasolina.jpg',
      'marcadores.jpg',
      'armadurasarrancador.jpg',
      'automaticodearrancador.jpg',
      'bendixarrancador.jpg',
      'switchs.jpg',
      'bujiasdeencendido.jpg',
      'bujiasdeencendidokia.jpg',
      'bujiasdeencendidoford.jpg',
      'lineahkt.jpg',
      'plumillas.jpg',
      'platinosycondensadores.jpg',
      'repuestoselectricos.jpg',
      'bujiasdeencendidochevrolet.jpg',
      'fusibles.jpg',
      'reguladoresdevoltaje.jpg',
      'cablesautomotrices.jpg',
      'carbonesarrancador.jpg',
      'portadiodos.jpg',
      'monark.jpg',
      'repuestossm.jpg',
      'repuestovolvovolvo.jpg',
      'repuestoshagen.jpg',
      'repuestostou.jpg',
      'repuestovolvo.jpg',
      'repuestovolvohowo.jpg',
      'repuestovolvoscania.jpg',
      'repuestosfebi.jpg',
      'repuestoscei.jpg',
      'bombasgmb.jpg',
      'bombadeagua.jpg',
      'bombasaguahq.jpg',
      'accesoriosbombaagua.jpg',
      'platosydiscosexcedy.jpg',
      'collarinesvarios.jpg',
      'platosydiscossecokia.jpg',
      'platosydiscosseco.jpg',
      'platosydiscosseconissan.jpg',
      'platosydiscossecomitsubishi.jpg',
      'platosydiscossecomazda.jpg',
      'platosdiscoskawhe.jpg',
      'platosydiscospacers.jpg',
      'platosydiscosaisin.jpg',
      'platosdiscosycollarines.jpg',
      'collarineskoyododge.jpg',
      'collarineskoyo.jpg',
      'collarinespfi.jpg',
      'collarineskoyotoyota.jpg',
      'platosydiscosvaleohyundai.jpg',
      'platosydiscosvaleonissan.jpg',
      'platosydiscosvaleotoyota.jpg',
      'platosydiscosvaleo.jpg',
      'platosydiscosvaleomitsubishi.jpg',
      'platosydiscosvaleochevrolet.jpg',
      'platosydiscosvaleokia.jpg',
      'collarineskoyonissan.jpg',
      'collarineskoyomitsubishi.jpg',
      'collarineskoyosuzuki.jpg',
      'platosydiscossecochevrolet.jpg',
      'platosydiscosvaleosuzuki.jpg',
      'platosydiscosvaleojac.jpg',
      'crucetadecardanpreciso.jpg',
      'crucetadecardanprecisoford.jpg',
      'crucetadecardantoyotoyota.jpg',
      'crucetadecardankaym.jpg',
      'crucetadecardanmtg.jpg',
      'crucetadecardanskf.jpg',
      'crucetadecardansm.jpg',
      'crucetascardansamtin.jpg',
      'crucetadecardantoyo.jpg',
      'crucetadecardanbull.jpg',
      'crucetadecardan.jpg',
      'crucetasdecardangmg.jpg',
      'partesdecardanprecisotoyota.jpg',
      'partesdecardanprecisodatsun.jpg',
      'partesdecardanprecisonissan.jpg',
      'partesdecardanprecisomazda.jpg',
      'partesdecardanprecisomitsubishi.jpg',
      'partesdecardanprecisoisuzu.jpg',
      'partesdecardanprecisohyundai.jpg',
      'partesdecardanpreciso.jpg',
      'partesdecardanprecisovolvo.jpg',
      'partesdecardan.jpg',
      'soportescardansamtin.jpg',
      'bombadeembragueprecisotoyota.jpg',
      'bombadeembragueprecisodatsun.jpg',
      'bombadeembragueprecisonissan.jpg',
      'bombadeembragueprecisomitsubishi.jpg',
      'bombadeembraguepreciso.jpg',
      'bombadeembrague.jpg',
      'bombasembragueaisin.jpg',
      'bombadeembragueprecisokia.jpg',
      'bombadeembragueprecisohyundai.jpg',
      'puntadepalierdaiyomazda.jpg',
      'puntadepalierdaiyonissan.jpg',
      'puntadepalierdaiyomitsubishi.jpg',
      'puntadepalierdaiyochevrolet.jpg',
      'puntadepalierdaiyo.jpg',
      'puntadepalierdaiyotoyota.jpg',
      'puntadepalierdaiyoford.jpg',
      'puntadepalierskf.jpg',
      'puntadepalierkoyama.jpg',
      'puntadepalierytricetas.jpg',
      'tricetasdepalier.jpg',
      'resortes.jpg',
      'filtrodegasolinateyco.jpg',
      'filtrodegasolinateyconissan.jpg',
      'filtrodegasolinateycotoyota.jpg',
      'filtros.jpg',
      'filtrosaire.jpg',
      'filtrosaceite.jpg',
      'filtrospetroleo.jpg',
      'filtrospetroleomitsubishi.jpg',
      'filtrospetroleotoyota.jpg',
      'conicos.jpg',
      'bolas.jpg',
      'canastillas.jpg',
      'rodamientos.jpg',
      'dac.jpg',
      'agujas.jpg',
      'rodamientosaxiales.jpg',
      'soportedemotorteruyotoyota.jpg',
      'soportedemotorteruyofoton.jpg',
      'soportedemotorteruyonissan.jpg',
      'soportedemotorteruyo.jpg',
      'soportedemotorteruyokia.jpg',
      'soportedemotorteruyovolvo.jpg',
      'soportedemotorteruyodatsun.jpg',
      'soportedemotorteruyohyundai.jpg',
      'soportedemotorteruyomitsubishi.jpg',
      'soportedemotor.jpg',
      'soportedemotorteruyochevrolet.jpg',
      'precalentadoreshkt.jpg',
      'precalentadoresjkt.jpg',
      'precalentador.jpg',
      'rodamientostimken.jpg',
      'rodamientosmbs.jpg',
      'rodamientosskf.jpg',
      'rodamientosntn.jpg',
      'rodamientosnsk.jpg',
      'rodamientospfi.jpg',
      'rodamientoskbc.jpg',
      'rodamientoslinkbelt.jpg',
      'rodamientosfbj.jpg',
      'rodamientoscmb.jpg',
      'rodamientoskoyo.jpg',
      'rodamientoespecial.jpg',
      'rodamientosnachi.jpg',
      'reteneswb.jpg',
      'retenes.jpg',
      'fajaschevrolet.jpg',
      'fajasdaewoo.jpg',
      'fajascitroenpeugeot.jpg',
      'fajasfiat.jpg',
      'fajasmitsubishi.jpg',
      'fajadedistribucionnissan.jpg',
      'fajadedistribucionsuzuki.jpg',
      'fajadedistribuciontoyota.jpg',
      'fajadedistribucionisuzu.jpg',
      'fajadedistribucionkiamazda.jpg',
      'fajadedistribucionhyundai.jpg',
      'templadoresdefaja.jpg',
      'fajadedistribuciónytempladores.jpg',
      'pinesdireccionsamtin.jpg',
      'pinesdireccionkorea.jpg',
      'pinesdireccionamericanos.jpg',
      'pinesybobinasdedirección.jpg',
      'liquidosdefreno.jpg',
      'spraysaditivos.jpg',
      'grasas.jpg',
      'lubricantesyaditivos.jpg',
      'liquidosderadiador.jpg',
      'aceitesehidrolinas.jpg',
      'terminalesdireccioncarrosamericanos.jpg',
      'rotulasctr.jpg',
      'terminalesvolvo.jpg',
      'terminalesvolvovolvo.jpg',
      'terminalessamtin.jpg',
      'terminalesctr.jpg',
      'rackend.jpg',
      'terminalesyrotulasdedirección.jpg',
      'pernosderueda.jpg',
      'pernoscentrales.jpg',
      'pernoscaliper.jpg',
      'pernosautomotrices.jpg',
      'tuercasfunda.jpg',
      'chumacerasntn.jpg',
      'chumaceraskml.jpg',
      'chumacerastl.jpg',
      'chumaceraswhb.jpg',
      'chumacerasskf.jpg',
      'chumacerasindustriales.jpg',
      'manguerasoil.jpg',
      'manguerasflexibles.jpg',
      'mangueras.jpg',
      'manguerasradiador.jpg',
      'bypass.jpg',
      'abrazaderasdecremallera.jpg',
      'abrazaderasgbs.jpg',
      'pegamentos.jpg',
      'quimicos.jpg',
      'ferreteria.jpg',
      'herramientas.jpg',
      'pegamentosquimicos.jpg',
      'asbestoenplancha.jpg',
      'vitoritexmetro.jpg',
      'empaqueculata.jpg',
      'empaquebalancines.jpg',
      'empaques.jpg',
      'empaquescompresorajuego.jpg',
      'empaquescarter.jpg',
      'empaquescompresorajuegovolvo.jpg',
      'empaquescompresorajuegoford.jpg',
      'empaquesdeeje.jpg',
      'empaquesdecorona.jpg',
      'pernosg8.jpg',
      'pernoshexagonales.jpg',
      'tuercasaltas.jpg',
      'pernosg2.jpg',
      'pernoscabezacoche.jpg',
      'zincado.jpg',
      'cablesbujiaphc.jpg',
      'cablesbujiangk.jpg',
      'cablesdebujia.jpg',
      'abrazaderasnacionales.jpg',
      'abrazaderasimportadas.jpg',
      'bombasgasolina.jpg',
      'cebadorespetroleo.jpg',
      'bombasgasolinaycebadores.jpg',
      'cebadorespetroleotoyota.jpg',
      'cebadorespetroleonissan.jpg',
      'cebadorespetroleohyundai.jpg',
      'bridasrueda.jpg',
      'bocamazas.jpg',
      'amortiguadoressuspension.jpg',
      'amortiguadores.jpg',
      'soporteamortiguador.jpg',
      'lainaspinesdireccion.jpg',
      'lainascorona.jpg',
      'lainasaluminio.jpg',
      'lainasdecamiseta.jpg',
      'lainasaluminiovolvo.jpg',
      'bombasaceiteaisin.jpg',
      'bombasdeaceite.jpg',
      'bocinasmuelle.jpg',
      'pinmuelle.jpg',
      'pinesdemuelle.jpg',
      'grilletedemuelle.jpg',
      'bocinasarrancador.jpg',
      'cadenasdist.jpg',
      'templadorcadena.jpg',
      'guiasdistribucion.jpg',
      'oringcubo.jpg',
      'milimetros.jpg',
      'oringcubovolvo.jpg',
      'oringplano.jpg',
      'bombinnissan.jpg',
      'bombasbombinestoyota.jpg',
      'bombinmazdakia.jpg',
      'aspasdeventiladora.jpg',
      'arrancadoresyalternadores.jpg',
      'arrancador.jpg',
      'pernossocketallen.jpg',
    ];


    for ($i = 0; $i < count($images_name); $i++) {
      # code...
      $name_image = $images_name[$i];
      $newPath = public_path("rc/news/{$name_image}.jpg");
      \File::copy($oldPath, $newPath);
    }
  }

  public function tasksExecute()
  {
    $empresa = Empresa::find("009");
    $res = $empresa->getOrGenerateGuiaTokenApi();
    _dd($res);
    exit();
  }

  public function ticketera()
  {
    // $pdfBase = base64_encode(file_get_contents(file_build_path(public_path(), 'temp', 'ticket.pdf')));


    // return view('tests.qz', ['pdfBase' => $pdfBase]);
    return view('tests.qz');
  }

  public function firmaDigitalPublic()
  {
    return base64_encode(file_get_contents(file_build_path(public_path(), 'static', 'cert','cert_public.txt')));
  }


  public function firmaDigitalImpresion( Request $request )
  {

    /*
    * Echoes the signed message and exits
  */

    // #########################################################
    // #             WARNING   WARNING   WARNING               #
    // #########################################################
    // #                                                       #
    // # This file is intended for demonstration purposes      #
    // # only.                                                 #
    // #                                                       #
    // # It is the SOLE responsibility of YOU, the programmer  #
    // # to prevent against unauthorized access to any signing #
    // # functions.                                            #
    // #                                                       #
    // # Organizations that do not protect against un-         #
    // # authorized signing will be black-listed to prevent    #
    // # software piracy.                                      #
    // #                                                       #
    // # -QZ Industries, LLC                                   #
    // #                                                       #
    // #########################################################

    // Sample key.  Replace with one used for CSR generation
    // $KEY = 'private-key.pem';
    //$PASS = 'S3cur3P@ssw0rd';

    $KEY =  getTempPath('private_key.pem');
    // $KEY =  config('app.private_key.path'); 
    $PASS = config('app.private_key.password');

    // C:\xam\htdocs\loquesea\public\temp

    // $req = $_GET['request'];
    $req = $request->getRequestUri();

    $privateKey = $PASS ? openssl_get_privatekey(file_get_contents($KEY), $PASS) : openssl_get_privatekey(file_get_contents($KEY));


    $signature = null;
    openssl_sign($req, $signature, $privateKey, "sha512"); // Use "sha1" for QZ Tray 2.0 and older

    /*
    // Or alternately, via phpseclib
    include('Crypt/RSA.php');
    $rsa = new Crypt_RSA();
    $rsa.setHash('sha512'); // Use 'sha1' for QZ Tray 2.0 and older
    $rsa->loadKey(file_get_contents($KEY));
    $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
    $signature = $rsa->sign($req);
    */

    
    // if ($signature) {
    //   return base64_encode($signature);
    // }

    // echo '<h1>Error signing message</h1>';
    // http_response_code(500);
    // exit(1);


    if ($signature) {
      header("Content-type: text/plain");
      header("Access-Control-Allow-Origin: *");
      echo base64_encode($signature);
      exit(0);
    }

    echo '<h1>Error signing message</h1>';
    http_response_code(500);
    exit(1);
  }


  public function updateBoletas( $vtaOper = null )
  {
    $fecha = new Carbon('2021-05-10');

    $docs = []; 
    

    for( $i = 0;  $i < 100; $i++  ){

      $currentFecha = $fecha->format('Y-m-d');

      $boleta = Venta::where('VtaFvta', $currentFecha )
      ->where('TidCodi', '03')
      ->orderBy('VtaNume', 'desc')
      ->first();

      if( $currentFecha == "2021-07-01" ){
        break;
      }

      if( $boleta ){
        $total = $boleta->VtaImpo;
        $base = fixedValue( $total / 1.18); 
        $igv = $total - $base;
        $boleta->VtaIGVV = $igv;
        $boleta->VtaBase = $base;
        $boleta->VtaExon = "0.00";
        $boleta->CajNume = "112-000002";
        $boleta->save();
        foreach( $boleta->items as $item ){
          $item->UniCodi = "10000101";
          $item->DetCodi = "00010105";
          $item->DetNomb = "POR EL SERVICIO UNITARIO DE 2.00 SOLES POR LAS OPERACIONES REALIZADAS";
          $item->DetIGVV = 18;
          $item->DetBase = Venta::GRAVADA;
          $item->DetIGVP = $igv;
          $item->TipoIGV = 10;
          $item->incluye_igv = 1;
          $item->save();
        }
     }

      $fecha->addDay();
    }


 
  }

  public function updateClientes()
  {
    set_time_limit(1200);


    $clientes = [
      ['73342019','GEISON MACHADO	','B'],
      ['13461315','NOLMARIS DIAZ	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['65468778','MARYBELIS	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['18918398','YOLEISY RIOS	','B'],
      ['22710493','YUDIMAR DEL VALLE CORDERO CALZADILLA	','B'],
      ['17197902','NOREIDA CORDOVA TRENARD	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['19003467','ANYELY LANDAETA	','B'],
      ['15477163','GABRIEL GUTIÉRREZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['81694262','GUSTAVO HUAROTO MOLINA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['33128522','YOSBETH HERRERA HURTADO	','B'],
    ];

    foreach( $clientes as $cliente_data ){

      $cliente = ClienteProveedor::where('PCRucc', $cliente_data[0])
      ->where('TdoCodi', $cliente_data[2] )->first();
      if( $cliente ){
        $cliente->PCNomb = $cliente_data[1];        
        $cliente->save();
      }
    }


  }


  public function consultStatus( $tidCodi , $serie = null , int $nume_init = null  )
  {
    set_time_limit(1200);

    $ventas = Venta::where('TidCodi', $tidCodi );

    if( $serie  ){
      $ventas->where('VtaSeri', $serie );
    }

    if( $nume_init  ){
      $nume_init = agregar_ceros( $nume_init, 6 , 0);
      $ventas->where('VtaNumee', '>=', $nume_init );
    }

    $ventas = $ventas->get();


    foreach( $ventas as $venta ){
     $venta->searchSunatGetStatus(true);
    }

    notificacion('Accion exitosa', '', 'success');
    return redirect()->route('home');
  }

  public function xmlToVenta($empresaId, $desdeSerie = null, $tipoDocImport = null)
  {
    $empresa = Empresa::find($empresaId);

    (new ActiveEmpresaTenant($empresa))->handle();
    
    $importFromXml = new ImportFromXml( config('app.xml_importacion'), $empresa, $desdeSerie, $tipoDocImport);
    $importFromXml->handle();
  }

  // old code
  // public  function xmlToVenta()
  // {
  //   set_time_limit(1200);


  //   // old code
  //     // $directory = '/path/to/files';
  //   $directory = getTempPath(file_build_path('subida'));
    

  //   $files = array();
  //   foreach (scandir($directory) as $file) {
  //       if ($file !== '.' && $file !== '..') {
  //           $files[] = $file;
  //       }
  //   }

  //   dd($files);

  //   $empresa = get_empresa();
  //   $producto = Producto::findByProCodi('S');
  //   $unidad = $producto->unidades->first();
  //   $lista = $empresa->listas->first();
  //   $loccodi = "001";
  //   $data = []; 


  //   foreach( $files as $file ){
  //     $path = getTempPath(file_build_path('subida',  $file) );
  //     $createXmlToVenta = new CreateVentaFromXML( $path, $empresa, User::find(1) , $lista,  $producto, $unidad, $loccodi );
  //     $data[] = $createXmlToVenta->handle();      
  //   }

  //   // return $data;
  //   dd("listo");
  // }

  public  function xmlResumenToVenta()
  {
    set_time_limit(1200);

    $directory = getTempPath(file_build_path('subida_resumen'));

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }

    // dd( $files );

    $empresa = get_empresa();
    $producto = Producto::findByProCodi('00010100');
    $unidad = $producto->unidades->first();
    $lista = $empresa->listas->first();
    $loccodi = "001";

    // dd($files);
    $data = []; 
    foreach( $files as $file ){
      $path = getTempPath(file_build_path('subida_resumen',  $file) );
      $createXmlToVenta = new CreateVentaFromXML( $path, $empresa, User::find(12) , $lista,  $producto, $unidad, $loccodi );
      $data[] = $createXmlToVenta->handle();      
    }

    // return $data;

    dd("listo");
  }

  public  function xmlResumenToResumen()
  {
    set_time_limit(1200);

    $directory = getTempPath(file_build_path('subida_resumen'));

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }

    $empresa = get_empresa();
    $loccodi = "001";

    // dd($files);
    $data = []; 
    foreach( $files as $file ){
      $path = getTempPath(file_build_path('subida_resumen',  $file) );
      $createXmlToVenta = new XmlToRC( $path, $empresa, User::find(12), $loccodi );
      $data[] = $createXmlToVenta->handle();
    }

    dd("listo todo");
  }

  public  function validateResumen()
  {
    set_time_limit(1200);

    $directory = getTempPath(file_build_path('resumen_validar'));

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }


    $data = []; 
    foreach( $files as $file ){
      $path = getTempPath(file_build_path('resumen_validar',  $file) );
      $createXmlToVenta = new validateResumen( $path );
      $data[] = $createXmlToVenta->handle();
    }


    dd("listo todo");
  }

  public function imprimirDirecto( $document_id )
  {
    $venta = Venta::find($document_id);
    $serie = $venta->getSerie();
    $result = $venta->generatePDF(PDFPlantilla::FORMATO_A4, true, false, true);
    $data_impresion = Venta::prepareDataVentaForJavascriptPrint($result['data']);
    
    $data = [
      'message' => 'Data de impresion enviada',
      'imprecion_data' => [
      'impresion_directa' => $serie->impresion_directa,
      'cantidad_copias' => $serie->cantidad_copias,
      'nombre_impresora' => $serie->nombre_impresora,
      'data_impresion' => $data_impresion
      ]
    ];

    return response()->json($data);
  }


  public function testPrint()
  {
    $pdf = new PDFGenerator(view('tests.print'), PDFGenerator::HTMLGENERATOR );
    $pdf->generator->setGlobalOptions( PDFGenerator::getSetting( PDFPlantilla::FORMATO_TICKET, PDFGenerator::HTMLGENERATOR ));
    return $pdf->generate();
  }   
}