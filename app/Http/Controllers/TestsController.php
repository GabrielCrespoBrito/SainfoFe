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
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Controllers\SunatController;
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
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['17499331','RUBEN COLMENARES	','B'],
      ['16594084','RAMON SANGUINO	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['18772154','OSCAR ARANGUIBEL	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['13032252','RUT CALDERA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['27839484','YONAIKER RAFAEL ALVAREZ PEREZ	','B'],
      ['26193538','ELISAUL FERNANDEZ	','B'],
      ['30952724','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['25374550','ALEXA GUERRERO	','B'],
      ['82290796','SANDY GAMARRA	','B'],
      ['12653924','FABIOLA UZCATEGUI	','B'],
      ['98718185','CARLOS PAULINO GALLO	','B'],
      ['19070274','CESAR RAMIREZ BENCONO	','B'],
      ['43218712','MARYLIN RODRIGUEZ PUERTAS	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['28498302','GREGORIA MAVARE	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['24272948','KEVERLIN HERNANDEZ	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['20786178','JESUS SANCHEZ	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['41253828','HECTOR HUAMASH	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['23585228','FRANKLIN GARCIA	','B'],
      ['63087752','JORGE PINO DELGADO	','B'],
      ['17295021','LUIS JOSE ESPINOZA GONZALEZ	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['63604512','TERESA CARABALLO	','B'],
      ['30288803','JHOSETH RODRIGUEZ RODRIGUEZ	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['23649370','MIGUEL IGNACIO GONZALEZ CUEVA	','B'],
      ['18544171','YUSKEILYS SANTA CRUZ	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['48051791','RENATTA RUESTA GUERRERO	','B'],
      ['11053774','YUSMAR BLANCO	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['96441302','CIRILO CARDENAS	','B'],
      ['21239585','DAISY PEREZ	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['29800124','NELLY TORRES VILLA	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['20267380','CRISTOPHER COLMENARES	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['93292087','JESUS RONDON	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['42971218','LISSETH RIVAS	','B'],
      ['22320419','GENESIS MANZINI ALVAREZ	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['70204524','JULIO LEONEL LARIOS PERALTA	','B'],
      ['15058995','YNDIRA MAURY PIÑERO	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['18836324','MAOLY HERESI	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['15753357','WILMER PEREZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['10000000','LEONAR LUJANO	','B'],
      ['20355245','ANGEL ASCANIO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['12148100','MARYORI CAMACHO	','B'],
      ['14568921','DANIELA OBERTO GONZALEZ	','B'],
      ['94163702','HUGO CAMPOS GIRON	','B'],
      ['72789981','LUIS ZAPATA MORALES	','B'],
      ['72789981','LUIS ZAPATA MORALES	','B'],
      ['17295021','LUIS JOSE ESPINOZA GONZALEZ	','B'],
      ['16356807','ELEAZAR SANTAELLA	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['13521919','LAURI GUTIERREZ	','B'],
      ['21093170','ALEXANDRA SALAZAR	','B'],
      ['20198565','ADNER GODOY	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['92566000','ROSMARY RAMON	','B'],
      ['65484233','DANIEL LOPEZ	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['22598472','LUISANA FUCIL	','B'],
      ['15424671','JOSE PARRA	','B'],
      ['19003467','ANYELY LANDAETA	','B'],
      ['29800121','NELLY TORRES VILLA	','B'],
      ['20323748','ANNABETH ROJAS RAMOS	','B'],
      ['74507022','WILLIAM DEL VALLE	','B'],
      ['23635742','YOSELIN RODRIGUEZ	','B'],
      ['98782132','DANIEL LOPEZ	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['22915147','STIFANY MEJIA	','B'],
      ['21480929','EMILIO PADRON DELGADO	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['24314817','JESUS CONTRERAS	','B'],
      ['22692121','MANUEL ALBERTO BENCOMO	','B'],
      ['22692191','MANUEL ALBERTO BENCOMO	','B'],
      ['32165451','OLIVER RODRIGUEZ	','B'],
      ['17323598','WILMER SUAREZ	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['10927027','JUAN ZAMBRANO ROMERO	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['23739808','MAYRA NOGUERA	','B'],
      ['30111572','DAYANA GUILLEN	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['24501045','NELSON MILLAN	','B'],
      ['14606147','CESAR VALERO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['26137109','ANTHONY GABRIEL GUEVARA FERNÁNDEZ	','B'],
      ['32083902','ERIKA OBERTO	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['24679845','MARLY RODRIGUEZ	','B'],
      ['13032252','RUT CALDERA	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['16485983','FERNANDO MARCANO	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['15753357','WILMER PEREZ	','B'],
      ['21093170','ALEXANDRA SALAZAR	','B'],
      ['14432581','LIGMARY AGUILARA	','B'],
      ['14432581','LIGMARY AGUILARA	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['46989765','GIANCARLO AGUILAR ABURTO	','B'],
      ['13321553','JUAN MANUEL SUAREZ	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['14728569','ZARETH MATA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['30952721','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['19363221','DUGLAS MOGOLLON	','B'],
      ['19991124','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['19991124','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['25628820','RAQUEL CAVERO INFANTAS	','B'],
      ['28204853','MARIANYELI PAOLA BARROETA DE ARMAS	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['18469979','WILMER DEL RISCO	','B'],
      ['65489721','MILAGROS CAROLINA DEL VALLE CASTAÑO	','B'],
      ['17499331','RUBEN COLMENARES	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['32083902','ERIKA OBERTO	','B'],
      ['17442821','JOSE FRANKLIN TESEN QUINTANA	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['14678300','ELIMAR LEVEL SANCHEZ	','B'],
      ['25164953','ANGIE ZAMBRANO	','B'],
      ['74219822','JESBER CISNEROS	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['12209287','DORALIS MERCHAN	','B'],
      ['21195701','VICTOR RODRIGUEZ	','B'],
      ['34218321','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['14348084','ELIO OCHOA	','B'],
      ['19763934','JESUS VALERRY	','B'],
      ['96441301','CIRILO CARDENAS	','B'],
      ['19658706','JOSE BARRIOS	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['10927027','JUAN ZAMBRANO ROMERO	','B'],
      ['75313318','MARIBEL ROMERO	','B'],
      ['22872937','MILAGROS DEL VALLE CASTAÑO GONZALEZ	','B'],
      ['84597627','JORGE MENDOZA	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['27095638','LUIS ANGEL JAIMES	','B'],
      ['20296962','JUAN LEAL	','B'],
      ['18851885','LUZDARIS MARTINEZ	','B'],
      ['20011102','KARLA PABON	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['19964951','GABRIEL SANCHEZ	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['30898361','MANUEL ALEJANDRO ALVAREZ SUCRE	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['71855602','PILAR ROSADO RAMIREZ	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['40743281','NATHAN DURAND CASTILLO	','B'],
      ['28076229','ANDRES BRACAMONTE	','B'],
      ['43049772','MIGUEL SAAVEDRA LOPEZ	','B'],
      ['26918731','FABIOLA RIVERO BECERRA	','B'],
      ['29800164','NELLY TORRES VILLA	','B'],
      ['29800164','NELLY TORRES VILLA	','B'],
      ['24042081','ROBERTH SAUCEDO	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['27839484','YONAIKER RAFAEL ALVAREZ PEREZ	','B'],
      ['14690393','MILEIDY VALLADARES	','B'],
      ['18836324','MAOLY HERESI	','B'],
      ['17169318','KAREN DEYANIRA MENDOZA BLANCO	','B'],
      ['44967634','SHIRLEY ANDREINA DELGADO	','B'],
      ['14690393','MILEIDY VALLADARES	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['11412192','PEDRO RAMON AGUILERA GOMEZ	','B'],
      ['25252430','STEPHANIE MARTINEZ ANTOLINES	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['15058995','YNDIRA MAURY PIÑERO	','B'],
      ['18918398','YOLEISY RIOS	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['22762070','ESTEFANY RAMOS PERDOMO	','B'],
      ['22282584','CRISTIAN RONDON VASQUEZ	','B'],
      ['30898334','MANUEL ALEJANDRO ALVAREZ SUCRE	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['18766508','YENIFER GUAICARA	','B'],
      ['18766508','YENIFER GUAICARA	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['20065725','LUISSANA GONZALEZ	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['13461315','NOLMARIS DIAZ	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['20345395','DEILYMAR GABRIELA CORDERO LIMPIO	','B'],
      ['29845921','KARINA PEREZ TORRES	','B'],
      ['41253828','HECTOR HUAMASH	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['30111572','DAYANA GUILLEN	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['32463034','MIRANDA PARIAS ESTRADA	','B'],
      ['48361136','LINO CONTRERAS	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['70204524','JULIO LEONEL LARIOS PERALTA	','B'],
      ['27095638','LUIS ANGEL JAIMES	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['95309262','ROSA DELLEPIANE	','B'],
      ['94163702','HUGO CAMPOS GIRON	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['73249990','MOISES SANCHEZ	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['41726430','JUNIOR GUILLEN	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['34218334','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['22598472','LUISANA FUCIL	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['16594084','RAMON SANGUINO	','B'],
      ['29516894','ROSANYELIS CAROLINA MANRIQUE	','B'],
      ['30111572','DAYANA GUILLEN	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['18436235','JESSICA PEREZ	','B'],
      ['18766508','YENIFER GUAICARA	','B'],
      ['42971218','LISSETH RIVAS	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['27839484','YONAIKER RAFAEL ALVAREZ PEREZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['13321553','JUAN MANUEL SUAREZ	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['25374550','ALEXA GUERRERO	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['23327164','NEURIS DEL CARMEN GOITIA MEJIAS	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['41610836','JORGE ROCA	','B'],
      ['19860868','YEIMMILY CAROLINA ARCILA BRAVO	','B'],
      ['25374550','ALEXA GUERRERO	','B'],
      ['24899074','YORDANY ZAMBRANO	','B'],
      ['30111575','DAYANA GUILLEN	','B'],
      ['14574277','RICHARD ALZUALDE	','B'],
      ['75935167','JOSLAINY VICTORIA BADELL SEQUERA	','B'],
      ['12914537','ANDREA COROMOTO RIVERO DE LEON	','B'],
      ['18851834','LUZDARIS MARTINEZ	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['22915147','STIFANY MEJIA	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['30111574','DAYANA GUILLEN	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['61775244','KAO ANYELO HO TORRES	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['14568924','DANIELA OBERTO GONZALEZ	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['82124501','FERNANDO CASTILLO	','B'],
      ['14678300','ELIMAR LEVEL SANCHEZ	','B'],
      ['29800134','NELLY TORRES VILLA	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['36400224','NELSON ENRIQUE MENDOZA GARCES	','B'],
      ['16614802','KIRLA HURTADO	','B'],
      ['46176133','JUAN LIZARZUBURU	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['23595364','WENDYS RUBIO	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['11412192','PEDRO RAMON AGUILERA GOMEZ	','B'],
      ['17784932','FELIX CUEVAS	','B'],
      ['17784932','FELIX CUEVAS	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['26918724','FABIOLA RIVERO BECERRA	','B'],
      ['34532814','YOLBERTH MORA COLMENARES	','B'],
      ['96558290','ALEXANDER ACOSTO	','B'],
      ['18851823','LUZDARIS MARTINEZ	','B'],
      ['17499331','RUBEN COLMENARES	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['10927027','JUAN ZAMBRANO ROMERO	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['22320419','GENESIS MANZINI ALVAREZ	','B'],
      ['21199051','JOHN ESTIVEN ZULUAGA	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19658706','JOSE BARRIOS	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['17323598','WILMER SUAREZ	','B'],
      ['19763934','JESUS VALERRY	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['23635742','YOSELIN RODRIGUEZ	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['19991196','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['18851864','LUZDARIS MARTINEZ	','B'],
      ['14550914','CARLOS ADRIAN COLINA CHIRINO	','B'],
      ['14550914','CARLOS ADRIAN COLINA CHIRINO	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['98746551','CARLOS COLO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['25696263','MONICA PAREJO	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['74219822','JESBER CISNEROS	','B'],
      ['31177128','MARTIN ALARCON FLORES	','B'],
      ['81377561','EDUARD SOLANO	','B'],
      ['33146321','MARIA JOSE MIRELES ROA	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['20872629','JOHAN PARRA	','B'],
      ['19266678','ADEURY CASTILLO	','B'],
      ['94163705','HUGO CAMPOS GIRON	','B'],
      ['22598472','LUISANA FUCIL	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['18883995','LUIS ALFREDO BRAVO	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['10927027','JUAN ZAMBRANO ROMERO	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['19003467','ANYELY LANDAETA	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['19266678','ADEURY CASTILLO	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['26918731','FABIOLA RIVERO BECERRA	','B'],
      ['13461315','NOLMARIS DIAZ	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['82599770','JOSEFINA CENTENO	','B'],
      ['26808519','FRANY HERNANDEZ MEDINA	','B'],
      ['93142591','JOSE GREGORIO MATHEUS	','B'],
      ['15665083','MARIANA RODRIGUEZ	','B'],
      ['15665083','MARIANA RODRIGUEZ	','B'],
      ['47764458','GABRIELA BLANCO	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['48051791','RENATTA RUESTA GUERRERO	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['15369601','MARIO MORENO RAMIREZ	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['18836324','MAOLY HERESI	','B'],
      ['26808519','FRANY HERNANDEZ MEDINA	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['34218334','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['28498302','GREGORIA MAVARE	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['12148100','MARYORI CAMACHO	','B'],
      ['27231901','MARIANA GAMEZ	','B'],
      ['24272948','KEVERLIN HERNANDEZ	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['20759246','WILMER RONDON	','B'],
      ['26838124','SHARON CACERES SANCHEZ	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['21389104','DOHYRALITH URBANEJA CENTENO	','B'],
      ['66536520','LOLYMAR QUINTERO	','B'],
      ['82180430','MAXIMO REYES SUSANO	','B'],
      ['18413862','ELIEZER EFRAIN CHAVEZ	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['20870362','CHRISTIAN RAMOS	','B'],
      ['15798654','MILAGROS CAROLINA DEL VALLE CASTAÑO	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['30952725','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['26918725','FABIOLA RIVERO BECERRA	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['20097964','EDUARDO ASCANIO	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['28076229','ANDRES BRACAMONTE	','B'],
      ['94163702','HUGO CAMPOS GIRON	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['66536520','LOLYMAR QUINTERO	','B'],
      ['46543125','DANIEL LOPEZ	','B'],
      ['12148100','MARYORI CAMACHO	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['42971218','LISSETH RIVAS	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['24899074','YORDANY ZAMBRANO	','B'],
      ['11949892','HENRY SILVA	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['24314817','JESUS CONTRERAS	','B'],
      ['18772154','OSCAR ARANGUIBEL	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['34218358','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['16525122','JUAN BURGOS PALOMINO	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['13488318','WILLIAM LOPEZ	','B'],
      ['22983260','WLADIMIR PEREZ	','B'],
      ['27197669','MELI RENDON	','B'],
      ['19363295','DUGLAS MOGOLLON	','B'],
      ['26967101','CARLOS MORALES	','B'],
      ['47807497','LUIS ORE ROJAS	','B'],
      ['17169312','KAREN DEYANIRA MENDOZA BLANCO	','B'],
      ['19991134','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['80247885','FREDDY OLIVEIRA RENGIFO	','B'],
      ['20097964','EDUARDO ASCANIO	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['36362552','ABRIL YDROGO BRACAMONTE	','B'],
      ['36362552','ABRIL YDROGO BRACAMONTE	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['19763934','JESUS VALERRY	','B'],
      ['81694262','GUSTAVO HUAROTO MOLINA	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['24679845','MARLY RODRIGUEZ	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['14241955','ADRIANA CHIRIVI	','B'],
      ['16614802','KIRLA HURTADO	','B'],
      ['24042081','ROBERTH SAUCEDO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['17197902','NOREIDA CORDOVA TRENARD	','B'],
      ['29800121','NELLY TORRES VILLA	','B'],
      ['15477163','GABRIEL GUTIÉRREZ	','B'],
      ['15021344','ELIAMAR LEVEL SANCHEZ	','B'],
      ['20490844','KAROLINE ROJAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['18163767','YULIANA SOSA	','B'],
      ['18918398','YOLEISY RIOS	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['18851821','LUZDARIS MARTINEZ	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['25374550','ALEXA GUERRERO	','B'],
      ['13563724','JOSÉ SARMIENTO	','B'],
      ['20490844','KAROLINE ROJAS	','B'],
      ['41253828','HECTOR HUAMASH	','B'],
      ['20490844','KAROLINE ROJAS	','B'],
      ['22838158','JORGE VILCHEZ	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['23635742','YOSELIN RODRIGUEZ	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['84597627','JORGE MENDOZA	','B'],
      ['49041674','EDUARDO GONZALEZ SANCHEZ	','B'],
      ['96687413','ANDRES LEONARDO LINARES BLANCO	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['27197669','MELI RENDON	','B'],
      ['74219821','JESBER CISNEROS	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['23739808','MAYRA NOGUERA	','B'],
      ['19379113','JOEL MARTINEZ	','B'],
      ['34945723','JOSE BRICEÑO HERRERA	','B'],
      ['75313315','MARIBEL ROMERO	','B'],
      ['21239585','DAISY PEREZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['15477163','GABRIEL GUTIÉRREZ	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['15424671','JOSE PARRA	','B'],
      ['70753405','GINO ABURTO VARGAS	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['12148100','MARYORI CAMACHO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['29845971','KARINA PEREZ TORRES	','B'],
      ['59600791','JESUS SANCHEZ	','B'],
      ['25579151','DANIELA MUJICA	','B'],
      ['20786178','JESUS SANCHEZ	','B'],
      ['14042411','JOSE SANCHEZ	','B'],
      ['17295021','LUIS JOSE ESPINOZA GONZALEZ	','B'],
      ['23555194','OLLANTAY GUEDEZ ACOSTA	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['20267380','CRISTOPHER COLMENARES	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['32083903','ERIKA OBERTO	','B'],
      ['17028699','LUIS ALFREDO ARAQUE	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['27197669','MELI RENDON	','B'],
      ['13321553','JUAN MANUEL SUAREZ	','B'],
      ['22872699','ERIKA DE LOS ANGELES CHACIN HENRIQUEZ	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19860868','YEIMMILY CAROLINA ARCILA BRAVO	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['19379113','JOEL MARTINEZ	','B'],
      ['19314596','MARIA CHAVEZ SERRADA	','B'],
      ['30952761','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['15963876','LISETT MARELYS SANCHEZ ANDAZORA	','B'],
      ['18228925','ERNESTO CORZO	','B'],
      ['18436235','JESSICA PEREZ	','B'],
      ['81377561','EDUARD SOLANO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['26918731','FABIOLA RIVERO BECERRA	','B'],
      ['13461315','NOLMARIS DIAZ	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['48987951','PEDRO RODRIGUEZ PONCE	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['81694261','GUSTAVO HUAROTO MOLINA	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['20345395','DEILYMAR GABRIELA CORDERO LIMPIO	','B'],
      ['20345395','DEILYMAR GABRIELA CORDERO LIMPIO	','B'],
      ['19003467','ANYELY LANDAETA	','B'],
      ['20099411','JUNIOR RODRIGUEZ	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['20670970','PIERANYELA PERAZA TIMAURE	','B'],
      ['34218345','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['42971218','LISSETH RIVAS	','B'],
      ['19540094','LUIS LOPEZ	','B'],
      ['13032256','RUT CALDERA	','B'],
      ['67456345','MARYBELIS	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['27197669','MELI RENDON	','B'],
      ['25252430','STEPHANIE MARTINEZ ANTOLINES	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['25374550','ALEXA GUERRERO	','B'],
      ['26838124','SHARON CACERES SANCHEZ	','B'],
      ['14728569','ZARETH MATA	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['37852221','ORIANA DEL VALLE CONTRERAS ALDANA	','B'],
      ['10223277','ISIS COLMENARES	','B'],
      ['20870362','CHRISTIAN RAMOS	','B'],
      ['17499331','RUBEN COLMENARES	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['21093170','ALEXANDRA SALAZAR	','B'],
      ['19070274','CESAR RAMIREZ BENCONO	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['81681554','JONI ALCALA UGAZ	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['27084345','JUAN JOSE MIRELES ZAPATA	','B'],
      ['13521919','LAURI GUTIERREZ	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['10223277','ISIS COLMENARES	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['21176027','LEORANA CARABALLO	','B'],
      ['15985787','DUVERLIS NAVA	','B'],
      ['42699292','MARIA ROJAS	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['19964951','GABRIEL SANCHEZ	','B'],
      ['95309262','ROSA DELLEPIANE	','B'],
      ['19991124','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['42278480','EDDY IVAN MARQUINA SEGURA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['18544171','YUSKEILYS SANTA CRUZ	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['13488318','WILLIAM LOPEZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['28498302','GREGORIA MAVARE	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['24501045','NELSON MILLAN	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['26838124','SHARON CACERES SANCHEZ	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['15343333','ISRAEL GONZALEZ	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['23585228','FRANKLIN GARCIA	','B'],
      ['15665083','MARIANA RODRIGUEZ	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['22320419','GENESIS MANZINI ALVAREZ	','B'],
      ['26545451','OLIVER RODRIGUEZ	','B'],
      ['20670970','PIERANYELA PERAZA TIMAURE	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['10927027','JUAN ZAMBRANO ROMERO	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['31177128','MARTIN ALARCON FLORES	','B'],
      ['21239582','DAISY PEREZ	','B'],
      ['40241718','MANUEL RODRIGUEZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['18766508','YENIFER GUAICARA	','B'],
      ['26808519','FRANY HERNANDEZ MEDINA	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['18851821','LUZDARIS MARTINEZ	','B'],
      ['20759246','WILMER RONDON	','B'],
      ['20065725','LUISSANA GONZALEZ	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['25846077','RENZO CASTILLO	','B'],
      ['25846077','RENZO CASTILLO	','B'],
      ['13245678','NOELIS SEQUERA	','B'],
      ['27943802','RENDA RIJO PEREZ	','B'],
      ['17429192','GENIGER VELASQUEZ	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['24679845','MARLY RODRIGUEZ	','B'],
      ['20870362','CHRISTIAN RAMOS	','B'],
      ['25036062','MARBELYS LIRA	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['21201084','JOSE MORON	','B'],
      ['18469979','WILMER DEL RISCO	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['23793311','YELIMBERT MORENO	','B'],
      ['47764458','GABRIELA BLANCO	','B'],
      ['14182460','HILDAMAR BRETO	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['13828191','KARLA RODRIGUEZ	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['18711138','KATHERINE DIAZ	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['28498302','GREGORIA MAVARE	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['26346427','JOSE GONZALEZ	','B'],
      ['80247884','FREDDY OLIVEIRA RENGIFO	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['30952721','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['32943438','HERNAN SUCLUPE	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['28347304','EDIXON VILORIA	','B'],
      ['19594497','JHOSWART RUBIO	','B'],
      ['14574277','RICHARD ALZUALDE	','B'],
      ['65498746','OLIVER RODRIGUEZ	','B'],
      ['74219822','JESBER CISNEROS	','B'],
      ['13197906','RUDEINA RAFAELA HERNANDEZ MENDEZ	','B'],
      ['65798461','CARLOS COLO	','B'],
      ['65798461','CARLOS COLO	','B'],
      ['81694265','GUSTAVO HUAROTO MOLINA	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['20330709','GENESIS CEDEÑO	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['18836324','MAOLY HERESI	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['18323220','YORDAN HERMOSO	','B'],
      ['28014121','RASHEL HERNANDEZ MARRUFO	','B'],
      ['66536520','LOLYMAR QUINTERO	','B'],
      ['20670970','PIERANYELA PERAZA TIMAURE	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['25908686','ANIBAL HERNANDEZ	','B'],
      ['26691662','JAISI PEREZ	','B'],
      ['23739808','MAYRA NOGUERA	','B'],
      ['12209287','DORALIS MERCHAN	','B'],
      ['23635742','YOSELIN RODRIGUEZ	','B'],
      ['33616572','GERSON GUZMAN AGUILAR	','B'],
      ['12603531','SULIMAR PARDO COBARRUBIA	','B'],
      ['25060676','YAMILETH GARCIA	','B'],
      ['25060676','YAMILETH GARCIA	','B'],
      ['48361136','LINO CONTRERAS	','B'],
      ['14728569','ZARETH MATA	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['32463061','MIRANDA PARIAS ESTRADA	','B'],
      ['27231901','MARIANA GAMEZ	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['80247885','FREDDY OLIVEIRA RENGIFO	','B'],
      ['39428391','YSRAEL ASCANIO PIÑA	','B'],
      ['32463034','MIRANDA PARIAS ESTRADA	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['93142594','JOSE GREGORIO MATHEUS	','B'],
      ['18469979','WILMER DEL RISCO	','B'],
      ['19658706','JOSE BARRIOS	','B'],
      ['27839484','YONAIKER RAFAEL ALVAREZ PEREZ	','B'],
      ['78625487','CARLOS COLO	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['31277954','HUMBERTO PLAZA FUENTES	','B'],
      ['40964981','GABRIEL EDUARDO ROJAS NEWMAN	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['96558290','ALEXANDER ACOSTO	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['33285531','JESUS GIL PERDOMO	','B'],
      ['14606147','CESAR VALERO	','B'],
      ['14606147','CESAR VALERO	','B'],
      ['20802290','DARWIN VALERA NGEL	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['30111571','DAYANA GUILLEN	','B'],
      ['20406900','EMILY YARELIS AGAMES CALDERON	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['20524021','ANDERSON TORREALBA	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['15369601','MARIO MORENO RAMIREZ	','B'],
      ['33425168','LUIS HERRERA	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['70204524','JULIO LEONEL LARIOS PERALTA	','B'],
      ['13521919','LAURI GUTIERREZ	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['15629129','YOEL CRUCES	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['19763934','JESUS VALERRY	','B'],
      ['19314596','MARIA CHAVEZ SERRADA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['15071951','JOSE RANGEL BRICEÑO	','B'],
      ['74219822','JESBER CISNEROS	','B'],
      ['17323598','WILMER SUAREZ	','B'],
      ['10567497','ROGERT POLANCO	','B'],
      ['23635742','YOSELIN RODRIGUEZ	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['20759246','WILMER RONDON	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['16374889','GABRIELA BERMUDEZ	','B'],
      ['30079263','SHADDAY ADRIANA ROJAS CENTENO	','B'],
      ['20872629','JOHAN PARRA	','B'],
      ['13563724','JOSÉ SARMIENTO	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['18836324','MAOLY HERESI	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['71855601','PILAR ROSADO RAMIREZ	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['13563724','JOSÉ SARMIENTO	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['19266678','ADEURY CASTILLO	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['12148100','MARYORI CAMACHO	','B'],
      ['20059620','LUISANA SUCRE	','B'],
      ['89880192','ANAIS ADAME	','B'],
      ['22320419','GENESIS MANZINI ALVAREZ	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['45874557','MARYBELIS	','B'],
      ['80247881','FREDDY OLIVEIRA RENGIFO	','B'],
      ['11383932','GABRIELA DI FLAVIANO HILDEBRANDT	','B'],
      ['40964951','GABRIEL EDUARDO ROJAS NEWMAN	','B'],
      ['82180430','MAXIMO REYES SUSANO	','B'],
      ['20355245','ANGEL ASCANIO	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['27024706','DIEGO CARMONA PARRA	','B'],
      ['27095638','LUIS ANGEL JAIMES	','B'],
      ['20330709','GENESIS CEDEÑO	','B'],
      ['15369601','MARIO MORENO RAMIREZ	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['14348084','ELIO OCHOA	','B'],
      ['17295021','LUIS JOSE ESPINOZA GONZALEZ	','B'],
      ['61166682','LUIS ALBERTO APOLINARIO MUÑOZ	','B'],
      ['30952732','LISBETH COROMOTO FIGUERA YORIS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['32083902','ERIKA OBERTO	','B'],
      ['21239581','DAISY PEREZ	','B'],
      ['12653924','FABIOLA UZCATEGUI	','B'],
      ['81377561','EDUARD SOLANO	','B'],
      ['14568921','DANIELA OBERTO GONZALEZ	','B'],
      ['22020442','MAYFER TORRES	','B'],
      ['18851821','LUZDARIS MARTINEZ	','B'],
      ['80247889','FREDDY OLIVEIRA RENGIFO	','B'],
      ['42653723','JOSE ANTONIO USCUCHAGUA FLORES	','B'],
      ['20098113','JESSIKA SANCHEZ VALERA	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['27943802','RENDA RIJO PEREZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['19763934','JESUS VALERRY	','B'],
      ['11100719','JOSE HERNANDEZ VARGAS	','B'],
      ['92566000','ROSMARY RAMON	','B'],
      ['20296962','JUAN LEAL	','B'],
      ['47764458','GABRIELA BLANCO	','B'],
      ['47764458','GABRIELA BLANCO	','B'],
      ['49041674','EDUARDO GONZALEZ SANCHEZ	','B'],
      ['22692121','MANUEL ALBERTO BENCOMO	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['26691662','JAISI PEREZ	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['26137109','ANTHONY GABRIEL GUEVARA FERNÁNDEZ	','B'],
      ['19547135','ENRIQUETA PANASA	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['80247885','FREDDY OLIVEIRA RENGIFO	','B'],
      ['21590524','KAREM ARAQUE ALAS	','B'],
      ['22692162','MANUEL ALBERTO BENCOMO	','B'],
      ['27197669','MELI RENDON	','B'],
      ['11949892','HENRY SILVA	','B'],
      ['80247885','FREDDY OLIVEIRA RENGIFO	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['66536520','LOLYMAR QUINTERO	','B'],
      ['66536520','LOLYMAR QUINTERO	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['19620204','JEFFERLINE SILVERA	','B'],
      ['22983260','WLADIMIR PEREZ	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['23793311','YELIMBERT MORENO	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['16918161','CAROLINA GALO PÉREZ	','B'],
      ['20099411','JUNIOR RODRIGUEZ	','B'],
      ['22838158','JORGE VILCHEZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['23773921','LIBARDO FERRER COLMENARES	','B'],
      ['19991154','PABLO EMILIO GUDIÑO PEREZ	','B'],
      ['19363231','DUGLAS MOGOLLON	','B'],
      ['75245845','DANIEL LOPEZ	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['37722451','LUIS FIGUEROA PINEDA	','B'],
      ['34218321','EVELYN ALESSANDRA PARRA FRIAS	','B'],
      ['42278480','EDDY IVAN MARQUINA SEGURA	','B'],
      ['21199021','JOHN ESTIVEN ZULUAGA	','B'],
      ['18918398','YOLEISY RIOS	','B'],
      ['19003467','ANYELY LANDAETA	','B'],
      ['23739808','MAYRA NOGUERA	','B'],
      ['32083901','ERIKA OBERTO	','B'],
      ['14832540','MARIA ROJAS	','B'],
      ['16458478','GERMAN GARCIA	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['30288801','JHOSETH RODRIGUEZ RODRIGUEZ	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['27197669','MELI RENDON	','B'],
      ['20959934','YORDAN SENESI	','B'],
      ['13563724','JOSÉ SARMIENTO	','B'],
      ['27197669','MELI RENDON	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['80247881','FREDDY OLIVEIRA RENGIFO	','B'],
      ['33425168','LUIS HERRERA	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['97468630','VIVIAN FERNANDEZ	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['99773312','AUGUSTO CANALES HERRERA	','B'],
      ['22983260','WLADIMIR PEREZ	','B'],
      ['95309262','ROSA DELLEPIANE	','B'],
      ['32943438','HERNAN SUCLUPE	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['32943438','HERNAN SUCLUPE	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['15058995','YNDIRA MAURY PIÑERO	','B'],
      ['15058995','YNDIRA MAURY PIÑERO	','B'],
      ['22954493','MARTHA HERRERA	','B'],
      ['22692121','MANUEL ALBERTO BENCOMO	','B'],
      ['26838124','SHARON CACERES SANCHEZ	','B'],
      ['82180430','MAXIMO REYES SUSANO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['46533821','AUDIO JOSE GONZALEZ	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['18977396','JEAN CARLOS LARROQUE MOGOLLON	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['88248000','WILLIAM DIAZ	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['48051791','RENATTA RUESTA GUERRERO	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['17902142','GUILLERMO CAIGUA	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['18766508','YENIFER GUAICARA	','B'],
      ['61775242','KAO ANYELO HO TORRES	','B'],
      ['22983260','WLADIMIR PEREZ	','B'],
      ['18798921','LUIS TORREALBA PIMENTEL	','B'],
      ['22692194','MANUEL ALBERTO BENCOMO	','B'],
      ['27197669','MELI RENDON	','B'],
      ['21444552','STEPHANIE CISNEROS	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['11514551','MIGUELIS LUCES	','B'],
      ['15058995','YNDIRA MAURY PIÑERO	','B'],
      ['27197669','MELI RENDON	','B'],
      ['24272948','KEVERLIN HERNANDEZ	','B'],
      ['16525122','JUAN BURGOS PALOMINO	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['17442821','JOSE FRANKLIN TESEN QUINTANA	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['27197669','MELI RENDON	','B'],
      ['81694265','GUSTAVO HUAROTO MOLINA	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['41253828','HECTOR HUAMASH	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['25812341','ROSMELY CHACIN	','B'],
      ['13521919','LAURI GUTIERREZ	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
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
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['22546165','KISSBEL ESPINOZA MOLINA	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['17429192','GENIGER VELASQUEZ	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['19070274','CESAR RAMIREZ BENCONO	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['22710493','YUDIMAR DEL VALLE CORDERO CALZADILLA	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['46821775','LUIS OLAZABAL YAMUNAQUE	','B'],
      ['27231901','MARIANA GAMEZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['19052199','WILMER SANCHEZ	','B'],
      ['14620823','EMILIS ROJAS	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['21022466','JOSE GIL PERDOMO	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['13488318','WILLIAM LOPEZ	','B'],
      ['14394564','LUIS ALFREDO BLANCO HERNANDEZ	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['14678300','ELIMAR LEVEL SANCHEZ	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['70753405','GINO ABURTO VARGAS	','B'],
      ['21201084','JOSE MORON	','B'],
      ['40241718','ROMULO CUETO MALAGA	','B'],
      ['47764458','GABRIELA BLANCO	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['41135844','JUAN VALERIANO	','B'],
      ['66589223','JHON ROMERO	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['15720752','MAIRE ALVAREZ	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['26918721','FABIOLA RIVERO BECERRA	','B'],
      ['24679845','MARLY RODRIGUEZ	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['15658833','NAIBELYN PAZ DUARTE	','B'],
      ['22983260','WLADIMIR PEREZ	','B'],
      ['17103185','YOVANNY YARI	','B'],
      ['33128521','YOSBETH HERRERA HURTADO	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['16887030','NINOSKA JOXELYN OROPEZA RIVAS	','B'],
      ['20406900','EMILY YARELIS AGAMES CALDERON	','B'],
      ['32943438','HERNAN SUCLUPE	','B'],
      ['13032252','RUT CALDERA	','B'],
      ['20940956','NARCELIS CONTRERAS	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['46897292','ANA JULIA  HERNANDEZ	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['12603521','SULIMAR PARDO COBARRUBIA	','B'],
      ['74219821','JESBER CISNEROS	','B'],
      ['42278480','EDDY IVAN MARQUINA SEGURA	','B'],
      ['21310869','ADRIAN CARIPA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['13000312','AMERICO BRICEÑO	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['46533823','AUDIO JOSE GONZALEZ	','B'],
      ['17323598','WILMER SUAREZ	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['19658706','JOSE BARRIOS	','B'],
      ['22692121','MANUEL ALBERTO BENCOMO	','B'],
      ['38727721','MIGUEL BRACAMONTE	','B'],
      ['65498751','CARLOS COLO	','B'],
      ['27839484','YONAIKER RAFAEL ALVAREZ PEREZ	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['73249990','MOISES SANCHEZ	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['96558290','ALEXANDER ACOSTO	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['32866742','MELVIN REBAZA ALVARADO	','B'],
      ['20759246','WILMER RONDON	','B'],
      ['79065332','JESUS CASTILLO	','B'],
      ['13244388','JOHANNA BALLESTEROS	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['46303972','ELIAS AGUSTÍN LAYZA PAULINO	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['28178330','MARIA ANTONIETA HARO	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['18469979','WILMER DEL RISCO	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['18228925','ERNESTO CORZO	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['19233488','MARIA RIVAS	','B'],
      ['14620823','EMILIS ROJAS	','B'],
      ['17564283','DANIEL MALDONADO	','B'],
      ['18413821','ELIEZER EFRAIN CHAVEZ	','B'],
      ['13872981','ALVARO RAMIREZ	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['20213488','JOSE CHIRINO VEROES	','B'],
      ['29629783','CARLOS CHOY	','B'],
      ['24549978','EDIXON GIL TALAVERA	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['13461315','NOLMARIS DIAZ	','B'],
      ['13769101','MARIANA LANDAETA	','B'],
      ['65468778','MARYBELIS	','B'],
      ['27421313','GUSTAVO URBINA	','B'],
      ['16428381','JOEL BASTIDAS ARAUJO	','B'],
      ['93142592','JOSE GREGORIO MATHEUS	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['18918398','YOLEISY RIOS	','B'],
      ['46819223','MELODY GOMEZ SANCHEZ	','B'],
      ['15369601','MARIO MORENO RAMIREZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['17041268','MIGUEL MORAN	','B'],
      ['17902142','GUILLERMO CAIGUA	','B'],
      ['12209287','DORALIS MERCHAN	','B'],
      ['17013480','DARWUI SANCHEZ ARENAS	','B'],
      ['20759246','WILMER RONDON	','B'],
      ['89632280','GIOVANNI AGUILERA	','B'],
      ['46533821','AUDIO JOSE GONZALEZ	','B'],
      ['42971218','LISSETH RIVAS	','B'],
      ['20267380','CRISTOPHER COLMENARES	','B'],
      ['18851832','LUZDARIS MARTINEZ	','B'],
      ['16395457','PAULO DOS REIS	','B'],
      ['32917172','GLADYS CASTILLO TARAZONA	','B'],
      ['18278200','LILIANA ASTULLIDO	','B'],
      ['22546165','KISSBEL ESPINOZA MOLINA	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['17429192','GENIGER VELASQUEZ	','B'],
      ['17309803','JESUS MORENO	','B'],
      ['24498065','ALEJANDRO RODRÍGUEZ SANTANA	','B'],
      ['99701680','ANDREINA RIVAS	','B'],
      ['19070274','CESAR RAMIREZ BENCONO	','B'],
      ['25406935','ISAAC MANZANILLA	','B'],
      ['10776472','NANCY MENDOZA	','B'],
      ['22710493','YUDIMAR DEL VALLE CORDERO CALZADILLA	','B'],
      ['40832502','CRISTIAN ARANCIBIA	','B'],
      ['46821775','LUIS OLAZABAL YAMUNAQUE	','B'],
      ['27231901','MARIANA GAMEZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['19052199','WILMER SANCHEZ	','B'],
      ['14620823','EMILIS ROJAS	','B'],
      ['12692165','SELENE CHACIN	','B'],
      ['41253828','HECTOR HUAMASH	','B'],
      ['80247882','FREDDY OLIVEIRA RENGIFO	','B'],
      ['25812341','ROSMELY CHACIN	','B'],
      ['13521919','LAURI GUTIERREZ	','B'],
      ['18519162','MARIA EUGENIA GONZALEZ	','B'],
      ['42780000','ELSY BELEN	','B'],
      ['25436082','KARINA JOSEFINA CARPAVIRE	','B'],
      ['20986493','JEISSON RODRIGUEZ	','B'],
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


  public  function xmlToVenta()
  {
    set_time_limit(1200);

    // $directory = '/path/to/files';
    $directory = getTempPath(file_build_path('subida'));

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }

    $empresa = get_empresa();
    $producto = Producto::findByProCodi('S');
    $unidad = $producto->unidades->first();
    $lista = $empresa->listas->first();
    $loccodi = "001";
    $data = []; 


    foreach( $files as $file ){
      $path = getTempPath(file_build_path('subida',  $file) );
      $createXmlToVenta = new CreateVentaFromXML( $path, $empresa, User::find(1) , $lista,  $producto, $unidad, $loccodi );
      $data[] = $createXmlToVenta->handle();      
    }

    // return $data;
    dd("listo");
  }

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