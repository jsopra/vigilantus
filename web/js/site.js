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

function populaSelectJSON(elemento, url) {

    jQuery.getJSON(url, function(data) {

        elemento.html('');

        var qtde = 0;

        jQuery.each(data, function(i, value) {
            elemento.append($('<option>').text(value).attr('value', i));
            qtde++;
        });      
        
        if(qtde > 1)
            elemento.prepend($('<option>').text('Selecione...').attr('value', ''));
    });
}