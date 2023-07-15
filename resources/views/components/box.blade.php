@php
  $className = $className ?? 'info';
  $remove = $remove ?? false;
  $header = $header ?? '';
  $body = $body ?? '';

@endphp

<div class="box box-{{ $className }}">
  <div class="box-header with-border">
    
    <h3 class="box-title"> {!! $header !!} </h3>
    
    @if($remove)
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    @endif

    <!-- /.box-tools -->
  </div>
  <!-- /.box-header -->
  @if( $body != "" )
  <div class="box-body">
    {!! $body !!}
  </div>
  @endif
  <!-- /.box-body -->

</div>