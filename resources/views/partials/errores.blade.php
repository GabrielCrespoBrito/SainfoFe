@if( $errors->count() )  

  <script type="text/javascript">
    $(document).ready(function(){
      let $errors = [];
      console.log( @json($errors) )
      @foreach( $errors->all() as $error )
        $errors.push( "{!! $error !!}" );
      @endforeach
      notificaciones( $errors , "error" , "Información invalida" );
    })
  </script>

@endif
