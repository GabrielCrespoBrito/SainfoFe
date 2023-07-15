      <!-- Our Mission-->
      <section class="section bg-default section-cont-features section-md">
        <div class="container">


    <div class="col-md-12 wow fadeInUp" style="margin-bottom: 30px; visibility: visible; animation-name: fadeInUp;">
      <div class="box-icon-2">
        <h2 class="title" style="text-align: center" id="table-precios"> Caracteristicas </h2>
      </div>
    </div>

          <div class="row row-30">

            @foreach( $caracteristicas as $caracteristica )
              <div class="col-md-4">
              @include('landing.partials.contabilidad.card', ['nombre' => $caracteristica->nombre, 'descripcion' => $caracteristica->descripcion ])
              </div>

            @endforeach


            
            {{-- <div class="col-md-4">
            @include('landing.partials.contabilidad.card', ['nombre' => 'Libros Contables', 'descripcion' => 'Libros Contables en línea, sin ningún proceso de centralización, etc'])
            </div>

            <div class="col-md-4">
            @include('landing.partials.contabilidad.card', ['nombre' => 'Nuevo PLE 5.3', 'descripcion' => 'Actualizado al Nuevo PLE 5.3 Nuevo RVIE  '])
            </div> --}}
            
          </div>

          {{-- <div class="row row-30">
            
            <div class="col-md-4">
            @include('landing.partials.contabilidad.card', ['nombre' => 'Integración en linea', 'descripcion' => 'Integra y gestiona en línea, la facturación electrónica de tus clientes'])
            </div>
            
            <div class="col-md-4">
            @include('landing.partials.contabilidad.card', ['nombre' => 'Importación Sencilla', 'descripcion' => 'Importa fácil tus compras, ventas, cobranzas, pasos y asientos de diario'])
            </div>

            <div class="col-md-4">
            @include('landing.partials.contabilidad.card', ['nombre' => 'Tipos de cambio online', 'descripcion' => 'Consulta en línea, el tipo de cambio del mes actual y el histórico'])
            </div>
            
          </div> --}}

        </div>
      </section>
