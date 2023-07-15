@component('landing.partials.contabilidad.slider_comp')

  @slot('content')

    @component('landing.partials.contabilidad.slider_item', ['img' => asset('page/images/slider/cont_slider_1.jpg')])
      @slot('content')
        {{-- <h1><span class="d-block" data-caption-animate="fadeInUp" data-caption-delay="100">Cierre Anual Contable Facil y Sencillo</span></h1> --}}
      @endslot
    @endcomponent

    @component('landing.partials.contabilidad.slider_item', ['img' => asset('page/images/slider/cont_slider_2.jpg')])
      @slot('content')
      @endslot
    @endcomponent

    @component('landing.partials.contabilidad.slider_item', ['img' => asset('page/images/slider/cont_slider_3.jpg')])
      @slot('content')
      @endslot
    @endcomponent


  @endslot
@endcomponent