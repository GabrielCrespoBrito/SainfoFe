<?php 

namespace App\Helpers;

use Sunat\Sunat;

class ConsultarDocumento {

  public $documento;
  public $isRuc;
  public $isSearchByRucComPe;
  public $format = [
    'razon_social' => '',
    'nombre_comercial' => '',
    'ubigeo' => '',
    'direccion' => '',
    'telefono' => '',
    'email' => '',    
    'success' => true,
    'error' => '',
    'code' => 200
  ];

  public function __construct( $documento , $isRuc = true ){
    $this->documento = $documento;
    $this->isRuc = $isRuc; 
    $this->isSearchByRucComPe = get_setting('servicios_busqueda_documento') == "ruc.com.pe";
  }

  # Servicios de busqueda
  public function searchByRucComPe()
  {
    $token=  get_setting('token_search_documento');
    $ruta =  get_setting('ruta_search_documento');
    $data = [ "token" => $token,  "ruc"   => $this->documento ];
    $data_json = json_encode($data);
    // return ($data_json);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta  = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta, true);    
  }

  public function searchBySunat(){
    $sunat = new Sunat();
    return $sunat->getDataRUC($this->documento);    
  }

  public function searchRuc()
  {
    return $this->isSearchByRucComPe ? 
    $this->searchByRucComPe() : $this->searchBySunat();
  }


  public function searchDniPeru()
  {
    $nombres = '';
    $apellidos = '';
    $success = true;
    $error = '';

    $client = new \GuzzleHttp\Client();
    $options = [
      'headers' => ['Content-type' => 'application/json'],
    ];

    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZvbnNlY2Fid2FAZ21haWwuY29tIn0.auWnUxfMLXJqOXBsohtAh34mphnq3NIbNMUjMGuzKsQ";

    $url =  sprintf("https://dniruc.apisperu.com/api/v1/dni/%s?token=%s", $this->documento , $token );;

    try {
      $res = $client->get($url, $options);
    } catch (\Throwable $th) {
      $success = false;
      $error = 'No se encontrol el DNI o hubo un problema con la busqueda';      
    }

    if( $success ){
      $dataPersona = json_decode( $res->getBody()->getContents());
      $nombres = $dataPersona->nombres;
      $apellidos =  $dataPersona->apellidoPaterno . ' ' . $dataPersona->apellidoMaterno;
    }


    return [
      'nombres' => $nombres,
      'apellidos' => $apellidos,
      'success' => $success,
      'error' => $error,
    ];


    //
  }


  public function searchDniOpti()
  {
    $nombres = '';
    $apellidos = '';
    $success = true;
    $error = '';
    $client = new \GuzzleHttp\Client();

    $options = [
      'headers' => [ 'Content-type' => 'application/json' ],
    ];


    $url =  "https://dni.optimizeperu.com/api/persons/" . $this->documento;
    $res = $client->get( $url , $options );
    
    if( $res->getStatusCode() != 200 ){
      $success = false;
      $error = 'Error en la busqueda';
    }
    else {
      $response = json_decode( $res->getBody()->getContents());

      if( is_null( $response ) ){
        $success = false;
        $error = 'El DNI consultado no existe, por favor verificar';
      }

      else if( is_object($response) ){
        if(property_exists($response ,'dni')){
          $nombres = $response->name;
          $paterno = $response->first_name;
          $materno = $response->last_name;
          $apellidos = $paterno . ' ' . $materno;
        }
        else {
          $success = false;
          $error = 'El DNI consultado no existe, por favor verificar';
        }
      }
    }
    
    return [
      'nombres' => $nombres,
      'apellidos' => $apellidos,
      'success' => $success,
      'error' => $error,
    ];
  }

  public function searchDni()
  {
    $nombres = '';
    $apellidos = '';
    $success = true;
    $error = '';
    
    $client = new \GuzzleHttp\Client();

    $options = [
      'headers' => [ 'Content-type' => 'application/x-www-form-urlencoded' ],
      'form_params' => [
        'token' => "tutorialesexcel.com",
        'dni' => $this->documento ,
      ],
    ];


    $url = 'http://luisrojas.hol.es/2ren/tutorialesexcel.php';
    $res = $client->post( $url , $options );
    $response = strtoupper($res->getBody()->getContents());
    $content = explode('|',  $response);

    // dd($response);
    // dd( $content );

    if(strstr( $response, "SUCCESS TRUE") === false ){
      $success = false;
      $error = $content[0];
    }
    else {

      // Nombrees
      $nombres = explode( " ", $content[3]);
      $nombres = end($nombres);

      $paternoArr = explode(" ", $content[4]);
      $paterno = end($paternoArr);
      $maternoArr = explode(" ", $content[5]);
      $materno = end($maternoArr);
      $apellidos = $paterno . ' ' . $materno;
    }
    
    return [
      'nombres' => $nombres,
      'apellidos' => $apellidos,
      'success' => $success,
      'error' => $error,
    ];

  }


  public function setFormat($info){

    $format = $this->format;

    // rucs
    if( $this->isRuc ){

      // servicio de search.com
      if( $this->isSearchByRucComPe ){
        if( isset($info['error']) ){
          $format['success'] = false;
          $format['error'] = "No se pudo encontrar la información, por favor escriba manualmente los datos";
          $format['code'] = 400;        
        }

      }

      // servicio sunat
      else {        
        if( $info == false ){            
          $format['success'] = false;
          $format['code'] = 400;        
          $format['error'] = "No se encuentra este RUC";
        }
      }



      if( $format['success'] ){
        $searchRucCom = $this->isSearchByRucComPe;       
        $format["razon_social"] = $searchRucCom ? $info['razon_social'] : $info['RazonSocial'];
        $format["nombre_comercial"] = $searchRucCom ? $info['nombre_comercial'] : $info['NombreComercial'];
        $format["direccion"] = $searchRucCom ? $info['direccion'] : $info['direccion'];
        $format["ubigeo"] = $searchRucCom ? $info['ubigeo'] : '';
      }

    }

    // dnis
    else {

      if( $info['error'] ){
        $format["success"] = false;   
        $format["error"] = $info['error'];                     
      }
      else {
        $format["razon_social"] = $info['nombres']  . ' ' . $info['apellidos'];        
      }

    }

    return $format;
  }



  public function search()
  {
    // $info = $this->isRuc ? $this->searchRuc() : $this->searchDni(); searchDniOpti
    $info = $this->isRuc ? $this->searchRuc() : $this->searchDniPeru();
    return $this->setFormat($info);
  }


  
  
}