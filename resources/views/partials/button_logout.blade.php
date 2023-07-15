@php
  $id = $id ?? 'form-logout';
  $clases = $clases ?? 'btn-default btn-block btn-flat';
  $textBtn = $textBtn ?? 'Salir';
@endphp

<a 
href="#" 
onclick="event.preventDefault();document.getElementById('{{ $id }}').submit();" 
class="btn {{ $clases }}"> 
  {{ $textBtn }} 
</a>