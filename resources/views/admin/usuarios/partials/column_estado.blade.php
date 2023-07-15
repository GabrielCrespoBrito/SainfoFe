<?php 
  $str = $active ? "Activo" : "Inactivo";
  $color = $active ? "bg-green" : "bg-red";
?>

<span class="btn btn-xs {{ $color }}">{{ $str }}</span>
