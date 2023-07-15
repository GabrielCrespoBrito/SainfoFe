@php
$closeButton = $closeButton ?? true;
$idContent = $idContent ?? '';
$backdrop = $backdrop ?? 'false';
$keyboard = $keyboard ?? '';
@endphp

<div class="modal fade" id="{{ $id }}" data-backdrop="{{ $backdrop }}" data-keyboard="{{ $keyboard }}">
  <div class="modal-dialog {{ $size ?? 'modal-lgg' }}">
    <div class="modal-content">

      {{-- Header --}}
      <div class="modal-header">
        @if( isHtmlStringInstance($title) )
        {!! $title !!}
        @else

        @if($closeButton)
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        @endif

        <h4 class="modal-title">{{ $title }}</h4>

        @endif

      </div>

      {{-- Body --}}
      <div class="modal-body" id="{{ $idContent }}" style="{{ $overflow ?? 'overflow:hidden'  }}">
        {{ $body }}
      </div>

      {{-- Footer --}}
      @isset($footer)
      <div class="modal-footer">
        {{ $footer }}
      </div>
      @endisset

    </div>
  </div>
</div>