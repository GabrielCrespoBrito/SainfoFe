<div class="cabezera">
  @if($logoDocumento)
  <p class="empresa_nombre" style="text-align:center">
    <img style="
      text-align: center;
      display: block;
      margin-bottom:10px" 
      src="data:image/png;base64,{{ $logoDocumento }}"/>
  </p>
  @endif

  <p class="center titulo"> {{ $empresa['EmpNomb'] }} </p>
  <p class="center size-regular"> {{ $direccion }} </p>
  <div class="ruc center size-regular">R.U.C.: {{ $empresa['EmpLin1'] }} </div> 
  <div class="tlf center size-regular">{{ $telefonos_local ?? $telefonos }} </div> 
  <div class="email center size-regular">{{ $empresa['EmpLin3'] }} </div> 
  
  <p class="border-separador"></p>

  <div class="data_salida">

    <div style="margin-bottom: 5pt">
      <div class="center facturacion">{{$venta['nombre_documento']}}</div>
      <div class="center facturacion">{{$venta['VtaNume']}}</div>
    </div>

    <table class="center" style="width: 100%">
      <tr class="center">
        <td class="left" style="width:33%;">Fecha Emisión:</td>
        <td class="left" style="width:33%;  ">{{$venta['VtaFvta']}}</td>
        <td class="left" style="width:33%;">{{$venta['VtaHora']}}</td>				
      </tr>
      <tr class="center">
        <td class="left" style="width:33%;">Vendedor:</td>
        <td class="left" style="width:33%; ">{{$venta['Vencodi']}}</td>
        <td class="left" style="width:33%;"></td>				
      </tr>				
    </table>

    </div>	

  </div>