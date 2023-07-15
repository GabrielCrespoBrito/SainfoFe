@extends('layouts.master')

@section('bread')
<li>  Contratas </li>
@endsection

@add_assets(['libs' => ['froala_editor'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', 'Contrata')

@section('contenido')


  <div id='edit' style="margin-top: 30px;">
		<h1>Full Featured</h1>

		<p>This is the full featured Froala WYSIWYG HTML editor.</p>

		<img class="fr-fir fr-dii" src="../../img/photo1.jpg" alt="Old Clock" width="300"/>Lorem  <strong>ipsum</strong> dolor sit amet, consectetur <strong>adipiscing <em>elit.</em> Donec</strong> facilisis diam in odio iaculis blandit. Nunc eu mauris sit amet purus <strong>viverra</strong><em> gravida</em> ut a dui.<br/>
		<ul><li>Vivamus nec rutrum augue, pharetra faucibus purus. Maecenas non orci sagittis, vehicula lorem et, dignissim nunc.</li> <li>Suspendisse suscipit, diam non varius facilisis, enim libero tincidunt magna, sit amet iaculis eros libero sit amet eros. Vestibulum a rhoncus felis.<ol><li>Nam lacus nulla, consequat ac lacus sit amet, accumsan pellentesque risus. Aenean viverra mi at urna mattis fermentum.</li> <li>Curabitur porta metus in tortor elementum, in semper nulla ullamcorper. Vestibulum mattis tempor tortor quis gravida. In rhoncus risus nibh. Nullam condimentum dapibus massa vel fringilla. Sed hendrerit sed est quis facilisis. Ut sit amet nibh sem. Pellentesque imperdiet mollis libero.</li></ol></li></ul>
  </div>


@endsection

