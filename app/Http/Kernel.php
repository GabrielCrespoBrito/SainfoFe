<?php

namespace App\Http;

use App\Http\Middleware\HttpsProtocol;
use App\Http\Middleware\redirectSecureHttp;
use App\Http\Middleware\VerifiyConfigEmpresa;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
      \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
      \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
      \App\Http\Middleware\TrimStrings::class,
      \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
      \App\Http\Middleware\TrustProxies::class,
      // redirectSecureHttp::class,
      // \Illuminate\Session\Middleware\StartSession::class,
      // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // HttpsProtocol::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
      'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
      'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
      'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
      'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
      'can' => \Illuminate\Auth\Middleware\Authorize::class,
      'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
      'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
      'elegir.periodo' => \App\Http\Middleware\ElegirPeriodoMiddleware::class,        
      'ventas.creacion' => \App\Http\Middleware\VentaCreateMiddleware::class,
      'role' => \App\Http\Middleware\RoleMiddleware::class,       
      'cliente.acceso' => \App\Http\Middleware\ClienteAdministracionMiddleware::class,        
      'familia.producto.creacion' => \App\Http\Middleware\FamiliaProductoMiddleware::class,        
      'caja.aperturada' => \App\Http\Middleware\CajaMiddleware::class,              
      'usuario.empresa' => \App\Http\Middleware\UsuarioEmpresa::class,
      'permisos' => \App\Http\Middleware\Permission::class,
      'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
      'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
      'usuario.activo' => \App\Http\Middleware\ActiveUserMiddelware::class,
      'usuario.verificar' => \App\Http\Middleware\VerificarUserMiddleware::class,
      'registration_user.active' => \App\Http\Middleware\VerifyUserRegistrationIsActive::class,
      'usuario.need_verify_empresa' => \App\Http\Middleware\UserIsNeedVerifyEmpresaMiddleware::class,
      'isAdmin' => \App\Http\Middleware\IsAdminMiddleware::class,
      'administrative_user' => \App\Http\Middleware\IsUserAdministrative::class,
      'isNotOse' => \App\Http\Middleware\VerifyIsNotOseMiddleware::class,
      'tenancy.enforce' => \App\Http\Middleware\EnforceTenancy::class,
      'tenancy.force_url' => \App\Http\Middleware\ForceUrlTenancy::class,
      'tenant.exists' => \App\Http\Middleware\TenantExists::class,
      'basehost.enforce' => \App\Http\Middleware\RedirectIfNotBaseHost::class,
      'guia.seriecreada' => \App\Http\Middleware\GuiaHasTipoDocumentoMiddleware::class,
      'orden_pago.pendiente' => \App\Http\Middleware\EmpresaHasOrdenPagoPending::class,
      'suscripcion.active' =>  \App\Http\Middleware\VerifyIfEmpresaSuscripcionIsActive::class, 
      'verifiy.config' =>  \App\Http\Middleware\VerifiyConfigEmpresa::class, 
      'check.consumo' =>  \App\Http\Middleware\CheckConsumoEmpresa::class,
      'empresa.active' =>  \App\Http\Middleware\EmpresaIsActiveMiddleware::class,
      'modulo_activo' =>  \App\Http\Middleware\ModuloActiveMiddleware::class,
    
      
      // Acceso solo a los dueños de la empresa 
      'acceso_owner' =>  \App\Http\Middleware\OwnerAccess::class,       
    ];
}
