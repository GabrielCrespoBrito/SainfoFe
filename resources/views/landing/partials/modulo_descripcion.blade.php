@php
  $active = $active ?? false;
  $src_image = $src_image ?? 'index-874x534.jpg';

  // dd($active);
@endphp

<!-- Download Our Tax Guide App-->
<section class="section section-modulo bg-gray-100 box-image-left" id="{{ $id }}" style="display:{{ !$active ? 'none' : 'inherit' }} !important">
  <div class="container">
    <div class="row">
      <div class="col-md-6 {{ $active ? 'wow fadeInLeft' : '' }} ">
        <div class="section-lg">
          {{ $body }}
        </div>
      </div>

    </div>
  </div>

  {{ $image }}

</section>