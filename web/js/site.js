$(document).ready(function(){
	$('a[rel="tooltip"]').tooltip({placement: 'right'});

	function createAutoClosingAlert(selector, delay) {
		var alert = $(selector).alert();
		window.setTimeout(function() { alert.alert('close') }, delay);
	}

	if ($('.alert-success').length) {
		createAutoClosingAlert(".alert-success", 2000);
	}
})