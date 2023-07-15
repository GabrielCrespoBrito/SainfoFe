<?php

namespace App;

use App\Jobs\Setting\GetCurrentTipoCambioSunat;
use App\Jobs\Setting\GetTipoCambioSunat;
use App\Repositories\SettingSystemRepository;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class SettingSystem extends Model
{
  public $table = 'settings_system';
  use UsesSystemConnection;
  public $timestamps = false;
  public $fillable = ['name','value'];

  const CAMPO_CONFIGURACION_IGV = "configuracion_igv";


  public static function getAll()
  {

    return [
      [
        'name' => 'nombre',
        'value' => 'SAINFO',
      ],

      [
        'name' => 'video_presentacion_contabilidad',
        'value' => 'https://www.youtube.com/watch?v=KFVUxSynSXc&ab_channel=productpage_video',
      ],


      // [
        // 'name' => 'settings_api',
        // 'value' => ['scope' => ] ,
      // ],

      // scope


      [
        'name' => 'link_whatapp_contact_contact',
        'value' => 'https//wa.link/hkzbnl',
      ],

      [
        'name' => 'ose',
        'value' => 0,
      ],

      // Habilitar el registro de usuarios
      [
        'name' => 'register_user',
        'value' => 0,
      ],

      // Nombre de la base de datos de la empresa
      [
        'name' => 'DB_DATABASE_CUSTOM',
        'value' => '',
      ],

      [
        'name' => 'mysqldump_path',
        'value' => 'C:\"Program Files"\MySQL\"MySQL Server 5.7"\bin\mysqldump',
      ],

      // Ubicación y/o comando para ejecutar el respaldo en RAR
      [
        'name' => 'path_rar',
        'value' => 'C:\"Program Files (x86)"\WinRAR\WinRAR.exe',
      ],


      [
        'name' => 'binary_path',
        'value' => 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe',
      ],

      [
        'name' => 'dia_respaldo',
        'value' => '1',
      ],

      [
        'name' => 'hora_respaldo',
        'value' => '10:14',
      ],

      [
        'name' => 'servicios_busqueda_documento',
        'value' => '1',
      ],


      // Nombre del sistema
      [
        'name' => 'nombre',
        'value' => 'Sainfo',
      ],
      [
        'name' => 'sistema_email',
        'value' => 'fonsecabwa@gmail.com',
      ],
      [
        'name' => 'sistema_password',
        'value' => 'fonblas12',
      ],
      [
        'name' => 'is_online',
        'value' => '0',
      ],
      [
        'name' => 'save_amazon',
        'descripcion' => 'indicar si se quiere guardar en amazon - (Valores 0/1)',
        'value' => '1',
      ],
      [
        'name' => 'data_path',
        'value' => 'XMLData',
      ],
      [
        'name' => 'envio_path',
        'value' => 'XMLEnvio',
      ],

      [
        'name' => 'cdr_path',
        'value' => 'XMLCDR',
      ],
      [
        'name' => 'cert_path',
        'value' => 'XMLCert',
      ],
      
      [
        'name' => 'pdf_path',
        'value' => 'XMLPDF',
      ],

      [
        'name' => 'data_path_amazon',
        'value' => '{ruc_cliente}\documentos\XMLData',
      ],

      [
        'name' => 'envio_path_amazon',
        'value' => '{ruc_cliente}\documentos\XMLEnvio',
      ],

      [
        'name' => 'img_path',
        'value' => 'image',
      ],

      [
        'name' => 'cdr_path_amazon',
        'value' => '{ruc_cliente}\documentos\XMLCDR',
      ],

      [
        'name' => 'cert_path_amazon',
        'value' => '{ruc_cliente}\documentos\XMLCert',
      ],

      [
        'name' => 'pdf_path_amazon',
        'value' => '{ruc_cliente}\documentos\XMLPDF',
      ],

      [
        'name' => 'token_search_documento',
        'value' => '224e92e6-bf05-43ea-9726-6549a1b0541f-76162889-a24c-44f7-aaf7-4f33487429a6',
      ],

      [
        'name' => 'ruta_search_documento',
        'value' => 'https://ruc.com.pe/api/v1/ruc',
      ],

      [
        'name' => 'carpeta_guardado',
        'value' => 'D:\CCCSainfoNet_Fe_FSol\\',
      ],

      [
        'name' => 'instalacion_end',
        'value' => false,
      ],

      [
        'name' => 'url_terminos_condiciones',
        'value' => "#",
      ],

      [
        'name' => 'url_politica_privacidad',
        'value' => "#",
      ],

      [
        'name' => 'limite_codigo_verificacion_telefono',
        'value' => 2,
      ],

      [
        'name' => 'limite_codigo_verificacion_telefono',
        'value' => 2,
      ],      

      [
        'name' => 'html_contacto',
        'value' => '<p> <span class="fa fa-phone "></span> Números de telefono </p>
        <div class="form-group has-feedback">
          <p class="form-control"> Soporte +51 000 000 000 <span class="fa fa-whatsapp"></span> </p>
        </div>
        <div class="form-group has-feedback">
          <p class="form-control"> Ventas: +51 000 000 000  <span class="fa fa-whatsapp"></span> </p>
        </div>
        <p> <span class="fa fa-envelope "></span> Corres electronicos </p>
        <div class="form-group has-feedback">
          <p class="form-control"> ventas@sainfo.pe  </p>
        </div>
        <div class="form-group has-feedback">
          <p class="form-control"> soporte@sainfo.com  </p>
        </div>',
      ],

      [
        'name' => 'dias_envio_documento_01',
        'value' => 3,
      ],

      [
        'name' => 'dias_envio_documento_03',
        'value' => 5,
      ],

      [
        'name' => 'dias_envio_documento_07',
        'value' => 5,
      ],

      [
        'name' => 'dias_envio_documento_08',
        'value' => 5,
      ],

      [
        'name' => 'dias_envio_documento_09',
        'value' => 9999,
      ],

      [
        'name' => 'dias_envio_documento_anulacion',
        'value' => 30,
      ],
      // Configuracion de igv
      [
        'name' => 'configuracion_igv',
        'value' => json_encode([
          [ 'codigo' => 'regular' , 'descripcion' => 'Regular 18%', 'porc' => 18 ],
          [ 'codigo' => 'restaurantes', 'descripcion' => 'Ley 31556 (IGV 10%) - Ley Micro y Pequeñas empresas en los rubros de restaurantes, hoteles y alojamientos turisticos - Válido desde el 01/09/2022 hasta el 31/12/2024', 'porc' => 10 ],
        ]),
      ],
    ];
  }


  public static function registerNews()
  {
    $registers = SettingSystem::getAll();

    foreach ($registers as $register)
    {
      SettingSystem::firstOrcreate(
        ['name' => $register['name']],
        ['name' => $register['name'], 'value' => $register['value']]
      );
    }

    cache()->forget('settings');
  }
    
  
  const SELECTS = [

    'servicios_busqueda_documento' => [
      'values' => [ 'ruc_com' => 'ruc.com.pe', 'busqueda_sunat' => 'busqueda Sunat' ] ],
      
      'is_online' => [
        'values' => [ 'Si' =>  '1',  'No' => '0'] 
      ],
      
      'register_user' => [
        'values' => [ 'Si' =>  '1',  'No' => '0'] 
      ],

      'save_amazon' => [
        'values' => [ 'Si' =>  '1',  'No' => '0'] 
      ],

      'ose' => [
        'values' => [ 'Si' =>  '1',  'No' => '0'] 
      ],      

      'dia_respaldo'  => [
        'values'      => [ 
          'Lunes'     => '1',
          'Martes'    => '2',
          'Miercoles' => '3',
          'Jueves'    => '4',
          'Viernes'   => '5',
          'Sabado'    => '6',
          'Domingo'   => '7'
        ] 
      ], 

  ];


  public static function createMultiple(Array $records)
  {
    for ( $i = 0; $i < count($records) ; $i++) {
      $record = $records[$i];
      $data = [
        'name' => $record[0],
        'value' => $record[1],
        'descripcion' => isset($record[2]) ? $record[2] : ''
      ];
      self::create($data);
    }
  }

  public function isSelect(){
    if( array_key_exists( $this->name , self::SELECTS ) ){
      return self::SELECTS[$this->name];
    }
    return false;
  }


  
  public static function getCurrentTCSunat()
  {
    $tipocambio = new  GetCurrentTipoCambioSunat();

    return $tipocambio->consult();
  }

  public function repository()
  {
    return new SettingSystemRepository( $this );
  }

  public static function getIgvOpciones()
  {
    return json_decode(self::where('name', self::CAMPO_CONFIGURACION_IGV)->first()->value);
  }

}
