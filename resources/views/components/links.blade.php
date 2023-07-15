<script type="text/javascript">
  @foreach( $links as $name_var => $link )
    var {{ $name_var }} {!! $link == '' ? ';' :  '=' . "'". $link . "'"  !!}; {{ "\n" }}
  @endforeach
</script>