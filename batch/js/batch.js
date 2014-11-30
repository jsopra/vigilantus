var columnLabels;

var carregarHint = function(element)
{
    element = jQuery(element);

    var attribute = element.val();
    var hint = element.parentsUntil('tr').parent().find('[role=hint]');

    if (undefined != columnHints[attribute]) {
        hint.html('<small></small>');
        hint.find('small').text(columnHints[attribute]);
    } else {
        hint.html('&nbsp;');
    }
};
var desativarPosicoesEscolhidas = function() {

    var positionsDefined = {};

    for (attribute in columnLabels) {
        positionsDefined[attribute] = false;
    }

    jQuery('#posicoes-colunas select[role=position] option').prop('disabled', false);
    jQuery('#posicoes-colunas select[role=position]').each(function(index, element){

        var attribute = jQuery(element).val();

        if (undefined != positionsDefined[attribute]) {
            positionsDefined[attribute] = true;
        }
    });

    for (attribute in positionsDefined) {

        // Se já foi setado, desativa em todos os outros combos
        if (positionsDefined[attribute]) {

            var optionSelecionada = jQuery('#posicoes-colunas select[role=position] option[value=' + attribute + ']:selected');
            var todosOsOptions = jQuery('#posicoes-colunas select[role=position] option[value=' + attribute + ']');

            // Desativa todos os <option>
            todosOsOptions.prop('disabled', true);

            // Reativa o <option> selecionado
            optionSelecionada.prop('disabled', false);
        }
    }
};

jQuery(document).ready(function(){
    jQuery('#posicoes-colunas select[role=position]').each(function(index, element){
        element = jQuery(element);
        element.change(function(){
            desativarPosicoesEscolhidas();
            carregarHint(element);
        });
    });

    desativarPosicoesEscolhidas();
    jQuery('#posicoes-colunas select[role=position]').each(function(index, element){
        carregarHint(element);
    });

    $('#limpar-todos').click(function(){
        jQuery('#posicoes-colunas select[role=position]').each(function(index, element){
            jQuery(element).val('');
            jQuery(element).change();
        });
    });
    $('#preencher-automaticamente').click(function(){
        jQuery('#posicoes-colunas select[role=position]').each(function(index, element){
            element = jQuery(element);
            var option = element.find('option').not(':disabled').not('[value=""]');
            element.val(option.val());
            element.change();
        });
    });
});