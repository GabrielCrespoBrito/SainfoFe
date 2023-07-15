<div> 
  <h5> Series </h5>

  <div class="parent-main">   

    @if($isCreate)
      @include('modulo_monitoreo.empresas.partials.serie_component', ['isPrincipal' => true, 'serieSelected' => 'F001',  'tipoSelected' => '01',  'serie' => $serie_fake  ])

    @include('modulo_monitoreo.empresas.partials.serie_component', ['isPrincipal' => false, 'serieSelected' => 'B001',  'tipoSelected' => '03',  'serie' => $serie_fake  ])

    @include('modulo_monitoreo.empresas.partials.serie_component', ['isPrincipal' => false, 'serieSelected' => 'F001',  'tipoSelected' => '07',  'serie' => $serie_fake  ])    

    @include('modulo_monitoreo.empresas.partials.serie_component', ['isPrincipal' => false, 'serieSelected' => 'F001',  'tipoSelected' => '08',  'serie' => $serie_fake  ])    

    @else
      @foreach( $empr->series as $serie )
        @include('modulo_monitoreo.empresas.partials.serie_component', ['isPrincipal' => $loop->first, 'serie' => $serie  ])
      @endforeach
    @endif

  </div>

</div>