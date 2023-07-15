@php
$nombre = $nombre ?? '';
$descripcion = $descripcion ?? '';
@endphp

<div class="solution_cards_box">
<div class="solution_card" data-color="{{ $caracteristica->card_color }}">
<div class="hover_color_bubble" ></div>
<div class="so_top_icon" style="color:{{ $caracteristica->icon_color }}">
{!! $caracteristica->icon !!}
</div>
<div class="solu_title">
<h3>{{ $nombre }}</h3>
</div>
<div class="solu_description">
<p>
{!! $descripcion  !!}
</p>
</div>
</div>

</div>