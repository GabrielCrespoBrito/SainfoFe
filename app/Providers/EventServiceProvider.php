<?php

namespace App\Providers;

use App\Events\OrdenhasPay;
use App\Events\CompraCreated;
use App\Events\CompraUpdated;
use App\Events\CompraDeleting;
use App\Listeners\UpdatedPlan;
use App\Events\PlanUpdateEvent;
use App\Events\OrdenPagoHasCreated;
use App\Listeners\Empresa\SaveCert;
use App\Listeners\Guia\CreatedGuia;
use App\Listeners\Guia\UpdateVenta;
use App\Listeners\SaveConfigStatus;
use App\Events\CreateTomaInventario;
use App\Events\GuiaSimplyHasCreated;
use App\Listeners\ProcessSuscripcion;
use Illuminate\Auth\Events\Registered;
use App\Events\PlanCaracteristicaDelete;
use App\Events\PlanCaracteristicaUpdate;
use App\Events\User\UserOwnerHasCreated;
use App\Listeners\CompraCreatedListener;
use App\Listeners\CompraUpdatedListener;
use App\Listeners\SendOrderNotification;
use App\Events\Empresa\EmpresaHasCreated;
use App\Listeners\CompraDeletingListener;
use App\Listeners\CreatedAndStoreOrdenPdf;
use App\Listeners\Empresa\CreateDbEmpresa;
use App\Listeners\StoreDataTomaInventario;
use App\Listeners\User\UpdateEmpresaStats;
use App\Jobs\User\setUserDefaultPermission;
use App\Listeners\User\SendInfoUserCreated;
use App\Events\CreateTomaInventarioFromExcell;
use App\Events\Empresa\CertFacturacionInfoSave;
use App\Listeners\User\SendRegisterConfirmation;
use App\Listeners\Monitoreo\Empresa\CreateSeries;
use App\Listeners\Monitoreo\Empresa\CreateFolders;
use App\Listeners\Monitoreo\Empresa\UpdatedSeries;
use App\Events\Monitoreo\Empresa\EmpresaHasUpdated;
use App\Listeners\PlanCaracteristicaDeleteListener;
use App\Listeners\PlanCaracteristicaUpdateListener;
use App\Listeners\SendNotificationSuscripcionActive;
use App\Listeners\Empresa\SaveEmpresaFacturacionData;
use App\Listeners\Monitoreo\Empresa\SaveCertificates;
use App\Listeners\Empresa\IniciarDocumentosProduccion;
use App\Listeners\ProductoTomaInventarioExcellListener;
use App\Listeners\Empresa\CreatedEmpresaDefaultsMainData;
use App\Events\Monitoreo\Empresa\EmpresaMonitoreoHasCreated;
use App\Listeners\Empresa\CreatedEmpresaDefaultsSecundaryData;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */

	protected $listen =
	[	
		Registered::class => [
			SendRegisterConfirmation::class
		],
		'App\Events\InvoiceCreated' =>
		[
			'App\Listeners\UpdatedSerieDocumento',
			'App\Listeners\InvoiceNeedCreated',
		],
		'App\Events\GuiaHasCreate' =>
		[
			'App\Listeners\UpdatedVentaSend',
		],
    // 
		GuiaSimplyHasCreated::class => [
			CreatedGuia::class,
			UpdateVenta::class,
		],

		CompraCreated::class =>
		[
			CompraCreatedListener::class
		],
		CompraUpdated::class =>
		[
			CompraUpdatedListener::class
		],
		CompraDeleting::class => [
			CompraDeletingListener::class,
		],
		EmpresaHasCreated::class => [
			CreateDbEmpresa::class,
			CreatedEmpresaDefaultsMainData::class,
			CreatedEmpresaDefaultsSecundaryData::class,
		],
		EmpresaMonitoreoHasCreated::class => [
			CreateFolders::class,
			SaveCertificates::class,
			CreateSeries::class,
		],

		EmpresaHasUpdated::class => [
			SaveCertificates::class,			
			UpdatedSeries::class,			
		],

		CertFacturacionInfoSave::class => [
			SaveCert::class,
			SaveEmpresaFacturacionData::class,
			IniciarDocumentosProduccion::class,
			SaveConfigStatus::class,
		],
		
		UserOwnerHasCreated::class => [
			UpdateEmpresaStats::class, 
			SendInfoUserCreated::class,
      setUserDefaultPermission::class,
		],

		OrdenPagoHasCreated::class => [
			CreatedAndStoreOrdenPdf::class,
			SendOrderNotification::class,
		],

		OrdenhasPay::class => [
			ProcessSuscripcion::class,
			SendNotificationSuscripcionActive::class
		],

    PlanUpdateEvent::class =>  [
      UpdatedPlan::class
    ],

    PlanCaracteristicaUpdate::class =>  [
      PlanCaracteristicaUpdateListener::class
    ],

    CreateTomaInventario::class => [
      StoreDataTomaInventario::class
    ],

    PlanCaracteristicaDelete::class =>  [
      PlanCaracteristicaDeleteListener::class
    ],

    CreateTomaInventarioFromExcell::class =>  [
      ProductoTomaInventarioExcellListener::class
    ],    
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}
}
