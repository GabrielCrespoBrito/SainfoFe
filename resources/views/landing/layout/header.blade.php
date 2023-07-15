<header class="section page-header rd-navbar-transparent-wrap">
  <!--RD Navbar-->
  <div class="rd-navbar-wrap">
    <nav class="rd-navbar rd-navbar-transparent" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-static" data-xl-layout="rd-navbar-static" data-xl-device-layout="rd-navbar-static" data-lg-stick-up-offset="20px" data-xl-stick-up-offset="20px" data-xxl-stick-up-offset="20px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
      <div class="rd-navbar-collapse-toggle rd-navbar-fixed-element-1" data-rd-navbar-toggle=".rd-navbar-collapse"><span></span></div>
      <div class="rd-navbar-aside-outer rd-navbar-collapse">
        <div class="rd-navbar-aside">
          <div class="rd-navbar-info">
            <div class="icon novi-icon mdi mdi-phone">
            </div>
            <a style="color:white" href="tel:998840052">998-840-052</a> / <a style="color:white" href="tel:930483430">936-525-581</a>

            <a class="no-xs" target="_blank" href="https://www.facebook.com/SainfoSoftware">
            <div style="margin-left:20px" class="facebook-colors icon novi-icon mdi mdi-facebook">
            </div>
            </a>
            
            <a class="no-xs" target="_blank" href="https://www.instagram.com/sainfosoft">
              <div class="icon novi-icon mdi mdi-instagram"></div>
            </a>


          </div>
          <ul class="list-lined">

            <li class="login"><a href="{{ route('login') }}">Ingresa</a></li>            

            <li class="consult"><a href="{{ route('busquedaDocumentos') }}"> <span class="fa fa-file-text-o"></span> Consultar documentos </a></li>


          </ul>
        </div>
      </div>
      <div class="rd-navbar-main-outer">
        <div class="rd-navbar-main-inner">
          <div class="rd-navbar-main">
            <!--RD Navbar Panel-->
            <div class="rd-navbar-panel">
              <!--RD Navbar Toggle-->
              <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
              <!--RD Navbar Brand-->
              <div class="rd-navbar-brand">
                <!--Brand--><a class="brand" href="index.html"><img class="brand-logo-dark" src="{{ asset('page/images/logo.png') }}" alt="" width="146" height="22" /><img class="brand-logo-light" src="{{ asset('page/images/logo.png') }}" alt="" width="155" height="22" /></a>
                {{-- <!--Brand--><a class="brand" href="index.html"><img class="brand-logo-dark" src="{{ asset('page/images/logo-default-293x44.png') }}" alt="" width="146" height="22"/><img class="brand-logo-light" src="{{ asset('page/images/logo-inverse-310x44.png') }}" alt="" width="155" height="22" /></a> --}}
              </div>
            </div>
            <div class="rd-navbar-main-element">
              <div class="rd-navbar-nav-wrap">
                <ul class="rd-navbar-nav">
                  
                  <li class="rd-nav-item {{ request()->is('/') ? 'active' : ''  }}"><a class="rd-nav-link" href="{{ route('landing.index') }}">Inicio</a> </li>

                  <li class="rd-nav-item {{ request()->is('contabilidad') ? 'active' : ''  }}"><a class="rd-nav-link" href="{{ route('landing.contabilidad') }}"> Contabilidad </a> </li>

                  {{-- <li class="rd-nav-item"><a class="rd-nav-link" href="#table-precios"> Precios </a> </li> --}}
                  {{-- #segunda-seccion --}}
                  
                  <li class="rd-nav-item {{ request()->is('contacto') ? 'active' : ''  }}"><a class="rd-nav-link" href="{{ route('landing.contact') }}">Contacto</a> </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
  @isset($image_header)
  <div class="rd-navbar-bg novi-background bg-image" style="background-image: url(images/bg-navbar.jpg)"></div>
  @endisset
</header>