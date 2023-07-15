<p> Estimado <strong>{{ $nombre_remitente }}</strong> </p>


<div class="message"> {{ $mensaje }} </div>

<div class="table">

	@if(strlen( $mensaje ))

		<table style="margin-top:20px" style="color: #999999; background-color: #fafafa; border-left: 2px solid gray">

			<tr>
				<td> {{ $mensaje }}</td>
			</tr>
		</table>
	@endif

</div>

@include('mails.partials.pie')

