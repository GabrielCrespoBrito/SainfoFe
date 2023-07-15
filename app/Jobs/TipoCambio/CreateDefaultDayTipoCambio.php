<?php

namespace App\Jobs\TipoCambio;

use App\TipoCambioMoneda;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateDefaultDayTipoCambio
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = date('Y-m-d');

        $tc = TipoCambioMoneda::where('TipFech' , $today )->first();

        if( $tc == null ){
            $tcLast = TipoCambioMoneda::OrderByDesc('TipFech')->first();

            if( $tcLast ){
                $data = $tcLast->toArray();
                $data['TipFech'] = $today;
            }
            else {
                $data['TipFech'] = $today;
                $data['TipComp'] = 0;
                $data['TipVent'] = 0;
            }

            session()->flash( 'save_tipocambio' , true );

            TipoCambioMoneda::create($data);
        }

    }
}
