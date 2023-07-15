@php
  $id = $id ?? "";
  $url = $url ?? "";
  $attrs = $attrs ?? "";
  $attributes = $attributes ?? [];
  $class_name = $class_name ?? "";
  $container_class = $container_class ?? "";
@endphp

<div class="col-md-12 col-xs-12 wrapper-table-responsive {{ $container_class }}">

  <table
    data-url="{{ $url }}" 
    class="table {{ $class_name }} sainfo-table" {{ $attrs }} 
    @foreach( $attributes as $key => $value )
      {!! $key !!} = "{{ $value }}"
    @endforeach
    width="100%"
    id="{{ $id }}">
    {{-- Head --}}
    {{-- Head --}}
    <thead>
      <tr>
        @foreach( $thead as $td )
        
        @if( is_array($td) )
          <td
          @if( isset($td['attributes']) )
            @foreach( $td['attributes'] as $key => $value  )
              {{ $key }}="{{ $value }}"
            @endforeach
          @endif
          class="{{ $td['class_name'] }}"> 
          {!! $td['text'] !!}           
          </td>
        @else
          <td> {!! $td !!} </td>
        @endif

        @endforeach        
      </tr>
    </thead>

    {{-- Body --}}
    @isset($body)
    <tbody>
      {{ $body }}
    </tbody>
    @endisset

    {{-- Foot --}}
    @isset($tfoot)
    <tfoot>
      {{ $tfoot }}
    </tfoot>
    @endisset

  </table>
</div>