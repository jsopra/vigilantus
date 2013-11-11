;

(function($){
    $.paramLatin = function(a)
    {
        var s = []; 
 
        // If an array was passed in, assume that it is an array 
        // of form elements 
        if ( a.constructor == Array || a.jquery ){
            // Serialize the form elements 
            jQuery.each( a, function(){ 
                s.push(unescape(encodeURIComponent(escape(this.name))).replace(/\+/g, "%2B") + "=" + unescape(encodeURIComponent(escape(this.value))).replace(/\+/g, "%2B")); 
            }); 
        } 
        // Otherwise, assume that it's an object of key/value pairs 
        else{ 
            // Serialize the key/values 
            for ( var j in a ) 
                // If the value is an array then the key names need to be repeated 
                if ( a[j] && a[j].constructor == Array ) 
                    jQuery.each( a[j], function(){ 
                        s.push(unescape(encodeURIComponent(escape(j)).replace(/\+/g, "%2B") + "=" + encodeURIComponent(escape(this))).replace(/\+/g, "%2B")); 
                    }); 
                else 
                    s.push(unescape(encodeURIComponent(escape(j)).replace(/\+/g, "%2B") + "=" + encodeURIComponent(escape(a[j]))).replace(/\+/g, "%2B")); 
        } 
        // Return the resulting serialization 
        return s.join("&").replace(/ /g, "+"); 
    }
})(jQuery);


jQuery.fn.extend({
	serializeLatin: function() {
		return jQuery.paramLatin( this.serializeArray() );
	}
});

