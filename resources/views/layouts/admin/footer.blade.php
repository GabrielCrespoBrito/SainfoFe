@yield('footer_before')

</div> 

{{-- end body --}}
{{-- @include("partials.modal_update_tc", ['updatedNeeded' => session()->has('save_tipocambio') ]) --}}
{{-- end body --}}

@include("partials.modal_data")

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
<script src="{{ asset(mix('js/mix/admin.js')) }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
	window.url_datatable_es = "{{ asset('plugins/datatable/es_lang.json') }}";
</script>

@yield('js')
@stack('js')

<script type="text/javascript">

$(function(){
	@if( session()->has('notificacion') )


    let optionsToast = {
      text: "{{ session('mensaje', '') }}",
      heading: "{{ session('titulo', '') }}",
      showHideTransition: "{{ session('N_showHideTransition', 'fase') }}",
      allowToastClose: {{ session('N_allowToastClose', 'true' ) }},
      hideAfter: "{{ session('N_hideAfter', '3000') }}",
      loader: {{ session('N_loader', 'true') }},
      loaderBg: "{{ session('N_loaderBg', '#9EC600') }}",
      stack: {{ session('N_stack', '5') }},
      position: "{{ session('N_position', 'top-center') }}",
      bgColor: {{ session('N_bgColor', 'false') }},
      textColor: {{ session('N_textColor', 'false') }},
      textAlign: "{{ session('N_textAlign', 'left') }}",
      icon: "{{ session('tipo', 'success') }}"
      //beforeShow: function () {},
      //afterShown: function () {},
      //beforeHide: function () {},
      // afterHidden: function () {}
    }

  console.log(optionsToast);

	$.toast(optionsToast);
  
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