@component('landing.partials.feature', [ 'classSection' => 'bg-gray', 'classColDesc' => 'flex', 'title' => 'Todo listo para tu Apertura Contable 2023', 'img' => asset('page/images/slider/feature_1.jpg')])

  @slot('content')
    <ul class="list-marked-2 list-market-cont">
<li>Proceso Automatizado de Cierre Contable Anual. </li>
<li> Archivos txt para Renta Anual (Balance de comprobación y casillas Sunat). </li>
<li>Estados Financieros SMV-NIIF y Sunat. </li>
<li>Registro automático de XML de ventas y compras validadas. </li>
<li> Flujo de caja proyectado, análisis de gasto y depreciación. </li>
<li> Diferencia de cambio automática. </li>
<li>Importación fácil de compras, ventas, cobranzas, pagos y asientos de diario desde Excel. </li>
<li>Libros Contables sin ningún proceso de centralización. </li>
<li>Backups programados para no perder tu información.</li>
        </ul>
  @endslot
@endcomponent



@component('landing.partials.feature', [ 'classSection' => '', 'classColDesc' => 'flex','title' => 'Seguridad de primer nivel en la nube de AWS', 'img' => asset('page/images/slider/feature_1.jpg') ,'imgRight' => false])

  @slot('content')
    <p >Todas nuestras soluciones SQL para Contadores, se alojan en un
ambiente cloud de Amazon Web Services y están soportadas con
la mayor seguridad. Además, te brindamos la posibilidad de
generar backups programados y almacenarlos en el mismo
ambiente. Nuestra recertificación ISO 27001 y nuestros estándares
de calidad dan fe de ello, podrás trabajar tranquilo y sin
complicaciones.</p>

    <p >AWS Lider en computación en la nube.</p>

  <a class="btn mt-2 btn-primary btn-block" href="https://aws.amazon.com/es/security/" target="_blank"> Información </a>

  @endslot
@endcomponent


@component('landing.partials.feature', [ 'classSection' => 'bg-gray feature-seccion', 'classColDesc' => 'flex', 'title' => 'Disfruta de la nueva Tributación Online', 'img' => asset('page/images/slider/feature_1.jpg') ])

  @slot('content')

<p>Con nuestro Contable SQL podrás disfrutar de la nueva tributación
en línea que te permite: </p>
    <ul class="list-marked-2 list-market-cont">
<li>Consultar online la validez de tus facturas de ventas y compras.</li>
<li>Registrar las ventas y compras validadas de forma automática.</li>
<li>Emitir, validar y contabilizar ventas de forma integrada.</li>
<li>Realizar la liquidación de impuestos automáticamente.</li>
<li>Controlar el IGV, ventas, compras y montos a pagar.</li>
<li>Validar tus detracciones para asegurar el uso del crédito fiscal. </li>
</ul>

  @endslot
@endcomponent


