<?php

namespace App;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DBHelp 
{
  public $clean_reporte = true;
  public $reporte = ["Existen todas las tablas y columnas necesarias"];

  public static function TT( $table_name, $empcodi = null ){
    $empcodi = $empcodi ?? empcodi();

    \DB::connection('tenant')->table($table_name)
    ->where('empcodi', $empcodi )
    ->delete();
  }
  
  public function add_to_reporte( $table , $columna = false , $creada = true )
  {
    if($creada){
      $info = "Se ha creado La tabla {$table}";      
    }
    else {
      $info = "Se ha agregado la columna {$columna} de la tabla {$table}";
    }

    if( $this->clean_reporte ){
      $this->reporte = [];
      $this->clean_reporte = false;
    }

    array_push( $this->reporte , $info );
  }

  public static function fixedId( $model, $column = "column_name", $ceros = 3 )
  {
    if( $model->count() ) {
      foreach( $model->all() as $m ){
        $value_fixed  = agregar_ceros( $m->{$column} , $ceros , 0 );        
        $m->{$column} = $value_fixed;
        $m->save();
      }
    }
  }


  public function add_empcodi( $table , $column = "empcodi"  , $setPrimaryKey  = false, $primaryKeys = [] )
  {
    if ( !Schema::hasColumn($table , "empcodi") && !Schema::hasColumn($table,"EmpCodi") ){
      Schema::table( $table , function (Blueprint $table) use($column,$setPrimaryKey,$primaryKeys)  {
        $table->string($column, 3)->default("001");
        if($setPrimaryKey){ 
          $table->dropPrimary();
          $table->primary( (array) $primaryKeys); 
        }
      });
      $this->add_to_reporte( $table , $column  , false );
    }
  }

  public static function deletePermisosTable()
  {
    Schema::dropIfExists('role_has_permissions');
    Schema::dropIfExists('model_has_roles');
    Schema::dropIfExists('model_has_permissions');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('roles');
  }


