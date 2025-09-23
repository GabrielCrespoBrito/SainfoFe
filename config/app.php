<?php


return [

  /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

  'name' => env('APP_NAME', 'Laravel'),


  /*
    |--------------------------------------------------------------------------
    | Adicional
    |--------------------------------------------------------------------------
    |
    | Parametros adicionales
    |
    */
  'logo_url' => env('APP_LOGO', 'https://sainfo.pe/page/images/logo.png'),

  /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

  'env' => env('APP_ENV', 'production'),


  /*
    |--------------------------------------------------------------------------
    | Token Consulta de Comprobante
    |--------------------------------------------------------------------------
    |
    |
    */

  'token_cc' => env('TOKEN_CONSULT_COMPROBANTE', null ),


  /*
    |--------------------------------------------------------------------------
    | Application Ambiente
    |--------------------------------------------------------------------------
    |
    | This value determines the "ambinete" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

  'amb' => env('APP_AMB', 'web'),


  /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

  'debug' => env('APP_DEBUG', false),

  /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

  'url' => env('APP_URL', 'http://localhost'),
  'url_base' => env('APP_URL_BASE', 'http://localhost'),
  'url' => env('APP_URL', 'http://localhost'),
  'asset_url' => env('ASSET_URL', null),


  /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

  'timezone' => 'America/Lima',

  /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

  'locale' => 'es',

  /*
    |--------------------------------------------------------------------------
    | Contraseña de administración 
    |--------------------------------------------------------------------------

    */

  'password_admin' =>  env('PASSWORD_ADMIN', 'Peru_001'),


  /*
    |--------------------------------------------------------------------------
    | Contraseña de administración 
    |--------------------------------------------------------------------------

    */

  'backups' =>  env('BACKUPS', '/var/www/data/BACKUP/web'),

  /*
   * Script de respaldo de base de datos
   */
  'backup_script' => env('BACKUP_SCRIPT', null),

  /*
    |--------------------------------------------------------------------------
    | Dias para recordar vencimiento de suscripción
    |--------------------------------------------------------------------------
    |
    | Cantidad de dias antes de la fecha de vencimiento de la suscripción 
    | para enviar una notificación sobre dicho vencimiento
    |
    */

  // Cantidad de dias ANTES del vencimiento de la suscripción para notificar al usuario
  'recordatorio_suscripcion_porvencerse' => env('DIAS_RECORDATORIO_SUSCRIPCION_PORVENCER', 15),

  // Cantidad de dias ANTES del vencimiento de la suscripción para notificar al usuario
  'recordatorio_venc_certificado' => env('DIAS_RECORDATORIO_VENC_CERTIFICADO', 15),

  // Cantidad de dias DESPUES del vencimiento de la suscripción para notificar al usuario
  'recordatorio_suscripcion_porvencida' => env('DIAS_RECORDATORIO_SUSCRIPCION_VENCIDA', 3),

  // Cantidad de dias despues del vencimiento de la suscripción cuando se eliminaran los datos de la empresa
  'dias_eliminacion_empresa' => 30,

  // Limite de items que se pueden subir por documento en el excell de importacion de ventas
  'limit_items_ventas_import' => 10,

  'terminos_condiciones' => [

    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ultricies tincidunt leo, eget consectetur odio vulputate sit amet. In hac habitasse platea dictumst. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dignissim at turpis et laoreet. Pellentesque euismod faucibus lacus eu accumsan. Praesent porttitor faucibus interdum. Suspendisse metus quam, laoreet at lorem vitae, viverra facilisis libero. Nunc non magna eget lorem mollis lacinia. Vivamus mollis risus ac feugiat scelerisque. Sed eleifend eget ante vitae fermentum. Curabitur augue odio, volutpat ac hendrerit ut, consectetur quis lorem. Proin felis metus, pellentesque vitae gravida a, aliquam luctus elit. Maecenas nec gravida neque, sit amet iaculis urna. Praesent in libero quam. Sed condimentum dictum elit in vehicula. Cras ex quam, blandit in tincidunt ac, semper et erat.',

    'Aenean laoreet a ligula ac mollis. Sed mattis leo in lectus eleifend, dapibus ornare nunc congue. Nulla ac ligula luctus, cursus nisi et, sollicitudin urna. Suspendisse at nibh dapibus, rhoncus ipsum sed, pharetra augue. Nam in augue lacus. Etiam ac molestie quam, sit amet consectetur lacus. Sed in augue vitae urna bibendum pulvinar at tincidunt dui. Proin volutpat lorem eros, vitae convallis quam iaculis ac. Ut nec lacinia odio.',

    'Maecenas pellentesque fermentum fringilla. Nunc lobortis fermentum volutpat. Curabitur nisl mi, fringilla id enim non, tristique fermentum mauris. Mauris in ullamcorper nisi, eu suscipit magna. Mauris non varius elit. Aliquam in hendrerit est, et aliquam enim. Quisque convallis mi nec fermentum varius. Suspendisse a dolor enim. Praesent ut tortor eget lorem sodales porta. Integer nec felis vel turpis consectetur dictum vitae at libero. Nullam sodales tristique ipsum. Duis placerat lacus finibus velit vestibulum pharetra. Morbi non interdum nulla, ut condimentum nisl. Integer et blandit augue. Integer sit amet metus vitae libero pharetra feugiat.',
    'Vestibulum feugiat urna eu aliquet consectetur. Sed id ultrices dui. Integer quam lacus, maximus eu elit at, fermentum ultricies nibh. Donec nec elit quis erat placerat efficitur et eget elit. Suspendisse ut tincidunt quam. Aenean a tempus erat. Vivamus eu elit vel erat euismod pharetra ultrices a libero. Quisque porttitor risus in dolor aliquam pretium. Suspendisse eget congue risus, eu porttitor lacus.',

    'In vitae ultrices nisi. Curabitur ac erat posuere, porttitor nunc ut, scelerisque neque. Vivamus eget lorem nec lectus malesuada aliquam. Aliquam eget lacus ornare, venenatis lacus in, molestie leo. Phasellus consequat egestas mattis. Donec pellentesque diam non tortor pharetra tempor. Pellentesque non metus non purus congue viverra et quis magna. Fusce eu neque sed orci congue porttitor in eget nunc. Curabitur sit amet iaculis quam. Fusce luctus aliquet ultrices. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed ullamcorper urna elementum iaculis euismod. Nunc aliquam sodales justo id faucibus. Aliquam sollicitudin nulla in neque hendrerit, ut pharetra odio tristique. Vivamus lacus dolor, placerat ut lorem vitae, pharetra auctor nisi.'
  ],


  /*
    |--------------------------------------------------------------------------
    | Ubicacion del certificado de prueba
    |--------------------------------------------------------------------------
    |
    | Ubicaciones de los certificados de prueba para los usuarios que se registren
    |
    */
  'demo_user' => [
    'username' => env('DEMO_USERNAME', 'DEMO'),
    'password' => env('DEMO_PASSWORD', 'DEMO'),
  ],





  /*
    |--------------------------------------------------------------------------
    | Ubicacion del certificado de prueba
    |--------------------------------------------------------------------------
    |
    | Ubicaciones de los certificados de prueba para los usuarios que se registren
    |
    */
  'path_cert_demo' => [
    'key' => ['static', 'cert_demo', '20604067899.key'],
    'cer' => ['static', 'cert_demo', '20604067899.cer'],
    'pfx' => ['static', 'cert_demo', '20604067899.pfx'],
  ],


  /*
    |--------------------------------------------------------------------------
    | Información para el envio de documentos con los datos de demo
    |--------------------------------------------------------------------------
    |
    */
  'usuario_sol' => 'MODDATOS',
  'clave_sol' => 'MODDATOS',
  'clave_firma' => '20876584',
  'url_busqueda_documentos' => "https://www.sainfo.pe/busquedaDocumentos",
  'url_contacto' => "https://www.sainfo.pe/contacto",

  /**
   * 
   * Imagenes
   * 
   */
  'images' => [
    'footer' => env('FOOTER_IMG', 'holamundo'),
    'footer_banner_name' => 'sainfo_banner_footer.jpg',
  ],

  /*
    |--------------------------------------------------------------------------
    | Firma digital
    |--------------------------------------------------------------------------
    |
    */
  'private_key' => [
    'path' => env('PUBLIC_KEY_PATH', ''),
  ],


  /*
    |--------------------------------------------------------------------------
    | Series por defecto con la que va a trabajar el usuario
    |--------------------------------------------------------------------------
    |
    */
  'default_series' => [
    // Factura
    ['tidcodi' => '01', 'defecto' => 1, 'sercodi' => 'F001', 'tipo' =>  'ventas'],
    // Boleta
    ['tidcodi' => '03', 'defecto' => 0, 'sercodi' => 'B001', 'tipo' =>  'ventas'],
    // NotaCredito
    ['tidcodi' => '07', 'defecto' => 0, 'sercodi' => 'F001', 'tipo' =>  'ventas'],
    // NotaCredito
    ['tidcodi' => '07', 'defecto' => 0, 'sercodi' => 'B001', 'tipo' =>  'ventas'],
    // NotaDebito
    ['tidcodi' => '08', 'defecto' => 0, 'sercodi' => 'F001', 'tipo' =>  'ventas'],
    // NotaDebito
    ['tidcodi' => '08', 'defecto' => 0, 'sercodi' => 'B001', 'tipo' =>  'ventas'],
    // Guia de remisiòn
    ['tidcodi' => '09', 'defecto' => 0, 'sercodi' => 'T001', 'tipo' =>  'guias'],
    // Guia De Transportista
    ['tidcodi' => '31', 'defecto' => 0, 'sercodi' => 'V001', 'tipo' =>  'guias'],    
    // Cotizaciòn
    ['tidcodi' => '50', 'defecto' => 0, 'sercodi' => 'P001', 'tipo' =>  'cotizaciones'],
    // Nota De Venta
    ['tidcodi' => '52', 'defecto' => 0, 'sercodi' => 'N001', 'tipo' =>  'ventas'],
    // PreVenta
    ['tidcodi' => '53', 'defecto' => 0, 'sercodi' => 'C001', 'tipo' =>  'cotizaciones'],
    // OrdenDePago
    ['tidcodi' => '98', 'defecto' => 0, 'sercodi' => 'OP01', 'tipo' =>  'cotizaciones'],
    // OrdenDeCompra
    ['tidcodi' => '99', 'defecto' => 0, 'sercodi' => 'OC01', 'tipo' =>  'cotizaciones'],


  ],

  'logos_dimenciones' => [
    'a4' => [
      'width' => env('LOGO_A4_WITH', 450),
      'height' => env('LOGO_A4_HEIGHT', 250),
    ],
    'ticket' => [
      'width' => env('LOGO_TICKET_WITH', 200),
      'height' => env('LOGO_TICKET_HEIGHT', 161),
    ],
    'subtitulo' => [
      'width' => env('LOGO_SUBTITULO_WITH', 200),
      'height' => env('LOGO_SUBTITULO_WITH', 100),
    ],
    'footer' => [
      'width' => env('LOGO_IMG_FOOTER_WITH', 550),
      'height' => env('LOGO_IMG_FOOTER_HEIGHT', 50),
    ],

    'marca_agua' => [
      'width' => env('LOGO_MARCA_AGUA_WITH', 400),
      'height' => env('LOGO_MARCA_AGUA_HEIGHT', 400),
    ],
  ],

  'path_archivos' => [
    'xml_data'    => ['{ruc_cliente}', 'documentos', 'XMLData'],
    'xml_envio'   => ['{ruc_cliente}', 'documentos', 'XMLEnvio'],
    'xml_cdr'     => ['{ruc_cliente}', 'documentos', 'XMLCDR'],
    'xml_pdf'     => ['{ruc_cliente}', 'documentos', 'XMLPDF'],
    'images'      => ['images'],
    'cert'        => ['{ruc_cliente}', 'documentos', 'XMLCert'],
  ],
  
  // Path para la importacion de xmls
  'xml_importacion' => env('XML_IMPORTACION'),

  /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

  'fallback_locale' => 'es',

  /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

  'cliente_area_adm_docs' => env('CLIENTE_AREA_ADM_DOCS', false),


  /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

  'key' => env('APP_KEY'),

  'cipher' => 'AES-256-CBC',

  /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

  'providers' => [

    /*
         * Laravel Framework Service Providers...
         */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Notifications\NotificationServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
    Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    Orchestra\Parser\XmlServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    Chumper\Zipper\ZipperServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    // Datatable Yajra plugin 
    Yajra\DataTables\DataTablesServiceProvider::class,


  ],

  /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

  'aliases' => [

    'App' => Illuminate\Support\Facades\App::class,
    'Artisan' => Illuminate\Support\Facades\Artisan::class,
    'Auth' => Illuminate\Support\Facades\Auth::class,
    'Blade' => Illuminate\Support\Facades\Blade::class,
    'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
    'Bus' => Illuminate\Support\Facades\Bus::class,
    'Cache' => Illuminate\Support\Facades\Cache::class,
    'Config' => Illuminate\Support\Facades\Config::class,
    'Cookie' => Illuminate\Support\Facades\Cookie::class,
    'Crypt' => Illuminate\Support\Facades\Crypt::class,
    'DB' => Illuminate\Support\Facades\DB::class,
    'Eloquent' => Illuminate\Database\Eloquent\Model::class,
    'Event' => Illuminate\Support\Facades\Event::class,
    'File' => Illuminate\Support\Facades\File::class,
    'Gate' => Illuminate\Support\Facades\Gate::class,
    'Hash' => Illuminate\Support\Facades\Hash::class,
    'Lang' => Illuminate\Support\Facades\Lang::class,
    'Log' => Illuminate\Support\Facades\Log::class,
    'Mail' => Illuminate\Support\Facades\Mail::class,
    'Notification' => Illuminate\Support\Facades\Notification::class,
    'Password' => Illuminate\Support\Facades\Password::class,
    'Queue' => Illuminate\Support\Facades\Queue::class,
    'Redirect' => Illuminate\Support\Facades\Redirect::class,
    'Redis' => Illuminate\Support\Facades\Redis::class,
    'Request' => Illuminate\Support\Facades\Request::class,
    'Response' => Illuminate\Support\Facades\Response::class,
    'Route' => Illuminate\Support\Facades\Route::class,
    'Schema' => Illuminate\Support\Facades\Schema::class,
    'Session' => Illuminate\Support\Facades\Session::class,
    'Storage' => Illuminate\Support\Facades\Storage::class,
    'URL' => Illuminate\Support\Facades\URL::class,
    'Validator' => Illuminate\Support\Facades\Validator::class,
    'View' => Illuminate\Support\Facades\View::class,
    'Form' => Collective\Html\FormFacade::class,
    'Html' => Collective\Html\HtmlFacade::class,
    'PDF' => Barryvdh\DomPDF\Facade::class,
    'XmlParser' => Orchestra\Parser\Xml\Facade::class,
    'Zipper' => Chumper\Zipper\Zipper::class,
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    'DataTables' => Yajra\DataTables\Facades\DataTables::class,
  ],


  /*
    |--------------------------------------------------------------------------
    | Application Parameters
    |--------------------------------------------------------------------------
    |
    | Valores del sistema 
    |
    */

  /* @TODO- estos parametros ponerlos tambien configuración env()    */

  'parametros' => [
    // IGV hasta el 2022-07-20, para documentos(ventas) anteriores que no tengan el igv guardado en su tabla principal
    'igv_antiguo' => 18,
    'igv' => 18,
    'igv_bace_cero' => 0.18,
    'igv_bace_uno' => 1.18,
    'bolsa' => 0.30,
  ],

  'dias_anulacion' => env('DIAS_ANULACION', 30),
  // ------------
  'dias_envio' => [
    '01' => env('DIAS_ENVIO_FACTURA', 3),
    '03' => env('DIAS_ENVIO_BOLETA', 5),
    '07' => env('DIAS_ENVIO_NC', 5),
    '08' => env('DIAS_ENVIO_ND', 5),
  ],

  'mail' => [
    'pagos' =>  env('CORREO_CONTACTO_PAGOS', 'soporte@sainfo.pe'),
  ],

  'phones' => [
    'contacto' => env('TELEFONO_CONTACTO_SOPORTE', '998840052'),
  ],

  'cuentas' => [
    'bbva' => [
      'regular' => '0011-0137-71-0100035710',
      'interbamcaria' => 'CCI: 011-137-000100035710-71',
    ]
  ],

  'password_delete_empresa' => env('EMPRESA_BORRADO_PASSWORD', "79@xGocCD5UhZLg7U") ,

  "aws_url_bucket" => env('AWS_URL_ABSOLUTE_BUCKET', null),



  'reporte_mas_vendido_dias_limite' => env('REPORTE_MAS_VENDIDOS_DIAS', 31),
  'reporte_utilidades_dias_limite' => env('REPORTE_UTILIDADES_DIAS', 31),
  'reporte_vendedor_dias_limite' => env('REPORTE_VENDEDOR_DIAS', 31),
  'reporte_mejores_clientes_dias_limite' => env('REPORTE_MEJORES_CLIENTES_DIAS', 31),

  /*
    |--------------------------------------------------------------------------
    | Invalid Subdomains
    |--------------------------------------------------------------------------
    |
    | This list contains subdomains that are either unwanted or conflict
    | with a system value.
    |
    */

  'invalid_subdomains' => [
    'admin',
    'administrator',
    'api',
    'app',
    'controlpanel',
    'cp',
    'custom_domain',
    'custom_subdomain',
    'dns',
    'facebook',
    'hooks',
    'hostmaster',
    'linkedin',
    'mail',
    'microsoft',
    'node',
    'nodestatus',
    'ns',
    'pagestatus',
    'panel',
    'pinterest',
    'point',
    'pointdns',
    'root',
    'self',
    'service',
    'servicestatus',
    'sitestatus',
    'staging',
    'test',
    'twitter',
    'update',
    'updates',
    'webadmin',
    'webhooks',
    'webmail',
    'webmaster',
    'webnode',
    'webstatus',
    'worskpaces',
    'www',
    'www2'
  ],


  
  'google_captcha' => [
    'key' => env('CAPTCHA_KEY', null ),
    'secret' => env('CAPTCHA_SECRET', null),
  ]    
];