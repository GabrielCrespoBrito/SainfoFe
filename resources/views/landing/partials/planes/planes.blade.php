<div id="generic_price_table">
  <div class="row">

    @foreach( $planes as $plan )
    
    {{-- @dd( $plan ) --}}

    <div class="col-md-3">
      <div class="generic_content clearfix {{ $plan->is_demo ? 'plan plan-demo' : 'plan plan-pro' }}">

        <div class="generic_head_price clearfix">
          @include('landing.partials.planes.title')
        </div>

        <div class="generic_feature_list">
          @include('landing.partials.planes.caracteristicas')
        </div>

        <div class="generic_price_btn clearfix">
          @include('landing.partials.planes.link')
        </div>

      </div>
    </div>

    @endforeach

  </div>
</div>