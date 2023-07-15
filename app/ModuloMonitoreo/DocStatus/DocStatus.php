<?php

namespace App\ModuloMonitoreo\DocStatus;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use Illuminate\Database\Eloquent\Model;

class DocStatus extends Model
{
    protected $table = "monitor_documentos_status";
    protected $guarded = [];
    // monitor_documentos_status
    public function getColor()
    {
        return "success";
    }

    public function status()
    {
        return $this->belongsTo( StatusCode::class , 'status_id' , 'id' )->withDefault();
    }

}