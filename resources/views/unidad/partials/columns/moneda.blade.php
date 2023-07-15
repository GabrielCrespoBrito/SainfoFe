@php
  $moneda = App\Moneda::getAbrev($model->moncodi);
@endphp

{{ $moneda }}