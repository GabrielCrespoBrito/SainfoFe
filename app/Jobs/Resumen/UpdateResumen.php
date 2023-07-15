<?php

namespace App\Jobs\Resumen;

use App\Resumen;
use App\ResumenDetalle;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateResumen
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $request;
    protected $resumen;

    public function __construct( Resumen $resumen, $request)
    {
        $this->request = $request;
        $this->resumen = $resumen;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			$this->resumen->update([
				'DocFechaE' => $this->request->fecha_generacion,
				'DocFechaD' => $this->request->fecha_documento,
				'DocTicket' => $this->request->ticket,
				'DocNume' => $this->request->docnume,
			]);

      ResumenDetalle::createDetalle($this->resumen, $this->request->ids, true);
    }
}
