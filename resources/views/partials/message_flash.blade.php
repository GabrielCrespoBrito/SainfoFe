@if( session()->has('notificacion') )
  <script>
      let titulo = "{!! session()->get('titulo') !!}"
      let message = "{!! session()->get('mensaje') !!}"
      let tipo = "{{ session()->get('tipo') }}"
      notificaciones(message,tipo,titulo);
  </script>
  

@endif