(function ( $, window, undefined ) {

	var document = window.document,
	defaults = {
		charset: 'UTF-8',
		debug: false,
		loadedInlineEditionCssClass: "edition-loaded",
		isExportable: true,
		isEditable: true,
		hasClearFilterButton: false,
		exportFlagName: 'export',
		modelName: null,
		modelAttributesMapping: {},
		modelFormFieldsMapping: {},
		blankDisplay: null
	};

	window.PerspectivaGrid = function( element, options ) {
		
		this.element = jQuery(element);

		this.options = $.extend( {}, defaults, options) ;

		this._defaults = defaults;
		this._name = 'PerspectivaGrid';

		// Registra este grid numa variável global
		if (window.loadedPGridView == undefined) window.loadedPGridView = {};
		window.loadedPGridView[this.element.attr('id')] = this;

		this.init();
	};

	/**
	 * Método de classe que lança as mensagens pro usuário
	 */
	PerspectivaGrid.message = function(text, type, sticky) {

		if (type == undefined) {
			type = 'notice';
		}

		if (sticky == undefined) {
			sticky = false;
		}

		// Toast message
		jQuery().toastmessage('showToast', {
			text: text,
			type: type,
			sticky: sticky
		});
	};

	/**
	 * Inicializa o grid
	 */
	PerspectivaGrid.prototype.init = function () {
			
		var grid = this;
		
		// Atributos internos
		this._gridId = this.element.attr('id');
		this._modelName = this.element.attr('data-model-name');

		this.log({'Inicializando grid com ID': this.element.attr('id')});
		
		// Ações dos botões
		if (this.options.isExportable) {
			
			jQuery('#' + this._gridId + '-export-btn').click(function(){
				grid.exportData();
			});
		}
		
		if (this.options.isEditable) {
			
			// Instancia a ação de editar inline nos botões de editar
			jQuery('#' + this._gridId + ' table .button-column .update').each(function(){
				
				jQuery(this).click(function(event){
					
					try {
						// Carrega o form de edição inline
						grid.loadInlineEdition(this);
					}
					catch (exception) {
						throw exception;
					}
					finally {
						
						// Previne que o clique redirecione
						event.preventDefault();
					}
				});
			});
			
			// Instancia a ação de adicionar um novo
			jQuery('#' + this._gridId + ' table .add').each(function(){
				
				if (jQuery(this).attr('data-click-event') != undefined) {
					return;
				}
				
				jQuery(this).attr('data-click-event', 'true');
				
				jQuery(this).click(function(event){
					
					try {
						// Carrega o form de edição inline
						grid.loadInlineCreate(this);
					}
					catch (exception) {
						throw exception;
					}
					finally {
						
						// Previne que o clique redirecione
						event.preventDefault();
					}
				});
			});
		}
		
		// Botão de excluir vários
		var botaoDeleteMany = jQuery('#' + grid._gridId + ' .delete-many');

		if (botaoDeleteMany.attr('data-click-event') == undefined) {

			botaoDeleteMany.attr('data-click-event', 'true');

			botaoDeleteMany.click(function(){
				
				var idsList = [];
				
				jQuery('#' + grid._gridId + ' .checkbox-column input[name="selectedItems[]"]:checked').not(':disabled').each(function(index, element){
					idsList.push(jQuery(element).val());
				});
				
				if (idsList.length == 0) {
					return;
				}
				else if (!confirm('Tem certeza de que deseja remover os registros selecionados?')) {
					return;
				}
				
				$.ajax({
					url: jQuery(this).attr('data-url'),
					type: 'POST',
					data: {ids: idsList},
					success: function(data) {
						$.fn.yiiGridView.update(grid._gridId, {
							
							complete: function(jqXHR, status) {
								
								if (status=='success') {
									
									// Toast message
									PerspectivaGrid.message('Os registros foram removidos com sucesso');
									
									//atualiza os selects, caso necessite
									grid.updateAllSelectOption();
									
									// Reinstancia o grid depois do reload do Yii
									grid.init();
								}
							}
						});
					},
					error: function() {
						PerspectivaGrid.message('Ocorreu um erro ao processar a requisição', 'error');
					}
				});
				
			});
		}
	};
	
	/**
	 * Cria e retorna o objeto jQuery de um formulário temporário
	 * para que se possa chamar o método serialize()
	 */
	PerspectivaGrid.prototype.getTempForm = function() {
		
		var tempFormId = 'PerspectivaGridTempForm';
		
		// Se não existe, cria
		if (jQuery('#' + tempFormId).length == 0) {
			
			var form = jQuery('<form />');
			form.attr('id', tempFormId);
			form.attr('accept-charset', this.options.charset);
			form.hide();
			jQuery('#' + this._gridId).append(form);
		}
		// Se existe, limpa
		else {
			jQuery('#' + tempFormId).html('');
		}
		
		return jQuery('#' + tempFormId);
	};
	
	/**
	 * Duplica a linha que será editada, adicionando os campos de formulário e ocultando a linha original
	 */
	PerspectivaGrid.prototype.loadInlineCreate = function(button) {
		
		var grid = this;
		
		// TODO TRATAR LINHAS ZEBRADAS
		
		// Pega as linhas
		var originalRow = jQuery(button).parentsUntil('tr').parent();
		var editableRow = originalRow.clone();
		
		// Adiciona a linha fantasma
		editableRow.attr('data-ajax-update-url', jQuery(button).attr('href'));
		originalRow.before(editableRow);
		
		// Carrega a edição
		grid.loadInlineEdition(editableRow.find('.add'), true);
	};
	
	/**
	 * Duplica a linha que será editada, adicionando os campos de formulário e ocultando a linha original
	 */
	PerspectivaGrid.prototype.loadInlineEdition = function(button, isNew) {
		
		var grid = this;

		// Tem alguma linha com edição carregada? Desativa
		var cancelButton = jQuery('#' + grid._gridId + ' .button-column .cancel'); 

		if (cancelButton.length > 0) {
			grid.cancelInlineEdition(cancelButton);
		}
		
		if (isNew == undefined) isNew = false;
		
		// Pega as linhas
		var originalRow = jQuery(button).parentsUntil('tr').parent();
		var editableRow = originalRow.clone();

		this.log({'Editando linha': originalRow.attr('data-ajax-update-url')});
		
		// Prepara as celulas
		editableRow.find('td').each(function(index, element){
			
			element = jQuery(element);
			
			// É um campo editável?
			if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
				
				var attributeName = grid.options.modelAttributesMapping[index];
				
				// Monta a tag do campo
				var fieldType          = grid.options.modelFormFieldsMapping[index]['type'];
				var fieldData          = grid.options.modelFormFieldsMapping[index]['data'];
				var fieldHtmlOptions   = grid.options.modelFormFieldsMapping[index]['htmlOptions'];
				var fieldPluginOptions = grid.options.modelFormFieldsMapping[index]['pluginOptions'];

				var formField = null;
				
				// Combo
				if (fieldType == 'select') {
					
					formField = $('<select />');
					
					for (optionIndex in fieldData) {

						var optionLabel = fieldData[optionIndex]['label'];
						var optionValue = fieldData[optionIndex]['value'];

						formField.append('<option value="' + optionValue + '">' + optionLabel + '</option>');
					}
					
					// Corrige bug de JS onde não selecionava a opção
					formField.find('option').click(function() {
						jQuery(this).parent().find('option').removeAttr('selected');
						jQuery(this).attr('selected', 'selected');
					});
					
					if (!isNew && element.attr('data-current-value') != undefined) formField.val(element.attr('data-current-value'));
				}
				else if (fieldType == 'radio') {

					element.html('');
					
					for (optionIndex in fieldData) {

						var optionLabel = fieldData[optionIndex]['label'];
						var optionValue = fieldData[optionIndex]['value'];

						element.append('<label class="radio-option"><input type="radio" value="' + optionValue + '" /> ' + optionLabel + '</label>');
					}

					formField = element.find('input');

				}
				else if (fieldType == 'checkbox') {

					formField = $('<input />');
					formField.attr('type', 'checkbox');
					formField.attr('value', '1');
				
					if (!isNew) {

						if (element.attr('data-current-value')) {
							formField.attr('checked', 'checked');
						}
						else {
							formField.removeAttr('checked');
						}
					}
				}
				// Data: como não usamos HTML5, será um <input type=text> com DatePicker
				else if (fieldType == 'date') {

					var uiLanguage = fieldPluginOptions['uiLanguage'];

					formField = $('<input />');
					formField.attr('type', 'text');
					
					// Chama o datepicker só depois de adicioná-lo na árvore DOM

					if (!isNew && element.html() != grid.options.blankDisplay) formField.val(element.html());
				}
				// Padrão é texto
				else {
					
					formField = $('<input />');
					formField.attr('type', 'text');
				
					if (!isNew && element.html() != grid.options.blankDisplay) formField.val(element.html());
				}

				// Opções personalizadas de HTML
				for (attr in fieldHtmlOptions) {
					grid.log({'Setando configuração personalizada do campo:': {attr: fieldHtmlOptions[attr]} } );
					formField.attr(attr, fieldHtmlOptions[attr]);
				}
				
				// Atributos adicionais e adiciona à célula
				formField.addClass('fidelize-grid-field');
				formField.attr('name', grid.options.modelName + '[' + attributeName + ']');

				if (fieldType == 'radio') {

					var currentValue = (element.attr('data-current-value') != undefined && element.attr('data-current-value') != '') ? element.attr('data-current-value') : '0';

					formField.each(function(index, field){
						field = jQuery(field);
						field.attr('id', grid.options.modelName + '_' + attributeName + '_' + field.val());

						if (!isNew && currentValue == field.val()) {
							field.attr('checked', 'checked');
							field.click();
						}
						else {
							field.removeAttr('checked');
						}
					}); 
				}
				else {
					formField.attr('id', grid.options.modelName + '_' + attributeName);
					element.html(formField);

					// Se for uma data, agora pode chamar o datepicker
					if (fieldType == 'date') {
						formField.datepicker(jQuery.extend(jQuery.datepicker.regional[uiLanguage], fieldPluginOptions));
					}
				}
			}
			// É a coluna dos botões?
			else if (element.hasClass('button-column')) {
				
				element.html('<a href="javascript:void(0)" data-title="Atualizar" class="confirm icon-ok-sign tr_confirm">Confirmar</a> <a href="javascript:void(0)" class="cancel icon-remove-sign tr_cancel">Cancelar</a>');
				
				element.find('.confirm').click(function(){
					grid.confirmInlineEdition(this);
				});
				
				if (isNew) {
					element.find('.cancel').click(function(){
						grid.cancelInlineCreate(this);
					});
				}
				else {
					element.find('.cancel').click(function(){
						grid.cancelInlineEdition(this);
					});
				}
			}
			// Qualquer outra coluna é limpa
			else {
				//element.html('');
			}
		});
		
		// Oculta a linha original e carrega a editável
		originalRow.hide();
		originalRow.after(editableRow);
	};
	
	/**
	 * Cancela a edição inline removendo a <tr/> nova e exibindo a original
	 */
	PerspectivaGrid.prototype.cancelInlineEdition = function(button) {
		
		// Pega as linhas
		var editableRow = jQuery(button).parentsUntil('tr').parent();
		var originalRow = editableRow.prev();
		
		// Remove a linha editável e carrega a original
		originalRow.show();
		editableRow.remove();
	};
	
	/**
	 * Cancela a criação inline removendo os <tr/> novos
	 */
	PerspectivaGrid.prototype.cancelInlineCreate = function(button) {
		
		var editableRow = jQuery(button).parentsUntil('tr').parent();
		var originalRow = editableRow.prev();
		
		originalRow.remove();
		editableRow.remove();
	};
	
	/**
	 * Posta a edição inline e testa a validação
	 */
	PerspectivaGrid.prototype.confirmInlineEdition = function(button) {
		
		var grid = this;
		
		// Pega as linhas
		var editableRow = jQuery(button).parentsUntil('tr').parent();
		var originalRow = editableRow.prev();
		
		// Coleta os dados
		var tempForm = grid.getTempForm();
		
		editableRow.find('td').each(function(index, element){
			
			// Adiciona somente colunas editáveis (e não campos de check para exclusão, etc.)
			if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
				
				// Pega o campo do form
				var campo = jQuery(element).children();
				var novoCampo = null;

				// É radio ou checkbox?
				var isCheckbox = false;
				var isRadio = false;

				if (grid.options.modelFormFieldsMapping != undefined && grid.options.modelFormFieldsMapping[index] != undefined) {

					isCheckbox = (grid.options.modelFormFieldsMapping[index]['type'] == 'checkbox');
					isRadio = (grid.options.modelFormFieldsMapping[index]['type'] == 'radio');
				}

				// Adiciona o campo temporario
				if (isCheckbox || isRadio) {

					if (isRadio) {
						campo = jQuery(element).find('input:checked');
					}

					novoCampo = jQuery('<input/>');
					novoCampo.attr('type', 'hidden');
					novoCampo.attr('name', campo.attr('name'));
				}
				else {
					novoCampo = campo.clone();
				}

				tempForm.append(novoCampo);

				// Garante que o valor continua idêntico
				if (isCheckbox) {

					if (campo.is(':checked')) {
						novoCampo.val('1');
					}
					else {
						novoCampo.val('0');
					}
				}
				else {
					novoCampo.val(campo.val());
				}
			}
		});
		
		var dados = '';

		this.log({'Salvando objeto': tempForm.serializeArray()});
		
		if (grid.options.charset != 'UTF-8') {
			dados = tempForm.serializeLatin();
		}
		else {
			dados = tempForm.serialize();
		}
		
		// Posta os dados
		jQuery.ajax({
			url: editableRow.attr('data-ajax-update-url'),
			type: 'POST',
			data: dados,
			dataType: 'json',
			success: function(jsonData) {
				
				// Salvou!
				if (jsonData.saved == true) {
					
					// Atualiza o grid com a linha nova (isso é necessário pro caso de celulas que precisem processar PHP)
					$.fn.yiiGridView.update(grid._gridId, {
						
						complete: function(jqXHR, status) {
							
							if (status=='success'){
								
								PerspectivaGrid.message('Os dados foram salvos com sucesso', 'success');
								
								// Remove elemento, caso necessite
								editableRow.find('td').each(function(index){
									if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
										grid.updateSelectOption(index);
									}
								});
								
								// Reinstancia o grid depois do reload do Yii
								//grid.init();
							}
						}
					});
				}
				
				// Exibe erros de validação
				else if (jsonData.errors != undefined) {
					
					var errorMessage = [];
					
					for (attribute in jsonData.errors) {
						
						jQuery('#' + grid.options.modelName + '_' + attribute).addClass('error erro');
						
						var list = [];
						
						for (i in jsonData.errors[attribute]) {
							list.push(jsonData.errors[attribute][i]);
						}
						
						errorMessage.push(list.join('<br />'));
					}
					
					errorMessage = errorMessage.join('<br />');
								
					PerspectivaGrid.message(errorMessage, 'error', true);
				}
			},
			error: function() {
				PerspectivaGrid.message('Ocorreu um erro ao processar a requisição', 'error');
			}
		});
	};
  
	PerspectivaGrid.prototype.exportData = function() {
		
		// Pega um formulario temporario
		var tempForm = this.getTempForm();
	  
		// Adiciona campos
		jQuery("#" + this._gridId + " .filters .fidelize-grid-field").add("#" + this._gridId + " .filters select").each(function(indice, elemento){

			// Clona pro browser não se perder
			var clone = jQuery(elemento).clone();
			clone.attr("id", "");

			tempForm.append(clone);
		});

		// Serializa filtros
		var filters = tempForm.serialize();

		var url = jQuery("#url-" + this._gridId).val();

		url += "?" + this.options.exportFlagName + "=true";

		// Remove form
		tempForm.remove();

		window.open(url, "_blank");
	};
	
	/**
	 * Atualiza select, se solicitado para html type select
	 */
	PerspectivaGrid.prototype.updateSelectOption = function(index) {
		var grid = this;
		
		var fieldType = grid.options.modelFormFieldsMapping[index]['type'];
		var fieldRefreshUrl = grid.options.modelFormFieldsMapping[index]['refreshDataUrl'];

		if (fieldType == 'select' && fieldRefreshUrl != '') {
			
			jQuery.ajax({
				url: fieldRefreshUrl,
				type: 'GET',
				dataType: 'json',
				success: function(jsonData) {	
					grid.options.modelFormFieldsMapping[index]['data'] = jsonData;
				}
			});
		}
	};
	
	/**
	 * Chama atualização de todos os elementos
	 */
	PerspectivaGrid.prototype.updateAllSelectOption = function() {
		var grid = this;
		
		for(index in grid.options.modelAttributesMapping) {
			if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
				grid.updateSelectOption(index);
			}
		}
	};
	
	/**
	 * Dump de variáveis
	 */
	PerspectivaGrid.prototype.log = function(variable) {

		if (this.options.debug && console != undefined && console.log != undefined) {
			console.log(variable);
		}
	};

	$.fn['PerspectivaGrid'] = function ( options ) {
		
		return this.each(function () {
			
			if (!$.data(this, 'plugin_PerspectivaGrid')) {
				
				$.data(this, 'plugin_PerspectivaGrid', new PerspectivaGrid( this, options ));
			}
		});
	}

}(jQuery, window));
