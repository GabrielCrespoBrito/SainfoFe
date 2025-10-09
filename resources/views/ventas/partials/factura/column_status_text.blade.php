@php
use App\ModuloMonitoreo\StatusCode\StatusCode;

if( isset(StatusCode::CODES[$VtaFMail]) )
{
    logger( $VtaNume . " - " . $VtaFMail);
}

@endphp


{{ StatusCode::CODES[$VtaFMail] ?? "Sin estado" }}
