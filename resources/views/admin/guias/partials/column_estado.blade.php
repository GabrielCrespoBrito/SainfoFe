@php

  $tidcodi = $model->TidCodi;
  $codigo = $model->VtaFMail;
  
  $datas = [
    'div_class' => [
      '0001' => 'aceptada',
      '0002' => 'por_rechazado',
      '0003' => 'anulada',
      '0011' => 'por_enviar',
    ],
    'icon_class' => [
      '0001' => 'fa fa-check-circle',
      '0002' => 'fa-times',
      '0003' => 'fa fa-ban',
      '0011' => 'fa fa-spin fa-spinner spinner',
    ],
    'text' => [
      '0001' => 'Aceptado',
      '0002' => 'Rechazado',
      '0003' => 'Anulado',
      '0011' => 'Por Enviar',
    ],
  ];

  $class_name = $datas['div_class'][$codigo];
  $icon_class = $datas['icon_class'][$codigo];
  $text = $datas['text'][$codigo];

@endphp



<a href="#" class="btn btn-xs {{ $class_name }}">
  <span class="fa {{ $icon_class }}"></span>
  {{ $text }}
</a>


