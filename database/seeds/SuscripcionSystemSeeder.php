<?php

use App\Empresa;
use App\Events\OrdenhasPay;
use Illuminate\Database\Seeder;
use App\Models\Suscripcion\Plan;
use App\Models\Suscripcion\Duracion;
use App\Models\Suscripcion\OrdenPago;
use App\Models\Suscripcion\Suscripcion;
use App\Models\Suscripcion\PlanDuracion;
use App\Models\Suscripcion\Caracteristica;
use App\Models\Suscripcion\PlanCaracteristica;
use App\Suscripcion\SuscripcionUso;

class SuscripcionSystemSeeder extends Seeder
{	
	# Planes 
	const PLANES_DATA = [
		['nombre' => 'Demo',   'codigo' => 'PLAN-DEMO', 'is_demo' => 1 ],
		['nombre' => 'Basico', 'codigo' => 'PLAN-BASICO' ],
		['nombre' => 'Medio',  'codigo' => 'PLAN-MEDIO' ],
		['nombre' => 'Pro',    'codigo' => 'PLAN-PRO' ],
	];

	# Caracteristicas
	const CARACTERISTICAS_DATA = [
		['codigo' => Caracteristica::COMPROBANTES, 'tipo' => Caracteristica::CONSUMO,  'nombre' => 'Comprobantes', 'value' => 1, 'reset' => 1],
		['codigo' => Caracteristica::USUARIOS, 'tipo' => Caracteristica::CONSUMO,  'nombre' => 'Usuarios', 'value' => 1, 'reset' => 0],
		['codigo' => Caracteristica::PRODUCTOS, 'tipo' => Caracteristica::CONSUMO,  'nombre' => 'Productos', 'value' => 1, 'reset' => 1],
		['codigo' => Caracteristica::LOCAL, 'tipo' => Caracteristica::CONSUMO,  'nombre' => 'Locales', 'value' => 1, 'reset' => 0],
		['codigo' => Caracteristica::LOGOTIPO, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Uso de Logotipo propio'],
		['codigo' => Caracteristica::REPORTES, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Reportes en excell, pdf y en linea.'],
		['codigo' => Caracteristica::ENVIO, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Facturas, Boletas de Venta, Notas de Crédito y Débito, Anulaciones, Guia Remimisión'],
		['codigo'   => Caracteristica::SUNAT, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Conexión con SUNAT en 24 horas.'],
		['codigo'   => Caracteristica::CERTIFICADO, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Certificado Digital incluido. *', 'adicional' => 'El certificado viene incluido sin costo, si su empresa factura menos de 300 UIT, si excede esa cantidad el certificado corre por cuenta del cliente'],
		['codigo'   => Caracteristica::CATALOGO, 'tipo' => Caracteristica::RESTRICCION,  'nombre' => 'Catálogo de productos y servicios.'],
	];

	# Duraciones
	const DURACIONES_DATA = [
		['codigo' => '1MES', 'nombre' => '1 Mes',  'duracion' => 30, 'tipo_duracion' => Duracion::DURACION_DIARIO ],
		['codigo' => '12MESES', 'nombre' => '12 Meses',  'duracion' => 12, 'tipo_duracion' => Duracion::DURACION_MENSUAL ],	
	];


	public function truncateTables()
	{
		Plan::truncate();
		Duracion::truncate();
		Caracteristica::truncate();

		PlanCaracteristica::truncate();
		PlanDuracion::truncate();
		Suscripcion::truncate();
		OrdenPago::truncate();
		SuscripcionUso::truncate();
	}

  public function unguardTables()
  {
    Plan::truncate();
    Duracion::truncate();
    Caracteristica::truncate();

    PlanCaracteristica::truncate();
    PlanDuracion::truncate();
    Suscripcion::truncate();
    OrdenPago::truncate();
    SuscripcionUso::truncate();
  }

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		# Eliminar información de las tablas
		$this->truncateTables();
    $this->unguardTables();

		// Plan::unguard();
		// Caracteristica::unguard();
		// PlanCaracteristica::unguard();
		// Duracion::unguard();
		// PlanDuracion::unguard();

    // Tablas principales

		# Crear Planes
		$planes = $this->savePlanes();
		# Crear Caracteristicas
		$caracteristicas = $this->saveCaracteristicas();
		# Duraciones
		$duraciones = $this->saveDuraciones();


    # Plan duraciones
    $plan_duraciones = $this->savePlanDuraciones($planes, $duraciones);

		# Guardar Planes - Caracteristicas
		$this->savePlanCaracteristicas($plan_duraciones, $caracteristicas);
		// $this->savePlanCaracteristicas($planes, $caracteristicas);
		
		# Plan duraciones
		$plan_duraciones = $this->savePlanDuraciones( $planes, $duraciones);

		// dd(  $plan_duraciones );

		if( env('APP_ENV') == "local"){

			$empresas = Empresa::all();
			# Plan por defecto para las empresas, el Plan Pro
			$plan_duracion = end($plan_duraciones);

			foreach( $empresas as $empresa ){
				$orden_pago = OrdenPago::createFromPlanDuracion($plan_duracion, $empresa->id(), $empresa->userOwner()->id() );
				$orden_pago->update(['estatus' => OrdenPago::PAGADA ]);
				$orden_pago->createSuscripcion();
			}
		}

	}

	/**
	 * Guardar los planes
	 *
	 * @return array
	 */
	public function savePlanes()
	{
		$planes = [];
		foreach (SELF::PLANES_DATA as $plan) {
			$planes[] = Plan::create($plan);
		}

		return $planes;
	}

	/**
	 * Guardar los planes
	 *
	 * @return array
	 */
	public function saveCaracteristicas()
	{
		$caracteristicas = [];
		foreach ( self::CARACTERISTICAS_DATA as $caracteristica) {
			$caracteristicas[] = Caracteristica::create($caracteristica);
		}

		return $caracteristicas;
	}


	/**
	 * Guardar las duraciones de los planes
	 *
	 * @return array
	 */
	public function saveDuraciones()
	{
		# Duraciones
		$duraciones = [];
		foreach (self::DURACIONES_DATA  as $duracion) {
			$duraciones[] = Duracion::create($duracion);
		}
		return $duraciones;
	}

	/**
	 * Guardar los duraciones de cada plan especifico
	 *
	 * @return array
	 */
	public function savePlanDuraciones( $planes, $duraciones )
	{		
		# Asociar los Planes y la duración
		$plan_duraciones = [];

		# Plan demo
		$data_mes = $this->getPlanDuracionData($planes[0], $duraciones[0], 0, 0, 0, 0, 0);
		$plan_duraciones[] = PlanDuracion::create($data_mes);

		# Plan basico
		$data_mes = $this->getPlanDuracionData($planes[1], $duraciones[0], 30, 5.4, 35.4, 0, 0);
		$data_year = $this->getPlanDuracionData($planes[1], $duraciones[1], 259.32 , 46.68, 306, 15, 45.76);
		$plan_duraciones[] = PlanDuracion::create($data_mes);
		$plan_duraciones[] = PlanDuracion::create($data_year);

		# Plan intermedio
		$data_mes = $this->getPlanDuracionData($planes[2], $duraciones[0], 45, 8.1, 53.1, 0, 0);
		$data_year = $this->getPlanDuracionData($planes[2], $duraciones[1], 459, 82.62, 541.62, 15, 81.00);
		$plan_duraciones[] = PlanDuracion::create($data_mes);
		$plan_duraciones[] = PlanDuracion::create($data_year);

		# Plan pro
		$data_mes = $this->getPlanDuracionData($planes[3], $duraciones[0], 95.00, 17.1 , 112.01, 0, 0);
		$data_year = $this->getPlanDuracionData($planes[3], $duraciones[1], 969, 174.42, 1143.42, 15, 171);
		$plan_duraciones[] = PlanDuracion::create($data_mes);
		$plan_duraciones[] = PlanDuracion::create($data_year);

		return $plan_duraciones;
	}

	/**
	 * Guardar individualmente cada plan-duracion
	 *
	 * @return PlanDuracion
	 */
	public function getPlanDuracionData($plan, $duracion, $base, $igv, $total, $descuento_porc, $descuento_value)
	{
		return [
			'plan_id' => $plan->id,
			'duracion_id' => $duracion->id,
			'codigo' => $plan->codigo . '-' .  $duracion->codigo,
			'base' => $base,
			'igv' => $igv,
			'total' => $total,
			'descuento_porc' => $descuento_porc,
			'descuento_value' => $descuento_value,
		];
	}


	/**
	 * Guardar la relacion de planes y caracteristicas
	 *
	 * @return array
	 */
	public function savePlanCaracteristicas( array $planes, $caracteristicas)
	{
		# Plan demo
		$caracteristicaCustomValues = $this->generateCustomCaracteristicasValues(30,1,100,1);		
		$this->assignCaracteristicaToPlan($planes[0], $caracteristicas,  $caracteristicaCustomValues );

		# Plan basico
		$caracteristicaCustomValues = $this->generateCustomCaracteristicasValues(100,3, 1000, 1);
		$this->assignCaracteristicaToPlan($planes[1], $caracteristicas,  $caracteristicaCustomValues);

		# Plan intermedio
		$caracteristicaCustomValues = $this->generateCustomCaracteristicasValues(300, 5, 6000, 3);
		$this->assignCaracteristicaToPlan($planes[2], $caracteristicas,  $caracteristicaCustomValues);

		# Plan Pro
		$caracteristicaCustomValues = $this->generateCustomCaracteristicasValues(99999999, 10, 10000, 10);
		$this->assignCaracteristicaToPlan($planes[3], $caracteristicas,  $caracteristicaCustomValues);

	}

	public function assignCaracteristicaToPlan( Plan $plan, array $caracteristicas,  array $caracteristicaCustomValues )	
	{
		foreach( $caracteristicas as $caracteristica ){

			$value = array_key_exists( $caracteristica->codigo,  $caracteristicaCustomValues) ?
			$caracteristicaCustomValues[$caracteristica->codigo] : $caracteristica->default;

			$data = [
				'plan_id' => $plan->id,
				'caracteristica_id' => $caracteristica->id,
				'value' => $value,
			];

			PlanCaracteristica::create($data);
		}
	}


	
	public function generateCustomCaracteristicasValues( int $comprobantes, int $usuarios, int $productos, int $locales )
	{   
    return  [
			Caracteristica::COMPROBANTES => $comprobantes,
			Caracteristica::USUARIOS => $usuarios,
			Caracteristica::PRODUCTOS => $productos,
			Caracteristica::LOCAL => $locales,
		];
	}
	
}