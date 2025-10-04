<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nexmo text message data
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */
    'nexmo' => [
      'user' =>   env('NEXMO_USER', 'f5d926af' ),
      'api_key' =>   env('NEXMO_API_KEY' , '64WHNvBkRzKL2KvZ')
    ],

    /*
    |--------------------------------------------------------------------------
    | Migo api consulta de documentos, ruc, dni, tipo de cambios, etc
    |--------------------------------------------------------------------------
    |
    */
    'migo' => [
      'token'   =>   env('MIGO_API', 'V7UUp9zEnHP4tnXMGKeHyOAqhUK2V3sVgbgXDZPAa8o36F8yVJKTlxzLxxMZ'),
      'url_dni' => env('MIGO_URL_DNI', 'https://api.migo.pe/api/v1/dni'),
      'url_ruc' => env('MIGO_URL_RUC', 'https://api.migo.pe/api/v1/ruc'),
      'url_tc_latest'  => env('MIGO_URL_TC_LATEST', 'https://api.migo.pe/api/v1/exchange/latest'),
      'url_tc_date'  => env('MIGO_URL_TC_DATE', 'https://api.migo.pe/api/v1/exchange/date'),
      'url_agente_retencion' => env('MIGO_URL_AGENTE_RETENCION', 'https://api.migo.pe/api/v1/ruc/agentes-retencion'),
    ],

];
