<footer class="section footer-classic">
  <div class="footer-inner-1">
    <div class="container">
      <div class="row row-40">
        <div class="col-md-5 col-lg-3">
          <h5>Nuestro contacto</h5>
          <ul class="contact-list font-weight-bold">
            <li>
              <div class="unit unit-spacing-xs">
                <div class="unit-left">
                  <div class="icon icon-sm icon-primary novi-icon mdi mdi-map-marker"></div>
                </div>
                <div class="unit-body"><a href="#">Av. Guillermo Billinghurst 1135 <br>Lima - Lima - SJM</a></div>
              </div>
            </li>
            <li>
              <div class="unit unit-spacing-xs">
                <div class="unit-left">
                  <div class="icon icon-sm icon-primary novi-icon mdi mdi-phone"></div>
                </div>
                <div class="unit-body"> <a href="tel:998840052">998-840-052</a> / <a href="tel:930483430">936-525-581</a> </div>
              </div>
            </li>
            <li>
              <div class="unit unit-spacing-xs">
                <div class="unit-left">
                  <div class="icon icon-sm icon-primary novi-icon mdi mdi-clock"></div>
                </div>
                <div class="unit-body">
                  <ul class="list-0">
                    <li>Lun-Sab: 9:00–18:00</li>
                    <li>Dom: Cerrado</li>
                  </ul>
                </div>
              </div>
            </li>

            <li>
              <div class="unit unit-spacing-xs">
                <div class="unit-left">
                  <div class="icon icon-sm icon-primary novi-icon mdi mdi-email"></div>
                </div>
                <div class="unit-body"> <a href="mailto:ventas@sainfo.pe">ventas@sainfo.pe</a> </div>
              </div>
            </li>

            <li class="list-redes">
              <a target="_blank" href="https://www.facebook.com/SainfoSoftware" class="facebook"> <span class="fa fa-facebook"></span> </a>
              <a target="_blank" href="https://www.instagram.com/sainfosoft" class="instagram"> <span class="fa fa-instagram"></span> </a>
            </li>


          </ul>
        </div>
        <div class="col-md-6 col-lg-5">
          <h5>Enlaces</h5>
          <div class="row row-5 justify-content-between">
            <div class="col-sm-6">
              <ul class="footer-list big">
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Nosotros</a></li>
                <li><a href="#">Contacto</a></li>
              </ul>
            </div>

            <div class="col-sm-4 col-lg-6 col-xxl-5">
              <ul class="footer-list big">
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                <li><a href="{{ route('register') }}">Crear cuenta</a></li>
                <li><a href="{{ route('busquedaDocumentos') }}">Consultar documento</a></li>
              </ul>
            </div>

          </div>
        </div>

        <div class="col-md-12 col-lg-4">
          <h5>Partners</h5>
          <div class="row row-30 align-items-center text-center">
            <div class="div-link-socio-footer col-6 col-md-4 col-lg-6"><a class="link-image" href="#"><img src="{{ asset('page/images/socios/aws.png') }}" alt="" width="144" height="35" /></a></div>
            <div class="div-link-socio-footer col-6 col-md-4 col-lg-6"><a class="link-image" href="#"><img src="{{ asset('page/images/socios/llamape.png') }}" alt="" width="144" height="35" /></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-inner-2">
    <div class="container">
      <p class="rights"><span>&copy;&nbsp;</span><span class="copyright-year"></span>. Sainfo - Todos los derechos reservados.
      </p>
    </div>
  </div>

</footer>