<div class="row home">
  @foreach( $stats as $nameField => $stat )
    @include('admin.home.info_2_element', [
    'class_name' => 'col-md-3 col-sm-6 col-xs-12 info info-' . $nameField,
    'descripcion' => __('messages.stats.' . $nameField),
    'valor' => $stat ,
    ])
  @endforeach
</div>