<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DbsBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--force : Forzar el respaldo sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta el script de respaldo de base de datos configurado';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('🔄 Iniciando proceso de respaldo de base de datos...');
        
        // Obtener la ruta del script desde la configuración
        $backupScript = config('app.backup_script');
        
        if (empty($backupScript)) {
            $this->error('❌ No se ha configurado el script de respaldo.');
            $this->line('Por favor, configure la variable BACKUP_SCRIPT en su archivo .env');
            return 1;
        }

        // Verificar si el archivo existe
        if (!file_exists($backupScript)) {
            $this->error("❌ El script de respaldo no existe en la ruta: {$backupScript}");
            return 1;
        }

        // Confirmar ejecución si no se usa --force
        if (!$this->option('force')) {
            if (!$this->confirm('¿Está seguro de que desea ejecutar el respaldo de la base de datos?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        try {
            $this->info("📁 Ejecutando script: {$backupScript}");
            
            // Detectar el sistema operativo y ejecutar el comando apropiado
            $result = $this->executeBackupScript($backupScript);
            
            if ($result['success']) {
                $this->info('✅ Respaldo completado exitosamente');
                $this->line("📊 Tiempo de ejecución: {$result['execution_time']} segundos");
                
                if (!empty($result['output'])) {
                    $this->line('📝 Salida del script:');
                    $this->line($result['output']);
                }
                
                // Log del éxito
                Log::info('Respaldo de base de datos completado exitosamente', [
                    'script' => $backupScript,
                    'execution_time' => $result['execution_time']
                ]);
                
                return 0;
            } else {
                $this->error('❌ Error durante la ejecución del respaldo');
                $this->error("Código de salida: {$result['exit_code']}");
                
                if (!empty($result['error'])) {
                    $this->error("Error: {$result['error']}");
                }
                
                // Log del error
                Log::error('Error durante el respaldo de base de datos', [
                    'script' => $backupScript,
                    'exit_code' => $result['exit_code'],
                    'error' => $result['error']
                ]);
                
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error inesperado: {$e->getMessage()}");
            
            // Log del error inesperado
            Log::error('@Error DbsBackup inesperado durante el respaldo', [
                'script' => $backupScript,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }

    /**
     * Ejecuta el script de respaldo según el sistema operativo
     *
     * @param string $scriptPath
     * @return array
     */
    private function executeBackupScript($scriptPath)
    {
        $startTime = microtime(true);
        
        // Detectar el sistema operativo
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            // Para Windows
            $command = "cmd /c \"{$scriptPath}\"";
        } else {
            // Para Linux/Unix
            // Verificar si el script es ejecutable
            if (!is_executable($scriptPath)) {
                // Intentar hacer el script ejecutable
                chmod($scriptPath, 0755);
            }
            $command = "bash \"{$scriptPath}\"";
        }
        
        $this->line("🔧 Comando a ejecutar: {$command}");
        
        // Ejecutar el comando
        $output = [];
        $returnCode = 0;
        
        exec($command . ' 2>&1', $output, $returnCode);
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        $outputString = implode("\n", $output);
        
        return [
            'success' => $returnCode === 0,
            'exit_code' => $returnCode,
            'output' => $outputString,
            'error' => $returnCode !== 0 ? $outputString : null,
            'execution_time' => $executionTime
        ];
    }
}
