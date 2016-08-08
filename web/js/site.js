$(document).ready(function(){
	$('a[rel="tooltip"]').tooltip({placement: 'right'});

	function createAutoClosingAlert(selector, delay) {
		var alert = $(selector).alert();
		window.setTimeout(function() { alert.alert('close') }, delay);
	}

	if ($('.alert-success').length) {
		createAutoClosingAlert(".alert-success", 2000);
	}

    $('select[name="user_municipio"]').change(function(){
       window.location.href =  $(this).val();
    });

    if (!Modernizr.inputtypes.date) {
        $('body').on('focusin', 'input[type="date"]', function(e) {

            $(this).datepicker().on("changeDate", function (ev) {
                $(this).datepicker("hide");
            });
        });
    }

		$(".balance-text").balanceText();
});

function fillModalElement(html)
{
    $('#modal-window .modal-body').html(html);
    $('#modal-window-title').html($('#modal-window .modal-body h1').html());
    $('#modal-window .modal-footer').html('');
    $('#modal-window .modal-footer').append($('#modal-window .form-actions'));
    $('#modal-window .modal-body h1').remove();
    $('#modal-window [data-role=cancel]').attr('data-dismiss', 'modal');
    $('#modal-window [data-role=cancel]').attr('href', 'javascript:void(0)');
}

function createModalElement()
{
    if ($('#modal-window').length == 0) {

        $('body').append('\
            <div class="modal fade" id="modal-window" tabindex="-1" role="dialog" aria-labelledby="modal-window-title" aria-hidden="true">\
              <div class="modal-dialog">\
                <div class="modal-content">\
                  <div class="modal-header">\
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                    <h4 class="modal-title" id="modal-window-title">Carregando...</h4>\
                  </div>\
                  <div class="modal-body">\
                    &nbsp;\
                  </div>\
                  <div class="modal-footer">\
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->\
                    &nbsp;\
                  </div>\
                </div>\
              </div>\
            </div>\
        ');
    } else {
        $('#modal-window .modal-body').html('&nbsp;');
        $('#modal-window-title').html('Carregando...');
    }
}

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
