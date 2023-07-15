<!--Icon List 2-->
<section class="section section-sm section-sm-bottom-100">
  <div class="container">
    
    <div class="row row-30">
      <div class="col-md-12 wow fadeInUp">
        <div class="box-icon-2">
          <h2 class="title" style="text-align: center"> Modulos </h2>
        </div>
      </div>
    </div>

    <div class="row row-30">
      @include('landing.partials.modulos_box_select', ['descripcion' => 'Facturación electroncia', 'active' => true, 'id' => 'modulo_fe' ])
      @include('landing.partials.modulos_box_select', ['descripcion' => 'Guias remisión', 'id' => 'modulo_guias' ])
      @include('landing.partials.modulos_box_select', ['descripcion' => 'Caja', 'id' => 'modulo_caja' ])
      @include('landing.partials.modulos_box_select', ['descripcion' => 'Almacen', 'id' => 'modulo_almacen' ])
    </div>

    <div class="row row-30">
      @component('landing.partials.modulo_descripcion', ['descripcion' => 'Facturación electroncia', 'active' => true, 'id' => 'modulo_fe' ]) 
        
      @slot('body')
          <p class="big"> En el módulo de facturación electrónica podrás enviar tus documentos directamente a la SUNAT y en tiempo real, enviaras tus documentos de facturas, boletas, notas de créditos, notas de débito de manera fácil y sencilla, gracias a la interfaz amigable e intuitiva. </p>
          <p class="big"> Bondades: </p>
          <ul class="list-marked-2">
            <li>	Podrás enviar facturas con detracción. </li>
            <li>	Facturas de anticipo. </li>
            <li>	Factura con percepción. </li>
            <li>	Multi moneda: soles/dólares </li>
            <li>	Enviar tus documentos por email  a tus clientes </li>
            <li>	Búsqueda de DNI/RUC  a la RENIEC </li>
            <li>	Documentos de contingencia </li>
          </ul>
          @endslot

          @slot('image')
            <h1> imagen </h1>
            <div class="box-image-item box-image-video novi-background bg-image" style="background-image: url(page/images/index-874x534.jpg)"><a class="icon novi-icon fa fa-caret-right" href="//www.youtube.com/embed/KFVUxSynSXc" data-lightgallery="item"></a></div>
          @endslot

        @endcomponent

      @component('landing.partials.modulo_descripcion', ['descripcion' => 'Guias remisión', 'id' => 'modulo_guias' ]) 
          @slot('body')

            <p class="big">
              En el módulo de guías  generaras tus guías de remisión e ingreso de tus clientes, las cuales podrás enviar a la SUNAT al momento.
            </p>

            <p class="big">
              También dispondrás de la posibilidad de generar tus guías dinámicamente después de hacer tus compras/ventas, o de forma inversa podrás crear tus guías y ha partir de ellas generar tus documentos de compra/venta.
            </p>

            <p class="big">
              De igual forma que con los comprobantes podras enviar tus guías a tus clientes vía email de manera fácil y sencilla.
            </p>
          @endslot

          @slot('image')
          
            <div class="box-image-item box-image-video novi-background bg-image" style="background-image: url(page/images/index-874x534.jpg)"><a class="icon novi-icon fa fa-caret-right" href="//www.youtube.com/embed/KFVUxSynSXc" data-lightgallery="item"></a></div>

          @endslot

      @endcomponent

      @component('landing.partials.modulo_descripcion', ['descripcion' => 'Caja', 'id' => 'modulo_caja' ]) 
          @slot('body')

            <p class="big">
              En el módulo de caja tendrá acceso y controlara todos los ingresos y egresos  de su empresa, así mismo  dispondrá de informes detallados sobre sus movimientos en un determinado tiempo.
            </p>
            
            <p class="big">
              De la misma manera podrá visualizar los tipos de movimientos filtrado por su tipo, efectivo, bancario, etc, además de esto dispondrá el área de cuentas por pagar donde podrá inspeccionar todas las deudas que tiene en tiempo real.
            </p>

            <p class="big">
              Del mismo modo, contara con la sección de cuentas por cobrar, donde podrá ver de manera clara y sencilla los clientes o proveedores que le son deudores.
            </p>
            
            <p class="big">
              Por ultimo también contara con reportes pormenorizados de todos los movimientos bancarios realizados en el sistema, para que tenga una relación actualizada de toda esta información.
            </p>

          @endslot

          @slot('image')
          
            <div class="box-image-item box-image-video novi-background bg-image" style="background-image: url(page/images/index-874x534.jpg)"><a class="icon novi-icon fa fa-caret-right" href="//www.youtube.com/embed/KFVUxSynSXc" data-lightgallery="item"></a></div>
          @endslot
      @endcomponent

      @component('landing.partials.modulo_descripcion', ['descripcion' => 'Almacen', 'id' => 'modulo_almacen' ]) 
          @slot('body')
            <p class="big">
              En el módulo de almacén, podrá gestionar  toda la disponibilidad de todos tus productos, también dispondrás de reportes en cualquier momento del estado de sus stocks en los diferentes almacenes, de manera que tendrá controlado la cantidad de mercadería que actualmente dispone.
            </p>
            
            <p class="big">
              Todo esto de manera sencilla y amigable.
            </p>
          @endslot

          @slot('image')
          
            <div class="box-image-item box-image-video novi-background bg-image" style="background-image: url(page/images/index-874x534.jpg)"><a class="icon novi-icon fa fa-caret-right" href="//www.youtube.com/embed/KFVUxSynSXc" data-lightgallery="item"></a></div>
          @endslot
      @endcomponent



    </div>

  </div>
</section>