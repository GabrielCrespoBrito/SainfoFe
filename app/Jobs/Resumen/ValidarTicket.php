<?php

namespace App\Jobs\Resumen;

use App\Resumen;

class ValidarTicket 
{
    public $sent;
    public $resumen;
    protected $fieldsToExtract = ['ResponseCode','Description'];

    public function __construct( array $sent, Resumen $resumen , $isAnulacion)
    {
        $this->sent = $sent;
        $this->resumen = $resumen;
        $this->isAnulacion = $isAnulacion;
    }

    public function handle()
    {
        $nameFile = $this->resumen->nameFile(true, '.xml');
        
        $data = extraer_from_content( $this->sent['content'] , $nameFile, $this->fieldsToExtract);

        if ($this->isAnulacion ) {
            $this->resumen->successAnulacion($ticket);
        }

        if ($this->sent['status']) {
            $this->resumen->successValidacion($data);
        } else {
            $this->resumen->errorValidacion($data);
        }

        $this->sent['content'] = "";
        $this->sent['message'] = $resumen->DocDesc;
        $this->sent['ticket'] = $ticket;

        return $this->sent;
    }
}
