jQuery(document).ready(function(){
    jQuery('a[rel="tooltip"]').tooltip({placement: 'right'});
});

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