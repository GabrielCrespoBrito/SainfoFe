<!--Icon List 2-->
<section class="section section-bene section-sm section-sm-bottom-100 bg-primary">
  <div class="container">
    <div class="row box-icon-1-wrap">

      <div class="col-md-12 wow fadeInUp" style="margin-bottom: 30px ">
        <div class="box-icon-2">
          <h2 class="title" style="text-align: center"> Beneficios de trabajar con nosotros </h2>
        </div>
      </div>

      @include('landing.partials.service_box',['title' => 'Facturación electrónica', 'descripcion' => 'Factura electrónicamente de manera facil y sencilla, envia tus documentos en tiempo real a la SUNAT', 'icon' => 'mercury-icon-cloud-2'])

      @include('landing.partials.service_box',['title' => 'Respaldo en todo momento', 'descripcion' => 'Tanto usted como sus clientes tendrán acceso 24h a sus documentos ya que son respaldados diariamente en la robusta infraestructura de Amazon - AWS', 'delay' => 0.2, 'icon' => 'mercury-icon-cloud-2' ])

      @include('landing.partials.service_box',['title' => 'Atención personalizada', 'descripcion' => 'Nos caracterizamos por brindar una atención de primera a todos nuestros clientes, ayudándolos en todas las etapas de la utilización del sistema', 'delay' => 0.4, 'icon' => 'mercury-icon-group'])

      @include('landing.partials.service_box',['title' => 'Facil e intuitivo', 'descripcion' => 'Con nuestro sistema encontrara una interfaz intuitiva y amigable con la cual le sera sencillo habituarse en la utilización de todas partes del sistema.', 'icon' => 'mercury-icon-target'])

      @include('landing.partials.service_box',['title' => 'Reportes', 'descripcion' => 'Dispondra en reporte detallados en PDF, Excell, en linea, por lo siempre estara a pocos clicks de toda su información pormenorizada', 'delay' => 0.2, 'icon' => 'mercury-icon-document-search' ])

      @include('landing.partials.service_box',['title' => 'Innovación', 'descripcion' => 'En SAINFO nos caracterizamos por ir a al vanguardia para llevar a nuestros clientes periodicamente nuevas funcionalidades y mejoras en nuestro sistema.', 'delay' => 0.4, 'icon' => 'mercury-icon-gears'])

    </div>
  </div>
</section>