  // Tabla creada para guardar los datos almacenados del sistema
  public function check_settings_system_table($table = "settings_system")
  {
    if (!Schema::hasTable($table)) {
      Schema::create($table, function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('descripcion')->nullable();
        $table->longText('value');
      });
      $this->add_to_reporte($table);
      \Artisan::call('db:seed', [
        '--class' => "SettingsTableSeeder"
      ]);
    }
  }



  public function check_ventas_consulta_sunat($table = "ventas_consultas_sunat")
  {
    if( !Schema::hasTable($table) ){
      Schema::create($table, function (Blueprint $table) {
        $table->increments("id");
        $table->string("VtaOper");
        $table->string("CodiSunat");
        $table->string("Descripcion");
        $table->string("User_Crea")->nullable();        
        $table->string("User_FCrea")->nullable();        
        $table->string("User_Modi")->nullable();        
        $table->string("User_FModi")->nullable();
        $table->string("EmpCodi",4)->nullable();        
      });
    $this->add_to_reporte($table);
    }
  }



  public function check_contingencias_cab($table = "contingencias_cab")
  {
    if( !Schema::hasTable($table) ){
      Schema::create($table, function (Blueprint $table){
        $table->increments("id");
        $table->string("empcodi",4);
        $table->string("panano")->nullable();
        $table->string("panperi")->nullable();
        $table->string("mescodi")->nullable();
        $table->string("docnume")->nullable();
        $table->string("ticket")->nullable();
        $table->date("fecha_documento");
        $table->dateTime("fecha_emision");
      });
    $this->add_to_reporte($table);
    }
  }

  public function check_contingencias_detalles($table = "contingencias_detalles")
  {
    if( !Schema::hasTable($table) ){
      Schema::create($table, function (Blueprint $table){
        $table->increments("id");
        $table->string("empcodi",4);        
        $table->integer("con_id");
        $table->string("vtaoper");
        $table->string("tidcodi");
        $table->string("serie");
        $table->string("numero");
        $table->integer("motivo_id")->nullable();
        $table->string("gravada");
        $table->string("exonerada");
        $table->string("inafecta");
        $table->string("igv");
        $table->string("isc");
        $table->string("total");
        $table->string("tidcodi_ref")->nullable();
        $table->string("serie_ref")->nullable();
        $table->string("numero_ref")->nullable();
      });
    $this->add_to_reporte($table);
    }
  }

  public function check_contingencias_motivos($table = "contingencias_motivos")
  {
    if( !Schema::hasTable($table) ){
      Schema::create($table, function (Blueprint $table){
        $table->increments("id");
        $table->string("descripcion");
        $table->integer("cod_sunat");
      });

      $this->add_to_reporte($table);

      \Artisan::call('db:seed', [
        '--class' => "ContingenciaMotivoSeeder"
      ]);
    }
  }


 public function check_prov_clientes_table($table = "prov_clientes", $column = "PCDocu")
  {

    if( !Schema::hasColumn($table,$column) ){
      Schema::table($table, function (Blueprint $table)use($column) {
        $table->string($column)->nullable();
      });
    $this->add_to_reporte($table, $column);
    }
  }

  public function check_actividad_clientes_table($table = "actividad_clientes")
  {
    if( !Schema::hasTable('actividad_clientes') ){
      Schema::create($table, function ( Blueprint $table) {
        $table->increments('id');          
        $table->string( 'PCCodi');
        $table->string( 'Descripcion' )->nullable();
        $table->dateTime( 'Fecha' );
        $table->string( 'Model_id' );
        $table->string( 'Model_name' );
        $table->string( 'empcodi' );      
      });
    $this->add_to_reporte($table);
    }
  }

  public function check_empresa_code($table = "opciones", $column = "active")
  {
    // Si esta la empresa activa o no
    if (!Schema::hasColumn( $table , 'active' )){
      Schema::table( $table , function (Blueprint $table){
        $table->integer('active')->default("1");
        $this->add_to_reporte( $table , 'active'  , false );
      });
    }

    // Poner el fe_servicio
    if (!Schema::hasColumn($table, 'fe_servicio')) {
      Schema::table($table, function (Blueprint $table) {
        $table
        ->integer('fe_servicio')
        ->default("1")
        ->after("fe_version");
      });
      $this->add_to_reporte( $table , 'fe_servicio'  , false );
    }

  // Ponerle el fe_ambiente
    if (!Schema::hasColumn($table, 'fe_ambiente')) {
      Schema::table($table, function (Blueprint $table) {
        $table
        ->integer('fe_ambiente')
        ->after("fe_servicio")
        ->default("1");
      });
      $this->add_to_reporte( $table , 'fe_ambiente'  , false );
    }
  }

  public function check_produtos_table($table = 'productos', $column = "profoto2")
  {
    if (!Schema::hasColumn( $table , $column )){
      Schema::table( $table , function (Blueprint $table) use($column)  {
        $table->string($column)->nullable();
      });      
      $this->add_to_reporte( $table , $column  , false );
    } 
  }


  public function check_unidad_table($table = "unidad" , $column = "LisCodi")
  {
    if (!Schema::hasColumn( $table , $column )){
      Schema::table( $table , function (Blueprint $table) use($column)  {
        $table->string($column)->nullable()->default(10);
      });      
      $this->add_to_reporte( $table , $column  , false );
    }
  }

  public function check_empcodi_to_tables_table()
  {
    // -------------------------------------------------------------------    
    $this->add_empcodi("grupos");
    $this->add_empcodi("guia_detalle");
    $this->add_empcodi("usuario_local");
    $this->add_empcodi("lista_precio");
    $this->add_empcodi("familias");
    $this->add_empcodi("condicion");    
    $this->add_empcodi("marca");
    $this->add_empcodi("ventas_detalle", 'EmpCodi');    
    $this->add_empcodi("productos");
    $this->add_empcodi("vehiculo");
    $this->add_empcodi("unidad");
    $this->add_empcodi("condicion_cpra_vta");    
    $this->add_empcodi("vendedores");    
    $this->add_empcodi("caja_detalle");    
    $this->add_empcodi("ingresos" , 'EmpCodi' , ['IngCodi', 'EmpCodi'] );
    $this->add_empcodi("egresos" , 'EmpCodi'  , ['EgrCodi', 'EmpCodi'] );
    $this->add_empcodi("opciones_emp",'EmpCodi');
    $this->add_empcodi("bancos_cuenta_cte",'EmpCodi');    
    $this->add_empcodi("local","EmpCodi");
    $this->add_empcodi("cotizaciones_detalle","EmpCodi");
    $this->add_empcodi("tcmoneda", 'empcodi' , true , ['TipCodi','TipFech', 'empcodi']);
    
    // -------------------------------------------------------------------    
  }


  // Tabla creada para guardar los datos almacenados del sistema
  public function check_lista_precio_table($table = "lista_precio", $column = "LocCodi")
  {
    if (!Schema::hasColumn($table, $column)) {

      Schema::table($table, function (Blueprint $table) use ($column) {
        $table->string($column)->default('001')->nullable();
      });

      $this->add_to_reporte($table, $column, false);
    }
  }
 
  // Tabla creada para guardar los datos almacenados del sistema
  public function check_empresa_transporte_table($table = "empresa_transporte", $column = "empresa_id")
  {
    if (!Schema::hasColumn($table, $column)) {

      Schema::table($table, function (Blueprint $table) use($column){
        $table->string($column)->default(empcodi())->nullable();
      });
      
      $this->add_to_reporte($table, $column,false);
    }
  }


  // Tabla creada para guardar los datos almacenados del sistema
  public function check_usuario_documento_table($table = "usuario_documento" , $column = "contingencia")
  {      
    if (!Schema::hasColumn($table , $column ) ) {
      Schema::table($table, function ( Blueprint $table) {
        $table->boolean('contingencia')->default(0);
      });
      $this->add_to_reporte($table, $column , false);

    }
  }

  // Tabla creada para guardar los datos almacenados del sistema
  public function check_opciones_url_table($table = "opciones_url" , $column = "sunat")
  {      
    if (!Schema::hasColumn($table , $column ) ) {
      Schema::table($table, function ( Blueprint $table) {
        $table->boolean('sunat')->default(1);
      });
      $this->add_to_reporte($table, $column , false);

    }
  }


  // Tabla creada para guardar los datos almacenados del sistema
  public function check_vendedores_table($table = "vendedores", $field = "defecto")
  {      
    if (!Schema::hasColumn( $table , $field ) ) {
      Schema::table($table, function ( Blueprint $table) use ($field) {
        $table->boolean($field)->default(0);
      });
      $this->add_to_reporte($table, $field , false);
    }
  }


  // Tabla creada para guardar los datos almacenados del sistema
  public function check_cotizaciones_table($table = "cotizaciones", $field = "TidCodi1")
  {      
    if (!Schema::hasColumn( $table , $field ) ) {
      Schema::table($table, function ( Blueprint $table) use ($field) {
        $table->string($field)->default(50);
      });
      $this->add_to_reporte($table, $field);
    }
  }




  // Tabla creada para guardar los datos almacenados del sistema
  public function check_table_contratos($table = "contratas")
  {
    if (!Schema::hasTable($table) ) {
      Schema::create($table, function ( Blueprint $table) {
        $table->increments('id');
        $table->string( 'nombre');
        $table->longText('contenido')->nullable();
        $table->boolean('publicar')->default(0);
        $table->timestamps();
      });      
      $this->add_to_reporte($table, false);
    }

    if (!Schema::hasTable( "contratas_entidad" ) ) {
      Schema::create("contratas_entidad", function ( Blueprint $table) {
        $table->increments('id');
        $table->string('entidad_type');
        $table->string('entidad_id');
        $table->integer('contrata_id')->nullable();
        $table->longText('contenido')->nullable();
        $table->string('EmpCodi',3);        
        $table->timestamps();
      });      
      $this->add_to_reporte("contratas_entidad", false);
    }

    if (!Schema::hasColumn( "contratas_entidad" , "fecha_inicio" ) ) {
      Schema::table("contratas_entidad", function ( Blueprint $table) {
        $table->date('fecha_emision')->nullable();        
        $table->date('fecha_inicio')->nullable();
        $table->date('fecha_final')->nullable();
      });      
      $this->add_to_reporte("contratas_entidad", "fecha_inicio" );
    }

  }


  public function check_documentos_pendientes_detalle($table = "documentos_pendientes_detalle" )
  {
    if (!Schema::hasTable($table) ) {    
      Schema::create($table, function ( Blueprint $table) {
        $table->increments('id');          
        $table->integer('id_documento_pendiente')->unsigned();
        $table->foreign('id_documento_pendiente')->on($table)->references('id');
        $table->string('VtaOper');
        $table->string('EmpCodi');
        $table->integer('VtaNume');
      });   
      $this->add_to_reporte($table, false);
    }

  }

  // Tabla GuiaCab
  public function check_guia_cab( $table = "guias_cab" )
  {
    // Si el campo fe_rpta en la GuiaCab
    if(!Schema::hasColumn($table,'fe_rpta')){
      Schema::table("guias_cab", function (Blueprint $table) {
        $table->integer('GuiXML')->default('0');
        $table->integer('GuiPDF')->default('0');
        $table->integer('GuiCDR')->default('0');
        $table->integer('GuiMail')->default('0');
        $table->integer('fe_rpta')->default('9');
        $table->string('fe_firma')->nullable();
      });
    }
    if(!Schema::hasColumn($table,'fe_obse')){
      Schema::table("guias_cab", function (Blueprint $table) {
        $table->string('fe_obse')->nullable();
      });
      $this->add_to_reporte($table, 'fe_obse' , false);
    }

    if(!Schema::hasColumn($table,'TidCodi')){
      Schema::table("guias_cab", function (Blueprint $table) {
        $table->string('TidCodi',2)->after('vtaoper')->nullable();
      });
      $this->add_to_reporte($table, 'TidCodi' , false);      
    }     

  }


  // Tabla de ver los documentos pendientes
  public function check_documentos_pendientes()
  {
    // Si no existe la tabla
    if (!Schema::hasTable("documentos_pendientes")) {
      Schema::create("documentos_pendientes", function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('EmpCodi');
        $table->enum('tipo_documento', ['factura' , 'boleta']);
        $table->integer('cantidad');
      });
      $this->add_to_reporte('documentos_pendientes');
    }
    // Si no existe el campo lapso
    if (!Schema::hasColumn("documentos_pendientes",'lapso')) {
      Schema::table("documentos_pendientes", function (Blueprint $table) {
        $table->string('lapso')->default('diario');
      });      
      $this->add_to_reporte('documentos_pendientes' , 'lapso' , false);
      // Si existe el campo laptop (equivocado, quitarlo)
      if( Schema::hasColumn('documentos_pendientes','laptop') ){
        Schema::table("documentos_pendientes", function (Blueprint $table) {
          $table->dropColumn('laptop');
        });              
      }
    }
  }



  public function check_demoInfoSeeder(){
    // \Artisan::call('db:seed', [
    //   '--class' => "DemoInfoSeeder"
    // ]);
  }

  public function check_cotizaciones_detalle_table($table = "cotizaciones_detalle", $column = "id")
  {
    if (Schema::hasColumn($table, $column)) {
      Schema::table($table, function (Blueprint $table) use ( $column ) {
        $table->dropColumn('id');
      });      
    }
  }

  // Tabla ventas nube
  public function check_ventas_nube()
  {
    if (Schema::hasTable('ventas_nube')) {
      // Si no existe la columna Estatus en la tabla ventas_nube
      if (!Schema::hasColumn('ventas_nube','Estatus')){
        Schema::table('ventas_nube', function ( Blueprint $table) {
          $table->integer('Estatus')->default("0");
        });
        $this->add_to_reporte('ventas_nube', 'Estatus' , false);
      }
    }

    else {
      Schema::create('ventas_nube', function ( Blueprint $table) {
        $table->increments('id');
        $table->string('VtaOper');
        $table->integer('XML')->default("0");
        $table->integer('PDF')->default("0");
        $table->integer('CDR')->default("0");
        $table->integer('Estatus')->default("0");
      });
      $this->add_to_reporte('ventas_nube');
    }
  }

  // Tabla ventas nube
  public function check_ventas_amazon()
  {
    if (!Schema::hasTable('ventas_amazon')) {
      Schema::create('ventas_amazon', function ( Blueprint $table) {
        $table->increments('id');
        $table->string('VtaOper');
        $table->integer('XML')->default("0");
        $table->integer('PDF')->default("0");
        $table->integer('CDR')->default("0");
        $table->string('Estatus')->nullable();
      });
      $this->add_to_reporte('ventas_amazon');
    }

    if (!Schema::hasColumn( "ventas_amazon" , "EmpCodi" )) {
      Schema::table( "ventas_amazon", function ( Blueprint $table ) {
        $table->string('EmpCodi',3)->default("001");
      });
      $this->add_to_reporte("ventas_cab", "EmpCodi", false);
    }
  }


  public function check_ventas_cab( $table = 'ventas_cab' , $column = "VtaDetrPorc")
  {
    # Detracciones

    if (!Schema::hasColumn( $table , $column )) 
    {
      Schema::table( $table, function ( Blueprint $table ) {
        $table->string('VtaDetrPorc')->nullable();
        $table->string('VtaDetrTota')->nullable();
        $table->string('VtaTotalDetr')->nullable();
        $table->string('CuenCodi')->nullable();        
      });

      $this->add_to_reporte("ventas_cab", "VtaDetrPorc", false);
      $this->add_to_reporte("ventas_cab", "VtaDetrTota" , false);
      $this->add_to_reporte("ventas_cab", "VtaTotalDetr", false);      
      $this->add_to_reporte($table, "CueCodi", false);
    }

    if (!Schema::hasColumn($table, 'VtaDetrCode')) {
      Schema::table($table, function (Blueprint $table) {
        $table->string('VtaDetrCode')->nullable()->default(0);
      });
      $this->add_to_reporte("ventas_cab", "VtaDetrCode", false);
    }


    # Anticipos

    if (!Schema::hasColumn($table, 'VtaAnticipo')){
      Schema::table($table, function (Blueprint $table) {
        $table->string('VtaAnticipo')->nullable()->default(0);
      });
      $this->add_to_reporte("ventas_cab", "VtaAnticipo", false);
    }

    if (!Schema::hasColumn($table, 'VtaNumeAnticipo')) {
      Schema::table($table, function (Blueprint $table) {
        $table->string('VtaNumeAnticipo')->nullable();
        $table->string('VtaTidCodiAnticipo')->enum(['01','03'])->nullable();
      });
      $this->add_to_reporte("ventas_cab", "VtaNumeAnticipo", false);
      $this->add_to_reporte("ventas_cab", "VtaTidCodiAnticipo", false);
    }

    if (!Schema::hasColumn($table, 'VtaTotalAnticipo')) {
      Schema::table($table, function (Blueprint $table) {
        $table->string('VtaTotalAnticipo')->nullable();
      });
      $this->add_to_reporte("ventas_cab", "VtaTotalAnticipo", false);
    }
    
  }

  public function check_bancos( $table = "bancos_cuenta_cte", $column = "Detract" ){
    
    if (!Schema::hasColumn( $table , $column )) {
      Schema::table( $table, function ( Blueprint $table ) {
        $table->string('Detract')->default("0");
      });
    $this->add_to_reporte($table, $column , false);
    }
  }

  public function check_tipos_igvs( $table = "tipos_igvs" )
  {    
    if (!Schema::hasTable( $table )) {
      
      Schema::create( $table, function ( Blueprint $table ) {
        $table->increments('id');
        $table->string('cod_sunat');
        $table->enum('tipo', [ Venta::GRAVADA, Venta::EXONERADA, Venta::INAFECTA ]);
        $table->boolean('gratuito_disponible')->default(1);
        $table->string('descripcion');
      });

      \Artisan::call('db:seed', [
        '--class' => "TipoIGVSeeder"
      ]);

      $this->add_to_reporte($table);
    }
  }


  public function check_detracciones($table = "detracciones")
  {
    if (!Schema::hasTable($table)) {

      Schema::create($table, function (Blueprint $table) {
        $table->increments('id');
        $table->string('cod_sunat');
        $table->boolean('active');
        $table->string('descripcion');
        $table->string('porcentaje')->nullable();
      });

      \Artisan::call('db:seed', [
        '--class' => "DetraccionArticuloSeeder"
      ]);

      $this->add_to_reporte($table);
    }
  }

  public function runIfNoExists( $table, $column = false ) 
  {
    if( ! $column ){
      if( !Schema::hasTable($table) ){
        $this->runMigrations($table);
      }
    }
    else {
      if( !Schema::hasColumn($table,$column) ){
        $this->runMigrations($table);
      }
    }
  }

  public function check_aplly_statement()
  {
    DB::statement('ALTER TABLE `prov_clientes` CHANGE COLUMN `TDocCodi` `TDocCodi` VARCHAR(1) NULL DEFAULT NULL;');
    DB::statement('ALTER TABLE `prov_clientes_tipo_doc` CHANGE COLUMN `TDocCodi` `TDocCodi` VARCHAR(1) NOT NULL;');
    DB::statement('ALTER TABLE `ventas_ra_detalle` CHANGE COLUMN `TDocCodi` `TDocCodi` VARCHAR(1) NULL DEFAULT NULL;');


    DB::statement('ALTER TABLE `ventas_ra_cab` CHANGE COLUMN `DocNume` `DocNume` CHAR(25) NOT NULL;');
    DB::statement('ALTER TABLE `ventas_ra_detalle` CHANGE COLUMN `docNume` `docNume` CHAR(25) NOT NULL;');
    DB::statement('ALTER TABLE `ventas_ra_cab` CHANGE COLUMN `DocDesc` `DocDesc` VARCHAR(255) NOT NULL;');


    // Cambiar longitud del detalle
    DB::statement('ALTER TABLE `cotizaciones_detalle` CHANGE COLUMN `DetNomb` `DetNomb` VARCHAR(255) NULL DEFAULT NULL;');

    // Cambiar el tipo de dato en la columna VtaFMail en la tabla de ventas_cab
    DB::statement('ALTER TABLE `ventas_cab` CHANGE COLUMN `VtaFMail` `VtaFMail` VARCHAR(255) NULL DEFAULT NULL;');

    // Poner valores por defecto
    DB::statement('ALTER TABLE ' . '`' . env('DB_DATABASE') . '`' . '.`ventas_detalle` 
    CHANGE COLUMN `DetIGVV` `DetIGVV` FLOAT NULL DEFAULT 18 ,
    CHANGE COLUMN `DetSdCa` `DetSdCa` FLOAT NULL DEFAULT 0 ,
    CHANGE COLUMN `Detfact` `Detfact` FLOAT NULL DEFAULT 1 ,
    CHANGE COLUMN `DetDcto` `DetDcto` FLOAT NULL DEFAULT 0 ;');
    DB::statement('ALTER TABLE ' . '`' . env('DB_DATABASE') . '`' . '.`condicion_cpra_vta` DROP PRIMARY KEY, ADD PRIMARY KEY (`CcvCodi`, `empcodi`)');
  }

  public function runMigrations($table_or_column)
  {
    switch ($table_or_column) {
      case 'documentos_pendientes_detalle':
        Schema::create($table_or_column, function ( Blueprint $table) {
          $table->increments('id');          
          $table->integer('id_documento_pendiente')->unsigned();          
          $table->foreign('id_documento_pendiente')->on('documentos_pendientes')->references('id');          
          $table->string('VtaOper');
          $table->integer('VtaNume');
        });        
        $this->add_to_reporte($table_or_column);
        break;
      
      default:
        # code...
        break;
    }
  }

  public function check_ventas_ra_cab_table($table = 'ventas_ra_cab', $column = "LocCodi" )
  {
    if (!Schema::hasColumn($table, $column)) {
      Schema::table($table, function (Blueprint $table) use ($column) {
        $table->string($column)->default("001");
      });
      $this->add_to_reporte($table, $column, false);
    }
  }


  public function check_ventas_ra_detalle($table = 'ventas_ra_detalle' , $column1 = "vtatdr", $column2 = "vtaserir", $column3 = "vtanumer" ){
    if (!Schema::hasColumn($table, $column1)){
      Schema::table($table,function(Blueprint $table) use( $column1 , $column2 , $column3 ) {
        $table->string($column1)->nullable();
        $table->string($column2)->nullable();
        $table->string($column3)->nullable();          
      });
      $this->add_to_reporte($table, $column1 , false );
      $this->add_to_reporte($table, $column2 , false );
      $this->add_to_reporte($table, $column3 , false );
    }
  }

  // public function check_caja_detalle($table = 'caja_detalle', $column1 = "vtatdr", $column2 = "vtaserir", $column3 = "vtanumer")
  // {
  //   if (!Schema::hasColumn($table, $column1)) {
  //     Schema::table($table, function (Blueprint $table) use ($column1, $column2, $column3) {
  //       $table->string($column1)->nullable();
  //       $table->string($column2)->nullable();
  //       $table->string($column3)->nullable();
  //     });
  //     $this->add_to_reporte($table, $column1, false);
  //     $this->add_to_reporte($table, $column2, false);
  //     $this->add_to_reporte($table, $column3, false);
  //   }
  // }  
  //

  public function check_ventas_detalle($table = 'ventas_detalle', $column = "TipoIGV" )
  {
    if ( ! Schema::hasColumn($table, 'TipoIGV') ){
      
      Schema::table($table, function (Blueprint $table) use($column) {
        $table->string($column)->default("10");
      });
      $this->add_to_reporte($table , $column , false);
    }
  }

  public function check_usuario_empr($table = 'usuario_empr'){
    if (!Schema::hasTable($table)){
      Schema::create($table,function(Blueprint $table) {
        $table->increments('id');
        $table->string('usucodi')->default("01");
        $table->string('empcodi')->default("001");
        $table->integer('estado')->default(1);          
      });
      $this->add_to_reporte($table);
    }
  }

  public function check_usuarios_table($table = 'usuarios', $column = "active")
  {
    if (!Schema::hasColumn($table, $column)) {
      Schema::table($table, function (Blueprint $table) use ( $column ) {
        $table->integer($column)->default(1);
      });      
      $this->add_to_reporte($table , $column , false);        
    }

    // Si tiene el campo de mail
    if (!Schema::hasColumn($table, "email")) {
      Schema::table($table, function (Blueprint $table){
        $table->string('email', 200)->nullable();
      });      
      $this->add_to_reporte($table , "email" , false);        
    }

   // remember_token
    if (!Schema::hasColumn($table, "remember_token")) {
      Schema::table($table, function (Blueprint $table){
        $table->string('remember_token')->nullable();
      });      
      $this->add_to_reporte($table , "remember_token" , false);        
    }    

   // verificate
   if (!Schema::hasColumn($table, "verificate")) {
    Schema::table($table, function (Blueprint $table){
      $table->string('verificate')->default("");
    });      
    $this->add_to_reporte($table , "verificate" , false);        
  }

   // verificate_code
   if (!Schema::hasColumn($table, "verificate_code")) {
    Schema::table($table, function (Blueprint $table){
      $table->longText('verificate_code')
      ->nullable();      
    });
    $this->add_to_reporte($table , "verificate_code" , false);        
  }



  }


  // Verificiar que existan todas las tablas para el sistema de permisos
  public function check_roles_table($table = "roles")
  {    
    if (!Schema::hasTable($table)) {
      self::deletePermisosTable();
      $this->add_to_reporte('permissions');
      $this->add_to_reporte('roles');
      $this->add_to_reporte('model_has_permissions');
      $this->add_to_reporte('model_has_roles');
      $this->add_to_reporte('role_has_permissions');

        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('guard_name');
          $table->string('group')->unique();
          $table->string('descripcion')->nullable();
          $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('guard_name');
          $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');
            $table->string('model_type');
            $table->string($columnNames['model_morph_key']);
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');
            $table->string('model_type');
            $table->string($columnNames['model_morph_key']);
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            app('cache')->forget('spatie.permission.cache');
        });

      \Artisan::call('db:seed', ['--class' => "PermissionSeeder"]);
    }
  }

  public function verificar_db()
  {
    foreach (get_class_methods(new self) as $name  ) {     
      if( strpos( $name , "check_" ) !== false ){
        $this->{$name}();
      }      
    }
    return $this->reporte;
  }



  public function check_roles_table_monitor_tables()
  {
    # Tabla mod_monitoreo_empresas
    $tableNameEmpresa = 'monitor_empresas';
    if (!Schema::hasTable($tableNameEmpresa)) {
      Schema::create($tableNameEmpresa, function (Blueprint $table){
        $table->increments('id');
        $table->string('code')->nullable();
        $table->string('razon_social');
        $table->string('ruc')->required()->unique();
        $table->string('email')->nullable();
        $table->string('telefono')->nullable();
        $table->string('usuario_sol')->required();
        $table->string('clave_sol')->required();        
        $table->string('proveedor')->required();        
        $table->longText('descripcion')->nullable();
        $table->boolean('active')->default(0);
        $table->timestamps();
      });
      $this->add_to_reporte($tableNameEmpresa);
    }
    else {
      if (!Schema::hasColumn($tableNameEmpresa , 'cant_docs'  )) {

        Schema::table($tableNameEmpresa, function (Blueprint $table) {
          $table->unsignedInteger('cant_docs');
        });

        $this->add_to_reporte($tableNameEmpresa, 'cant_docs', false);      
      }
    }


    # Tabla mod_monitoreo_empresas
    $tableNameSerie = 'monitor_empresa_series';
    if (!Schema::hasTable($tableNameSerie)) {
      Schema::create($tableNameSerie, function (Blueprint $table) use($tableNameEmpresa) {
        $table->increments('id');
        $table->integer('empresa_id')->unsigned();
        // $table->foreign('empresa_id')->references($tableNameEmpresa)->on('id')->onDelete('cascade');
        $table->string('tipo_documento');
        $table->string('serie');
        $table->timestamps();
      });
      $this->add_to_reporte($tableNameSerie);
    }



    // # Tabla mod_monitoreo_empresas    
    $tableNameDoc = 'monitor_empresa_documentos';
    if (!Schema::hasTable($tableNameDoc)) {
      Schema::create($tableNameDoc, function (Blueprint $table){
        $table->increments('id');
        $table->integer('serie_id')->unsigned();
        $table->string('numero');
        $table->string('fecha_emision')->nullable();
        $table->boolean('buscado_sunat')->default(0);
        $table->integer('mescodi')->nullable();
        $table->timestamps();

      });
      $this->add_to_reporte('monitor_documentos');
    }


    # Tabla monitor_empresa_documentos    
    $tableNameSunatR = 'monitor_documentos_status';
    if (!Schema::hasTable($tableNameSunatR)) {
      Schema::create($tableNameSunatR, function (Blueprint $table) {
        $table->increments('id');
        $table->integer('documento_id')->unsigned();
        $table->foreign('documento_id')
        ->on('monitor_empresa_documentos')
        ->references('id')
        ->onDelete('cascade');
        $table->string('status_id')->nullable();
        $table->timestamps();
      });
      $this->add_to_reporte($tableNameSunatR);
    }
    # Tabla monitor_empresa_documentos    
    $tableNameSunatR = 'monitor_status_codes';
    if (!Schema::hasTable($tableNameSunatR)) {
      Schema::create($tableNameSunatR, function (Blueprint $table) {
        $table->increments('id');
        $table->string('tipo');
        $table->string('status_code',255);
        $table->string('status_message');
        $table->timestamps();
      });
      $this->add_to_reporte($tableNameSunatR);
      \Artisan::call('db:seed', [
        '--class' => "StatusCodeSeeder"
      ]);

    }
  }




}