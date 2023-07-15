<section class="testimonio-cont">
			<div class="container">
				<h2 class="text-center">Que dicen nuestros aliados de nosotros </h2>
				<hr class="midline">
				<h5 class="text-center mb-3">Nuestro equipo te ayudara a alcanzar los objetivos y metas de tu empresa</h5>
			  	<div class="card col-md-12 mt-2">
			      	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="100000">
			        	<div class="w-100 carousel-inner mb-5" role="listbox">

                @foreach( $testimonios_group as $testimonios )
			          		<div class="carousel-item  {{ $loop->first ? 'active' : '' }}">
			            		<div class="bg"></div>
			            		<div class="row">
                      @foreach( $testimonios as $testimonio )
				            		<div class="col-md-6">
				            			<div class="carousel-caption">
					              			<div class="row">
								                <div class="col-sm-3 col-4 align-items-start">
								                  	<img src="{{ $testimonio->pathImage() }}" alt="" class="rounded-circle img-fluid">
								                </div>
								                <div class="col-sm-9 col-8">
								                <h2>{{ $testimonio->representante }} - <span>{{ $testimonio->cargo }}</span></h2>
								                  <small class="descripcion">{{ $testimonio->testimonio_text }}</small>
								                </div>
					              			</div>
					            		</div>
				            		</div>
                      @endforeach
			            		</div>
			          		</div>

                @endforeach
			    		</div>

              @if( $testimonios_group->count() > 1 )
				        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
				          <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
				          <span class="sr-only">Previous</span>
				        </a>
				        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
				          <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
				          <span class="sr-only">Next</span>
				        </a>
              @endif

			  		</div>
				</div> 
			</div>
		</section>