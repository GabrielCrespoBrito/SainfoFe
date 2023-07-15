<div class="row">

  @foreach( $stats as  $nameField => $stat )


  @include('admin.home.stat_element', [
    'class_name' => 'col-lg-3 col-xs-6 stats stats-' . $nameField,

    'descripcion' => $stat['read_name'],
    'valor' => $stat['value'],
    'link' => $stat['link'],
  ])

  @endforeach  

</div>