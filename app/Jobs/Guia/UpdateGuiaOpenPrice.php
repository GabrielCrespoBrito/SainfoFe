<?php

namespace App\Jobs\Guia;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateGuiaOpenPrice
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($guia, $request)
    {
        $this->guia = $guia;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $items_guia = $this->guia->items;
        $items_request = $this->request->items;


        
        
        foreach($items_request as $item_request ){

            $precio = $item_request['DetPrec'];

            $item_guia = $items_guia->where('Linea' , $item_request['Linea'])->first();
            $importe = decimal($item_guia->Detcant * $precio, 2);;

            $item_guia->update([
                'DetPrec' => $precio,
                'DetImpo' => $importe,
            ]);
        }
    }
}
