<table class="t_cabezera" style="table-layout: fixed;" width="100%">
  <tr>
    <!-- data 1 (logo,nombre de empresa)-->
    <td class="data_1" style="width: 70% !important">
      <table width="100%">
        <tr>
          @php
          // ---------------------
          $enterprise = get_empresa();
          $logo_principal = $enterprise->logoEncode();
          $logo_secundario = $enterprise->logoEncode(2);
          $show_principal = true;
          $show_secundario = false;
          $format_small = true;
          $show_text = false;
          if( $enterprise->isA41() ){
          $show_secundario = true;
          $show_principal = false;
          }
          elseif( $enterprise->isA42() ){
          $show_secundario = true;
          }
          @endphp

          {{-- Logo principal --}}
          @if( $show_principal )
          <td class="data_1_1 " width="25%" style="text-align: right">
            <img style="
						  min-width: 100; 
						  height: 100px;" src="data:image/png;base64,{{ $logo }}">
          </td>
          @endif


          {{-- Logo secundario --}}

          @php
          if( $format_small ){
          $height = '100px';
          $width = '100%';
          $width_img = '300px';
          }
          else {
          $height = '120px';
          $width = $show_principal ? '75%' : '100%';
          $width_img = $show_principal ? '320px' : '450px';
          }
          @endphp

          <td class="data_1_2" width="{{ $width }}" style="border-radius: 20px">
            <p class="empresa_nombre">
              <img style="
				    	min-width: {{ $width_img }};
				    	display: block;
				    	height: {{ $height }};" src="data:image/png;base64,{{ $logo_secundario }}">
            </p>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="info_empresa">
            {{-- @dd() --}}
            <p class="direccion">{!! $enterprise->direccionFormato() !!}</p>
            <p class="telefono">Tlf: <strong> {{ $enterprise->EmpLin4 }} </strong> </p>
            <p class="email"> {{ getNombreCorreo($enterprise->EmpLin3) }}: <strong> {{ $enterprise->EmpLin3 }} </strong> </p>
          </td>
        </tr>
      </table>
    </td>

    <!-- data 2 (ruc, numero factura)-->
    <td class="data_2" style="width: 30% !important">
      <div class="border">
        <p class="empresa_ruc"> RUC: <span class="ruc"> {{ $empresa['EmpLin1'] }} </span> </p>
        <p class="factura_titulo"> {{ $nombre_documento }} </p>
        <p class="factura_numero"> N° <span class="factura_serie"> {{ $documento_id }} </span> </p>
      </div>
    </td>

  </tr>
</table>