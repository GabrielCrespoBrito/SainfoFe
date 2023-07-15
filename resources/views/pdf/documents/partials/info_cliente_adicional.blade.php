@php

$class_name = $class_name ?? "";
$medio_pago_nombre = $medio_pago_nombre ?? null;
$table_class = $table_class ?? '';
$right_text_class = $right_text_class ?? '';

// Nombre
$nombre = $nombre ?? "";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';

// Direccion
$direccion = isset($direccion) ? $direccion : '';
$direccion_div_class = $direccion_div_class ?? '';
$direccion_campo_nombre = $direccion_campo_nombre ?? '';
$direccion_campo_class = $direccion_campo_class ?? '';
$direccion_text_class = $direccion_text_class ?? '';

// Correo
$documento = $documento ?? "";
$documento_div_class = $documento_div_class ?? '';
$documento_campo_nombre = $documento_campo_nombre ?? '';
$documento_campo_class = $documento_campo_class ?? '';
$documento_text_class = $documento_text_class ?? '';


// Contacto
$contacto = $contacto ?? "";
$contacto_div_class = $contacto_div_class ?? '';
$contacto_campo_nombre = $contacto_campo_nombre ?? '';
$contacto_campo_class = $contacto_campo_class ?? '';
$contacto_text_class = $contacto_text_class ?? '';

// Vendedor
$vendedor = $vendedor ?? "";
$vendedor_div_class = $vendedor_div_class ?? '';
$vendedor_campo_nombre = $vendedor_campo_nombre ?? '';
$vendedor_campo_class = $vendedor_campo_class ?? '';
$vendedor_text_class = $vendedor_text_class ?? '';

$fecha_emision = $fecha_emision ?? "";
$fecha_emision_div_class = $fecha_emision_div_class ?? '';
$fecha_emision_campo_nombre = $fecha_emision_campo_nombre ?? '';
$fecha_emision_campo_class = $fecha_emision_campo_class ?? '';
$fecha_emision_text_class = $fecha_emision_text_class ?? '';

@endphp

<div class="{{ $class_name }}">

  <table class="width-100 font-size-9 text-uppercase vertical-align-top {{ $table_class }}">

    <tr>
      <!-- Nombre Cliente -->
      <td class="width-70 pb-x5 pb-x5">
        <div class="nombre_div_class">
          <span class="bold"> SEÑOR(ES): </span>
          <span class=""> {{ $nombre }} </span>
        </div>
      </td>
      <!-- /Nombre Cliente -->

      <!-- Fecha emisión -->
      <td class="width-30 pb-x5 pb-x5">
        <div class="nombre_div_class">
          <span class="bold">Fecha emisión:</span>
          <span class="{{ $right_text_class }}"> {{ isset($venta) ? $venta["VtaFvta"] : '' }}
          </span>
        </div>
      </td>
      <!-- Fecha emisióin -->
    </tr>


    <tr>
      <!-- RUC -->
      <td class="width-70 pb-x5">
        <div class="nombre_div_class">
          <span class="bold"> {{ $documento_campo_nombre }} </span>
          <span class=""> {{ $cliente->PCRucc }} </span>
        </div>
      </td>
      <!-- /RUC -->

      <!-- Moneda -->
      <td class="width-30 pb-x5">
        <div class="nombre_div_class">
          <span class="bold">Moneda: </span>
          <span class="{{ $right_text_class }}"> {{ $moneda_abreviatura }}
          </span>
        </div>
      </td>
      <!-- Moneda -->
    </tr>


    <tr>
      <!-- Dirección -->
      <td class="width-70 pb-x5">
        <div class="nombre_div_class">
          <span class="bold"> Dirección: </span>
          <span class=""> {!! $direccion !!} </span>
        </div>
      </td>
      <!-- /RUC -->

      <!-- Forma de Pago -->
      <td class="width-30 pb-x5">
        <div class="nombre_div_class">
          <span class="bold">Forma de Pago:</span>
          <span class="{{ $right_text_class }}"> {{ optional($forma_pago)->connomb . return_strs_if($medio_pago_nombre, '-' , $medio_pago_nombre)  }}
          </span>
        </div>
      </td>
      <!-- Forma de Pago -->
    </tr>

    <tr>
      <!-- Dirección -->
      <td class="width-70 pb-x5">
        <div class="nombre_div_class">
          <span class="bold"> O/COMPRA: </span>
          <span class=""> {{ $venta["VtaPedi"] }} </span>
        </div>
      </td>
      <!-- /RUC -->

      <!-- Forma de Pago -->
      <td class="width-30 pb-x5">
        <div class="nombre_div_class">
          <span class="bold">Vendedor: </span>
          <span class="{{ $right_text_class }}"> {{ $venta2->vendedor->vennomb }}
          </span>
        </div>
      </td>
      <!-- Forma de Pago -->
    </tr>


    <tr>
      <!-- Dirección -->
      <td class="width-70 pb-x5">
        <div class="nombre_div_class">
          <span class="bold"> Guias: </span>
          <span class=""> {{ implode(",",$guias)  }} </span>
        </div>
      </td>
      <!-- /RUC -->

      <!-- Forma de Pago -->
      <td class="width-30 pb-x5">
        <div class="nombre_div_class">
          <span class="bold">Responsable:</span>
          <span class="{{ $right_text_class }}"> {{ $venta["User_Crea"] }}
          </span>
        </div>
      </td>
      <!-- Forma de Pago -->
    </tr>

  </table>

</div>