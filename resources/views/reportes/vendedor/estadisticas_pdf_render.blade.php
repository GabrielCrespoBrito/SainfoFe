<!DOCTYPE html>
<html>
<head>
	<title>Titulo</title>
	<style type="text/css">
	
	.table_items {
		/*font-family: Open Sans;*/
		border-collapse: collapse;
		text-align: right
		border-bottom: 1px solid #000;
	}

	.table_items thead td {
		font-weight: bold;
		background-color: #ccc;
		color: black;
		border-bottom: 1px solid #000;
		border-top: 1px solid #000;
	}

	.table_items tfoot td {
		font-weight: bold;
		border-bottom: 1px solid #000;
		border-top: 1px solid #000;
	}

	.table_items tfoot td.border {
		border-bottom: 1px solid #000;
	}

	.title {
		text-align: center;
		font-weight: bold;
	}

	.wrapper {
		text-align: center;
	}

	</style>
</head>
<body>
	<div class="wrapper"> {!! $html !!} </div>
</body>
</html>

