@yield('footer_before')

</div> 
{{-- end body --}}
{{-- @include("partials.modal_update_tc", ['updatedNeeded' => session()->has('save_tipocambio') ]) --}}

@include("partials.modal_data")
@include("partials.modal_elegir_empresa" )

<!-- jQuery 3 -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('js/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<!-- Toasts -->
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset(mix('js/mix/sainfo.js')) }}"></script>
<script src="{{ asset('js/app.js') }}"></script>


<script type="text/javascript">
	window.url_datatable_es = "{{ asset('plugins/datatable/es_lang.json') }}";
</script>

@yield('js')
@stack('js')

<script type="text/javascript">
$(function(){
	@if( session()->has('notificacion') )
	  $.toast({
	    heading   : "{{ session()->get('titulo')  }}" ,
	    text      :  "{!! session()->get('mensaje') !!}",
			position  : 'top-center',
			// hideAfter: false,

	    showHideTransition : '{{ session('N_showHideTransition') }}', 
	    hideAfter : '{{ session('N_hideAfter') }}',
	    icon      : "{{ session()->get('tipo') }}",
	  });
	@endif
		// TC
		$modalTC = $("#modalTC");
		if( $modalTC.find("#formTC").data("info") == 1 ) {
			$modalTC.modal();
		}

	// tree
  $('.sidebar-menu').tree();
});

</script>
</body>
</html>