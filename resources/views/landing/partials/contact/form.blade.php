<div class="col-md-10 col-lg-6">
  <h5>Ponerse en contacto</h5>
  <!--RD Mailform  rd-form rd-mailform-->

  <div class="row">
    @include('partials.errors_html')
  </div>


  <form class=" form-contact-sainfo" data-form-output="form-output-global" data-form-type="contact" method="post" action="{{ route('landing.contact_send') }}">

    @csrf

    <div class="row">
      <div class="col-md-12">
        <div class="form-wrap {{ $errors->has('razon_social') ? 'has-error' : '' }}">
          <input class="form-input" id="contact-first-name" required type="text" value="{{ old('razon_social') }}" name="razon_social" data-constraints="@Required">
          @if( $errors->has('razon_social') )
            <span class="form-validation"> {{ $errors->first('razon_social')  }}  </span>
          @endif
          <label class="form-label" for="contact-first-name">Razon Social</label>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-6">
        <div class="form-wrap {{ $errors->has('ruc') ? 'has-error' : '' }}">
          <input class="form-input" name="ruc" required id="contact-ruc" type="number" value="{{ old('ruc') }}" name="name" data-constraints="@Required">
          @if( $errors->has('ruc') )
            <span class="form-validation"> {{ $errors->first('ruc')  }}  </span>
          @endif
          <label class="form-label" for="contact-ruc">RUC *</label>
        </div>
      </div>
     <div class="col-md-6">
        <div class="form-wrap {{ $errors->has('telefono') ? 'has-error' : '' }}">
          <input class="form-input" id="contact-telefono" required type="phone" name="telefono" value="{{ old('telefono') }}" data-constraints="@Required">
          @if( $errors->has('telefono') )
            <span class="form-validation"> {{ $errors->first('telefono')  }}  </span>
          @endif
          <label class="form-label" for="contact-telefono">Telefono  (*)</label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="form-wrap {{ $errors->has('mensaje') ? 'has-error' : '' }}">
          <label class="form-label" for="contact-message">Message</label>
          @if( $errors->has('mensaje') )
            <span class="form-validation"> {{ $errors->first('mensaje')  }}  </span>
          @endif
          <textarea class="form-input" id="contact-message" name="mensaje" data-constraints="@Required">{{ old('mensaje') }} </textarea>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-xl-12">
        <div class="form-wrap {{ $errors->has('email') ? 'has-error' : '' }}">
          <input class="form-input" id="contact-email" type="email" name="email" value="{{ old('email') }}" required data-constraints="@Email @Required">
          @if( $errors->has('email') )
            <span class="form-validation"> {{ $errors->first('email')  }}  </span>
          @endif
          <label class="form-label" for="contact-email">E-mail (*)</label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-xl-12">
        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>      
      </div>
    </div>

    <div class="row">
      <div class="col-md-5 col-xl-4">
        <button class="button button-size-1 button-block button-primary" type="submit">Enviar </button>
      </div>
    </div>
    
    </div>
  </form>
</div>