!function ($) {

	$(function(){
		$('a[href="#"][data-dismiss!="alert"]').click(function(){
			return false
		})
		$('[data-loading-text]').click(function(){
			$(this).button('loading')
		})
		$('[rel="tooltip"]').tooltip();
	})

}(window.jQuery)