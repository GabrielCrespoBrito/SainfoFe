<?php

namespace App\Http\Controllers;

use App\EnvHandler;
use App\Http\Controllers\Util\RespaldoBaseDato\CompressRar;
use App\Http\Requests\ImageBannerFooterSaveRequest;
use App\Jobs\Admin\CompressBackupFolder;
use App\SettingSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
	public function __construct()
  {
	  $this->middleware('isAdmin')->only(['runComandos']);
	}

  public function saveImageBannerPDF(ImageBannerFooterSaveRequest $request)
  {
    $fh = FileHelper();
    $fh->only_nube = true;
    $fh->save_img(config('app.images.footer_banner_name'),file_get_contents($request->file('img_footer')->getPathname()));

    noti()->success("Acción exitosa", 'Se ha guardado la nueva imagen exitosamente');
    return redirect()->back();
  }


	public function runComandos( Request $request,  $comando )
  {
		switch ($comando) {

			case 'instalacion':
				Artisan::call('instalacion');
			break;
        
			case 'verificardatabase':
	  		Artisan::call('verificar:database', ['onlyOwner' => $request->input('onlyOwner', false) ]);
			break;

			case 'parametros':
        SettingSystem::registerNews();
			break;
	
      case 'permisos':
        Artisan::call('system_task:add_permisos');
        break;

      case 'permisos_all':
        ini_set('max_execution_time', '300');
        Artisan::call('system_task:add_permisos', ['all_user' => 1]);
        break;

			case 'eliminar_temporales':
				Artisan::call('eliminar:temp');
			break;

			case 'barradebug':
				EnvHandler::toggle_debug();
			break;	

			case 'subir_archivos':
				Artisan::call('util:subir_archivos');			
			break;

      case 'enviar_doc_pendientes':
        Artisan::call('system_task:enviar_doc_pendientes');			
      break;

      case 'limpiar_cache':
        Artisan::call('cache:clear');
        break;

      case 'medios_pagos':
        Artisan::call('system_task:actualizar_campo_gd');
      break;        

      case 'respaldar_base_datos':
        Artisan::call('db:respaldo', ['database' => $request->input('basedatos'), 'guardar_amazon' => $request->input('guardar_amazon', 1)  ]);

        _dd("Respaldando");
        exit();

        if( $request->input('descargar') ){
          $pathFileToCompress = getTempPath('backup');
          $pathFileCompress = getTempPath('backup.rar');
        }

        _dd("todo_listo");
        exit();

        break;         

      case 'imagen_footer_sainfo':
        throw new \Exception("Error Processing Request", 1);
        break;        
        

			case 'default_cliente_proveedor':
			break;				
		}

		noti()->success("Acción exitosa completamente exitosamente");
		return redirect()->back();
	}
}

