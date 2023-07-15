 <section class="section section-sm section-sm-bottom-100 bg-white">
  <div class="container">
    <div class="row">
     
      <div class="col-md-12 wow fadeInUp" style="margin-bottom: 30px ">
        <div class="box-icon-2">
          <h2 class="title-icon title-icon-2">
            <span class="icon icon-default mercury-icon-users"></span> 
            <span>Testimonios</span>
          </h2>
        </div>
      </div> 

      @include('landing.partials.carusel_testimonios', ['images' =>  $testimonios, 'time' => 4000 ])




    </div>
  </div>
</section> 