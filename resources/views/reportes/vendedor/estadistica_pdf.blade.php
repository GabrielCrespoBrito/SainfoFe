<!DOCTYPE html>
<html>
<head>
	<title>Titulo</title>
	<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  	$(document).ready(function(){      
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart(){

        let colors = [
          'rgb(60,141,188)',
          'rgb(68 ,212 ,138)',
          'rgb(185 ,239 ,63)',
          'rgb(163 ,123 ,8)',
          'rgb(243 ,85 ,7)',
          'rgb(94 ,48 ,25)',
          'rgb(116 ,4 ,4)',
          'rgb(255 ,123 ,123)',
          'rgb(210 ,94 ,210)',
          'rgb(147 ,145 ,145)'
        ];

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', '');
        data.addColumn({ type: 'string', role: 'style' });
        // 
        data.addRows( {{ count($items) }} );

        @foreach($items as $item)
          data.setValue( {{ $loop->index }}, 0, "{{ $item['info']['nombre'] }}" );
          data.setValue( {{ $loop->index }}, 1, {{ $item['total']['importe'] }}  );
          data.setValue( {{ $loop->index }}, 2, colors[ {{ $loop->index }} ]);
        @endforeach

        var chart_area = document.getElementById('chart_div');
        var chart = new google.visualization.ColumnChart(chart_area);
  
        google.visualization.events.addListener(chart,'ready', function(){
        	chart_area.innerHTML = 
        	'<img src="'+ chart.getImageURI() +
        	'" class="img-responsive">';
        });

        chart.draw(data, {
            width: 750, 
            height: 400, 
            title: 'Sumatoria Importe/Vendedor',
            legend: 'none',
        });

	      let form = $("#form_grafica");
	      let html = $("#chart_html").html();
	      form.find("[name=hidden_html]").val(html);
	      form.submit();
      }         
  	})
  </script>	
</head>
<body>

	<div id="chart_html">

  {{-- table informacion header --}}
    <div style="border-bottom: 1px solid #ccc; padding-bottom: 20px ">
    	<table width="100%" class="table_items">
    		<tbody>
	    		<tr>
	    			<td style="text-align:center" colspan="2"> <h3><strong> {{ $info['reporte_nombre'] }} </strong> </h3></td>
	    		</tr>

	    		<tr>
	    			<td> <strong> {{ $info['empresa_nombre'] }} </strong> </td>
	    			<td> <strong> {{ $info['empresa_ruc'] }} </strong> </td>
	    		</tr>
	    		<tr>
	    			<td> FECHA DESDE: <strong> {{ $info['fecha_desde'] }} </strong> </td>
	    			<td> FECHA HASTA: <strong> {{ $info['fecha_hasta'] }} </strong> </td>
	    		</tr>          
    		</tbody>
    	</table>
    </div>
    {{-- table informacion header --}}

    <div id="chart_div"></div>

    <div class="table">    	
    	<table width="100%" class="table_items">
    		<thead>
	    		<tr>
	    			<td style=" font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" > CODIGO </td>
	    			<td style=" font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;"> VENDEDOR </td>
	    			<td style="text-align:right;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;"> TOTAL SIN IGV </td>
	    		</tr>
    		</thead>

    		@foreach( $items  as $item )
    		<tr>
    			<td style="border-top:1px dashed #ccc;border-bottom:1px dashed #ccc;">
            {{ $item['info']['id'] }}
          </td>
    			<td  style="border-top:1px dashed #ccc;border-bottom:1px dashed #ccc;">
            {{ $item['info']['nombre_complete'] }}
          </td>

    			<td  style="font-weight:bold; text-align:right; border-top:1px dashed #ccc;border-bottom:1px dashed #ccc;">
            {{ $item['total']['importe'] }}
          </td>

    		</tr>
    		@endforeach
    		<tfoot>
    			<tr>
    				<td></td>
    				<td class="border"> TOTAL GENERAL:</td>
    				<td class="border" style="font-weight:bold;text-align: right"> {{ $total['importe'] }}</td>
    			</tr>
    		</tfoot>
    	</table>
    </div>
	</div>

<form 
  id="form_grafica"
  method="post"
  style="display: none"
  action="{{ route('reportes.vendedor_estadistica.report_render') }}">
	@csrf
	<input type="hidden" name="hidden_html">
</form>

</body>
</html>

