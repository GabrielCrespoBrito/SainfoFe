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
      function drawChart() {

        let rows = [['','', {'role' : 'annotation' }]];
				// data.addColumn('string', 'Mes');


        @foreach( $data['mensuales'] as $mensual )
        	rows.push( [ "{{ $mensual['codigo'] }}" , {{ $mensual['importe'] }} , {{ $mensual['importe'] }}])
        @endforeach

        var data = new google.visualization.arrayToDataTable(rows);

        var options = {
        	'title':'Importe mensuales',
          'width': 700,
          'height':400
        };

        // Instantiate and draw our chart, passing in some options.
        var chart_area = document.getElementById("chart_div");
        var chart = new google.visualization.ColumnChart(chart_area);

        google.visualization.events.addListener(chart,'ready', function(){
        	chart_area.innerHTML = 
        	'<img src="'+ chart.getImageURI() +
        	'" class="img-responsive">';
        });

        chart.draw(data, options);

	      let form = $("#form_grafica");
	      let html = $("#chart_html").html();
	      form.find("[name=hidden_html]").val(html);
	      form.submit();
      }

      // Enviar la información
  	})
  </script>	
</head>
<body>

	<div id="chart_html">		
    <div id="chart_div"></div>
    <div class="table">    	
    
    	<table width="100%" class="table_items">
    		<thead>
	    		<tr>
	    			<td> Codigo </td>
	    			<td> Descripcion </td>
	    			<td style="text-align: right"> Importe </td>
	    		</tr>
    		</thead>
    		@foreach( $data['mensuales'] as $mensual )
    		<tr>
    			<td>{{ $mensual['codigo'] }}</td>
    			<td>{{ $mensual['descripcion'] }}</td>
    			<td style="text-align: right">{{ $mensual['importe'] }}</td>    			
    		</tr>
    		@endforeach
    		<tfoot>
    			<tr>
    				<td></td>
    				<td class="border"> Total general:</td>
    				<td class="border" style="text-align: right"> {{ $data['total_importe'] }}</td>
    			</tr>
    		</tfoot>
    	</table>
    </div>
	</div>

<form id="form_grafica" method="post" style="display: none" action="{{ route('reportes.ventas_anual_pdf_create', $data['year']  ) }}">
	@csrf
	<input type="hidden" name="hidden_html">
</form>

</body>
</html>

