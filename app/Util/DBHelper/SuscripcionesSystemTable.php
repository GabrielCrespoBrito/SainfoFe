<?php
namespace App\Util\DBHelper;

use App\Models\Suscripcion\Caracteristica;
use App\Models\Suscripcion\Suscripcion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

trait SuscripcionesSystemTable 
{
  /**
   * Creacion de tablas para el sistema de suscripciónes
   *
   * @return void
   */
  public function suscripcion_system()
  {
    # Tabla suscripcion_system_planes
    $tableName = 'suscripcion_system_planes';
    if (Schema::hasTable($tableName)) {
      return;
    }

    # Tabla Planes
    Schema::create($tableName, function (Blueprint $table){
      $table->increments('id');
      $table->string('codigo');
      $table->string('nombre');
      $table->string('descripcion')->nullable();
      $table->boolean('active')->default(1);
      $table->boolean('is_demo')->default(0);
      $table->boolean('recomendado')->default(0);
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Tabla Caracteristicas
    $tableName = 'suscripcion_system_caracteristicas';
    Schema::create($tableName, function (Blueprint $table){
      $table->increments('id');
      $table->string('codigo')->nullable();
      $table->string('nombre');
      $table->string('value')->nullable();
      $table->string('adicional')->nullable();
      $table->boolean('primary')->default(1);
      $table->boolean('active')->default(1);
      $table->enum('tipo', [  Caracteristica::RESTRICCION, Caracteristica::CONSUMO ])->default(Caracteristica::CONSUMO);
      $table->boolean('reset')->default(0);
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Duración
    $tableName = 'suscripcion_system_duraciones';
    Schema::create($tableName, function (Blueprint $table){
      $table->increments('id');
      $table->string('codigo')->nullable();
      $table->string('nombre');
      $table->integer('duracion'); 
      $table->enum('tipo_duracion',['dias','meses']); 
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);
    
    # Planes caracteristicas
    $tableName = 'suscripcion_system_planes_caracteristicas';
    Schema::create($tableName, function (Blueprint $table){
      $table->increments('id');
      $table->unsignedInteger('plan_id');
      $table->unsignedInteger('caracteristica_id');
      $table->string('value')->nullable();
      $table->boolean('active')
      ->default(1) // Si 
      ->comment('Si se aplica o no'); // Si 
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Duración
    $tableName = 'suscripcion_system_planes_duraciones';
    Schema::create($tableName, function (Blueprint $table){
      $table->increments('id');
      $table->string('codigo');
      $table->unsignedInteger('plan_id');
      $table->unsignedInteger('duracion_id');
      $table->integer('descuento_porc');
      $table->float('descuento_value');
      $table->float('base');
      $table->float('igv');
      $table->float('total');
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Ordenes de pago
    $tableName = 'suscripcion_system_ordenes_pago';
    Schema::create($tableName, function (Blueprint $table) {
      $table->increments('id');
      $table->string('uuid')->nullable();
      $table->unsignedInteger('duracion_id');
      $table->string('empresa_id');
      $table->string('user_id');
      $table->integer('descuento_porc');
      $table->float('descuento_value');
      $table->float('base');
      $table->float('igv');
      $table->float('total');
      $table->dateTime('fecha_emision');
      $table->dateTime('fecha_vencimiento')->nullable();
      $table->enum('estatus', ['pendiente','pagada']);
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Suscripciones
    $tableName = 'suscripcion_system_suscripciones';
    Schema::create($tableName, function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('orden_id');
      $table->string('empresa_id');
      $table->dateTime('fecha_inicio');
      $table->dateTime('fecha_final');
      $table->boolean('actual')->default(1);
      $table->enum('estatus', [Suscripcion::ESTATUS_ACTIVA, Suscripcion::ESTATUS_VENCIDA,  Suscripcion::ESTATUS_CANCELADA ]);
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);

    # Usos de la Suscripciones 
    $tableName = 'suscripcion_system_suscripciones_usos';
    Schema::create($tableName, function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('suscripcion_id');
      $table->unsignedInteger('caracteristica_id');
      $table->integer('limite');
      $table->integer('uso');
      $table->integer('restante');
      $table->timestamps();
    });
    $this->addMessageCreationTable($tableName);
    

    # Columna a la empresa
    $tableName = 'opciones';
    if(! Schema::hasColumn($tableName,'tipo_plan')  )
    Schema::table($tableName, function (Blueprint $table) {
      $table->string('tipo_plan')->nullable();
      $table->boolean('need_config')->default('0');
      $table->dateTime('end_plan')->nullable();
    });
    $this->addMessageCreationColumn($tableName, 'tipo_plan');

    # Llamada a los Seeders 
    Artisan::call('db:seed', [
      '--class' => "SuscripcionSystemSeeder"
    ]);
      
    return;
  }

 /**
   * Eliminación de las tablas para el sistema de suscripciónes
   *
   * @return void
   */
  public function suscripcion_system_rollback()
  {
    Schema::dropIfExists('suscripcion_system_planes');
    Schema::dropIfExists('suscripcion_system_caracteristicas');
    Schema::dropIfExists('suscripcion_system_planes_caracteristicas');
    Schema::dropIfExists('suscripcion_system_duraciones');
    Schema::dropIfExists('suscripcion_system_planes_duraciones');
    Schema::dropIfExists('suscripcion_system_ordenes_pago');
    Schema::dropIfExists('suscripcion_system_suscripciones');
    Schema::dropIfExists('suscripcion_system_suscripciones_usos');
    

    if (Schema::hasColumn('opciones', 'tipo_plan')) {
      Schema::table('opciones', function (Blueprint $table) {
        $table->dropColumn(['tipo_plan', 'end_plan']);
      });
    }

    if (Schema::hasColumn('opciones', 'need_config')) {
      Schema::table('opciones', function (Blueprint $table) {
        $table->dropColumn(['need_config']);
      });
    }

    $this->addMessageDeleteTable('suscripcion_system_planes');
    $this->addMessageDeleteTable('suscripcion_system_caracteristicas');
    $this->addMessageDeleteTable('suscripcion_system_planes_caracteristicas');
    $this->addMessageDeleteTable('suscripcion_system_duraciones');
    $this->addMessageDeleteTable('suscripcion_system_planes_duraciones');
    $this->addMessageDeleteTable('suscripcion_system_suscripciones');
    $this->addMessageDeleteTable('suscripcion_system_suscripciones_usos');

  }


}