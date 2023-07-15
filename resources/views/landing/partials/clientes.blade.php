<!--Icon List 2-->
<section class="section section-sm section-sm-bottom-100 bg-primary">
  <div class="container">
    <div class="row">
     
      <div class="col-md-12 wow fadeInUp" style="margin-bottom: 30px ">
        <div class="box-icon-2">
          <h2 class="title-icon title-icon-2"><span class="icon icon-default mercury-icon-partners"></span><span>Algunos de nuestros <span class="text-light">Clientes</span></span></h2>
        </div>
      </div> 

      @foreach( $clientesGroup as $clientes )
          @include('landing.partials.carusel_cliente', ['images' =>  $clientes, 'time' => 4000 ])
      @endforeach


{{-- 
      @include('landing.partials.carusel', ['images' => [ 
        ['src' =>  asset('page/images/logos-clientes/1.png'), 'href' => 'http://aceper.com.pe/', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/2.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/3.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/4.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/5.png'), 'href' => 'http://akani.com.pe/', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/6.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/7.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/8.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/9.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/10.png'), 'href' => 'https://umina.pe' ,'text' => '' ],        
        ],
        'time' => 4000,
      ])

      @include('landing.partials.carusel', ['images' => [ 
        ['src' =>  asset('page/images/logos-clientes/9.png'), 'href' => '#','text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/10.png'), 'href' => 'https://umina.pe' , 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/11.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/12.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/13.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/14.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/15.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/16.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/17.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/18.png'), 'href' => '#', 'text' => '' ],        
        ],
        'time' => 7000, 
      ])

      @include('landing.partials.carusel', ['images' => [ 
        ['src' =>  asset('page/images/logos-clientes/25.png'), 'href' => 'https://amir3v.com/', 'text' => '' ],     
        ['src' =>  asset('page/images/logos-clientes/26.png'), 'href' => 'https://oxifam.com.pe/', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/28.png'), 'href' => 'https://grupoovalle.com.pe/', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/20.png'), 'href' => 'http://rlinsercom.com/', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/18.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/19.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/21.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/22.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/23.png'), 'href' => '#', 'text' => '' ],
        ['src' =>  asset('page/images/logos-clientes/24.png'), 'href' => '#', 'text' => '' ],
      ],
        'time' => 10000, 
      ]) --}}


    </div>
  </div>
</section>