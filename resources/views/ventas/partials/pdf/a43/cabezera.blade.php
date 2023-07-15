<table class="t_cabezera" style="table-layout: fixed;" width="100%">
  <tr>

    <!-- data 1 (logo,nombre de empresa)-->
    <td class="data_1" style="width: 30% !important">
      <table width="100%">
        <tr>
          <td class="data_1_2" width="100%" style="border-radius: 20px; overflow:hidden;">
            <p class="empresa_nombre">
              <img style="
              display : block;
              height: auto;
              width: auto;" src="data:image/png;base64,{{ $logoDocumento }}">
            </p>
          </td>
        </tr>
      </table>
    </td>


    <!-- data 3 (logo,nombre de empresa)-->
    <td class="data_1" style="width: 40% !important">
      <table width="100%">        <tr>
          <td class="info_empresa">
            <p class="direccion">{!! $direccion !!}</p>
            <p class="telefono">Tlf: <strong> {{ $telefonos }} </strong> </p>
            <p class="email"> {{ $cliente_correo }}: <strong> {{ $correo }} </strong> </p>
          </td>
        </tr>
      </table>
    </td>


    <!-- data 2 (ruc, numero factura)-->
    <td class="data_2" style="width: 30% !important;">
      {{-- <div class="border" style="position:absolute; top:0;"> --}}
      <div class="border" style="position:relative;top:0">
        <p class="empresa_ruc"> RUC: <span class="ruc"> {{ $empresa['EmpLin1'] }} </span> </p>
        <p class="factura_titulo"> {{ $nombre_documento }} </p>
        <p class="factura_numero"> N° <span class="factura_serie"> {{ $documento_id }} </span> </p>
      </div>
      
      @isset($logoSubtitulo)
      <div class="" style="">
        <p class="empresa_nombre">
          <img style="
              margin-top: 5px;
              display : block;
              height: auto;
              width: auto;" src="data:image/png;base64,{{ $logoSubtitulo }}">
        </p>
      </div>
      @endisset

    </td>
  </tr>
</table>
