@php
@endphp

  <div class="col-md-6 container-grafica-parent">
  <div class="title-graphic" id="title-graphic-{{$tipo}}">
      <span 
      class="title-g"
      style="text-align: center;display: inline-block;position: absolute;left: 40%"> 
        <span class="cifra"> </span>
      </span>

      @include('dashboard.partials.currency_btn', ['tipo' => $tipo , 'moneda' => '02'  ])
      @include('dashboard.partials.currency_btn', ['tipo' => $tipo , 'moneda' => '01', 'active' => true ])
  </div>

    <div class="container-graphic-{{ $tipo }}">
      <canvas height="100px" id="graphic-{{ $tipo }}"></canvas>
    </div>

  </div>