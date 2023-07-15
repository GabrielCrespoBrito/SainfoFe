<!DOCTYPE html>
<html>
<head>
	<style type="text/css">

	@page {
	margin: 0cm 0cm;
	}

	/**
	* Define the real margins of the content of your PDF
	* Here you will fix the margins of the header and footer
	* Of your background image.
	**/
	body {
	margin-top:    4cm;
	margin-bottom: 2cm;
	margin-left:   1cm;
	margin-right:  1cm;
	}

	/** 
	* Define the width, height, margins and position of the watermark.
	**/
	#watermark_header {
	background: transparent url('images/cabecera.PNG') no-repeat 30% left;
	position: fixed;
	bottom:   0px;
	left:     0px;
	/** The width and height may change 
	according to the dimensions of your letterhead
	**/
	width:    21.8cm;
	height:   28cm;
	/** Your watermark should be behind every content**/
	z-index:  -1000;
	}


	#watermark_footer {
	background: transparent url('images/pie.PNG')	no-repeat 30% left;
	position: fixed;
	top:   88%;
	left:     0px;
	width:    21.8cm;
	height:   28cm;
	/** Your watermark should be behind every content**/
	z-index:  -1000;
	}


/*	.contenido {
		border-top: 1px solid #999;
		padding-bottom: 10px;
	}*/

	</style>
	<title> {{ $title }} </title>
</head>

<body>
	<div id="watermark_header"></div>	
	<div class="contenido">
	{!! $contenido !!}
	</div>
	<div id="watermark_footer"></div>		
</body>

</html>

