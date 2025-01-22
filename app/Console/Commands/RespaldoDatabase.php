<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Http\Controllers\Util\RespaldoBaseDato\CompressRar;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RespaldoDatabase extends Command
{
  const MESSAGE_INIT = 1;
  const MESSAGE_FINAL = 0;
  const SEPARATOR = "-------------";

  protected $signature = 'db:respaldo {--database=all} {--subir=1}';
  protected $description = 'Respaldo de las base de datos';
  protected $process;

  public $pathSql;
  public $pathCompress;
  public $fileNameSql;
  public $fileNameCompress;
  public $filesSqlSave = [];
  public $nombreEmpresa;
  public $date;

  /**
   * Ubicacion de guardado de archivos
   * 
   * @param string 
   */
  public $pathTemp;

  /**
   * Base de datos a la que esta respaldando
   * 
   * @param string
   */
  public $currentDatabase;

  /**
   * Registrar direcciones de almacenamientos
   * 
   * @return void
   */

  public function setSqlPaths()
  {
   
    $this->fileNameSql =  date('Ymd') . '_' . $this->currentDatabase . '.sql';
    $this->currentDatabase . '.sql';
    $this->fileNameCompress = $this->currentDatabase . '.rar';
    $this->pathSql = $this->pathTemp .  $this->fileNameSql;
    $this->pathCompress = $this->pathTemp .  $this->fileNameCompress;
  }

  public function setCurrentDatabase($dbName)
  {
    $this->currentDatabase = $dbName;
    $this->setSqlPaths();
  }

  public function __construct()
  {
    parent::__construct();

    $this->date = now()->subDay(1)->format('Y-m-d');

    // $this->pathTemp =  public_path( 'temp' . getSeparator());

    $this->pathTemp =  config('app.backups') . DIRECTORY_SEPARATOR;

    if (env('enviroment') === 'production') {
      ini_set('memory_limit', '400M');
      ini_set('max_execution_time', '900');
    }
  }

  public function deleteFiles()
  {
    // 
    foreach ($this->filesSqlSave as $file) {
      logger('@ELIMINANDO' . $file);
      // unlink($file);
    }
  }


  public function messageOutput($message, $position = self::MESSAGE_INIT)
  {
    $position === self::MESSAGE_INIT ? $this->info('') : null;
    $position === self::MESSAGE_INIT ? $this->info(self::SEPARATOR) : null;
    $this->info($message);
    $position === self::MESSAGE_FINAL ? $this->info(self::SEPARATOR) : null;
    $position === self::MESSAGE_FINAL ? $this->info('') : null;
  }

  /**
   * Ubicación donde almacenar los archivos
   * 
   * @return string
   */
  public function getTempPathTempStore($fileName = '')
  {
    return $this->pathTemp . $fileName;
  }
  // 
  public function generateCommand()
  {
    $mysqlCommand = get_setting('mysqldump_path');

    $comando = sprintf(
      'mysqldump -u%s -p%s %s > %s',
      // '%s -h127.0.0.1 -u%s -p%s %s > %s',
      config('database.connections.mysql.username'),
      config('database.connections.mysql.password'),
      $this->currentDatabase,
      $this->getTempPathTempStore( $this->fileNameSql )
    );

    Log::info("@COMANDO " . $comando );

    $this->process = new Process($comando, null, null, null, 300);
  }

  public function makeCompress()
  {
    $pathCompress = windows_os() ? $this->pathCompress : '';

    $compress = new CompressRar($this->pathSql, $pathCompress);
    $this->messageOutput( sprintf('Compromiendo %s ...', $this->currentDatabase ));
    $compress->make();
    $this->messageOutput( sprintf('Compresión %s lista', $this->currentDatabase), self::MESSAGE_FINAL);
  }

  // Corriendo Comando para respaldo
  public function makeRespaldo()
  {
    $this->messageOutput(sprintf('Respaldando %s', $this->fileNameSql));
    $this->generateCommand();
    $this->process->mustRun();
    $this->messageOutput( sprintf('Respaldo OK ', $this->fileNameSql ) , self::MESSAGE_FINAL);
  }


  // Guardando en amazon
  public function makeSaveAmazon()
  {
    $this->messageOutput('Guardando en amazon...');
    $fileHelper = FileHelper();
    $path = sprintf('/backup/%s/%s', $this->date, $this->fileNameCompress);
    
    $fileHelper->save_nube($path, file_get_contents($this->pathCompress));
    $this->messageOutput('Guardado en amazon listo en la dirección : ' . $path, self::MESSAGE_FINAL);
  }

  public function addFilesSqlSave($file)
  {
    $this->filesSqlSave[] = $file;
  }

  public function respaldoDB()
  {
    $success = true;
    try {
      $this->makeRespaldo();
    } catch (ProcessFailedException $exception) {
      $success = false;
      $error = sprintf("@ERROR-RESPALDO-BD (%s) (%s)", $exception->getMessage(), $this->currentDatabase);
      $this->error($error);
    }

    if ($success) {
      $this->addFilesSqlSave($this->pathSql);
      $this->makeCompress();      
      if ($this->option('subir')) {
        $this->makeSaveAmazon();
      }
    }

    return $success;
  }


  /**
   * Hacer respaldo de la base de datos principal
   * 
   * @return bool
   */
  public function makeRespaldoPrincipal()
  {
    $this->setCurrentDatabase(env('DB_DATABASE', 'forge'));
    return $this->respaldoDB();
  }


  public function getDatabaseName( Empresa $empresa )
  {
    try {
      return $empresa->getDatabase();
    } catch (\Throwable $th) {
      Log::info( sprintf("@ERROR-RESPALDO %s (%s)", $empresa->empcodi, $th->getMessage() ));
      return null;
    }
  }

  /**
   * Hacer respaldo del resto de las bases de datos las empresas
   * 
   * @return bool
   */
  public function makeRespaldoEmpresas()
  {
    $empresas = Empresa::activas()->get();

    foreach ( $empresas as $empresa ) {
      $dbName = $this->getDatabaseName($empresa);
      if( $dbName ){
        $this->setCurrentDatabase( $this->getDatabaseName($empresa));
        $this->respaldoDB();
      }
    }

    $this->deleteFiles();
  }

  public function processDatabaseBackud($databaseName)
  {
    $databases = explode(',', $databaseName);

    foreach ($databases as $dbName) {
      $this->setCurrentDatabase($dbName);
      $this->respaldoDB();
    }

    $this->deleteFiles();
  }

  // Respaldar base de datos principales y de las empresas
  public function handle()
  {
    Log::info('@PROCESS-RESPALDO-DB-START');

    $dbName = strtolower($this->option('database'));


    // Si se especifico una base de datos, respaldar esa bd
    if ($dbName != 'all') {
      return $this->processDatabaseBackud($dbName);
    }
    // De lo contrario respaldar todo
    else {
      $success = $this->makeRespaldoPrincipal();
      if ($success) {
        $this->makeRespaldoEmpresas();
      }
    }

    Log::info('@PROCESS-RESPALDO-DB-END');
  }
}
