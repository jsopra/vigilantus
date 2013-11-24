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

window.perspectivaGrid = function( element, options ) {

	var defaults = {
		charset: 'UTF-8',
		debug: false,
		loadedInlineEditionCssClass: "edition-loaded",
		isExportable: true,
		isEditable: true,
		isUsingBootstrap: false,
		hasClearFilterButton: false,
		exportFlagName: 'export',
		modelName: null,
		modelAttributesMapping: {},
		modelFormFieldsMapping: {},
		blankDisplay: null,
		maxRowsToExportInstantly: 0,
		csvReceiverEmailFlagName: ''
	};
	
	this.element = jQuery(element);

	this.options = jQuery.extend( {}, defaults, options) ;

	this._defaults = defaults;
	this._name = 'perspectivaGrid';

	// Registra este grid numa vari?vel global
	if (window.loadedPGridView == undefined) window.loadedPGridView = {};
	window.loadedPGridView[this.element.attr('id')] = this;

	this.init();
};

/**
 * M?todo de classe que lan?a as mensagens pro usu?rio
 */
perspectivaGrid.message = function(text, type, sticky) {

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
 * Erros ao processar a requisi??o
 */
perspectivaGrid.requestError = function()
{
	perspectivaGrid.message('Ocorreu um erro ao processar a requisi??o', 'error');
};

/**
 * M?todo de classe que exibe uma mensagem ap?s uma exclus?o
 */
perspectivaGrid.afterDelete = function(link, success, data) {

	if (success) {

		var data = jQuery.parseJSON(data);
		var type = 'success';
		var message = 'O registro foi removido com sucesso';

		if (data && typeof data == 'object') {

			if (data.deleted !== undefined) {
				type = (data.deleted ? 'success' : 'error');
			}

			if (data.message) {
				message = data.message;
			}
		}

		perspectivaGrid.message(message, type);
	}
	else {
		perspectivaGrid.requestError();
	}
};

/**
 * Inicializa o grid
 */
perspectivaGrid.prototype.init = function () {
		
	var grid = this;
	
	// Atributos internos   
	this._gridId = this.element.attr('id');
	this._modelName = this.element.attr('data-model-name');

	this.log({'Inicializando grid com ID': this.element.attr('id')});
	
	// A??es dos bot?es
	if (this.options.isExportable) {
		
		jQuery('#' + this._gridId + '-export-btn').click(function(){
			grid.exportData();
		});
	}
    
    if (this.options.isFixed) {
        jQuery('#' + this._gridId + ' > .tabela-style').tableScroll({height:200});
    }
	
	if (this.options.isEditable) {
		
		// Instancia a a??o de editar inline nos bot?es de editar
		jQuery('#' + this._gridId + ' table .button-column .update').each(function(){
			
			jQuery(this).click(function(event){
				
				try {
					// Carrega o form de edi??o inline
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
		
		// Instancia a a??o de adicionar um novo
		jQuery('#' + this._gridId + ' table .add').each(function(){
			
			if (jQuery(this).attr('data-click-event') != undefined) {
				return;
			}
			
			jQuery(this).attr('data-click-event', 'true');
			
			jQuery(this).click(function(event){
				
				try {
					// Carrega o form de edi??o inline
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
	
	// Bot?o de excluir v?rios
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
			
			jQuery.ajax({
				url: jQuery(this).attr('data-url'),
				type: 'POST',
				data: {ids: idsList},
				success: function(data, status, xhr) {

					var deleted = true;
					var type    = 'success';

					// Uma resposta inesperada (n?o-JSON)
					var message = 'Os registros foram removidos com sucesso';

					if (typeof data == 'object') {

						deleted = data.deleted;
						message = data.message;

						if (deleted == false) {
							type = 'error';
						}
					}

					// Toast message
					perspectivaGrid.message(message, type);

					// Se excluiu
					if (deleted) {

						jQuery.fn.yiiGridView.update(grid._gridId, {
						
							complete: function(jqXHR, status) {
								
								if (status=='success') {
									
									//atualiza os selects, caso necessite
									grid.updateAllSelectOption();
									
									// Reinstancia o grid depois do reload do Yii
									grid.init();
								}
							}
						});
					}
				},
				error: function() {
					perspectivaGrid.requestError();
				}
			});
			
		});
	}
};

/**
 * Cria e retorna o objeto jQuery de um formul?rio tempor?rio
 * para que se possa chamar o m?todo serialize()
 */
perspectivaGrid.prototype.getTempForm = function() {
	
	var tempFormId = 'perspectivaGridTempForm';
	
	// Se n?o existe, cria
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
 * Duplica a linha que ser? editada, adicionando os campos de formul?rio e ocultando a linha original
 */
perspectivaGrid.prototype.loadInlineCreate = function(button) {
	
	var grid = this;
	
	// TODO TRATAR LINHAS ZEBRADAS
	
	// Pega as linhas
	var originalRow = jQuery(button).parentsUntil('tr').parent();
	var editableRow = originalRow.clone();
	
	// Adiciona a linha fantasma
	editableRow.attr('data-ajax-update-url', jQuery(button).attr('href'));
	originalRow.before(editableRow);
	
	// Carrega a edi??o
	grid.loadInlineEdition(editableRow.find('.add'), true);
};

/**
 * Duplica a linha que ser? editada, adicionando os campos de formul?rio e ocultando a linha original
 */
perspectivaGrid.prototype.loadInlineEdition = function(button, isNew) {
	
	var grid = this;

	// Tem alguma linha com edi??o carregada? Desativa
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
		
		// ? um campo edit?vel?
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
				
				formField = jQuery('<select />');
				
				for (optionIndex in fieldData) {

					var optionLabel = fieldData[optionIndex]['label'];
					var optionValue = fieldData[optionIndex]['value'];

					formField.append('<option value="' + optionValue + '">' + optionLabel + '</option>');
				}
				
				// Corrige bug de JS onde n?o selecionava a op??o
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

				formField = jQuery('<input />');
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
			// Data: como n?o usamos HTML5, ser? um <input type=text> com DatePicker
			else if (fieldType == 'date') {

				var uiLanguage = fieldPluginOptions['uiLanguage'];

				formField = jQuery('<input />');
				formField.attr('type', 'text');
				
				// Chama o datepicker s? depois de adicion?-lo na ?rvore DOM

				if (!isNew && element.html() != grid.options.blankDisplay) formField.val(element.html());
			}
			else if (fieldType == 'label') {
					
				formField = $('<span />');
				formField.html(element.html());
			}
			else if (fieldType == 'autocomplete') {
				var tmpIds;
				var tmpDesc;
				
				var optionPlaceholder = fieldPluginOptions['placeholder'] != undefined ? fieldPluginOptions['placeholder'] : 'Selecione...';
				var optionItems = fieldPluginOptions['items'] != undefined ? fieldPluginOptions['items'] : 20;
				var optionMinLength = fieldPluginOptions['minLength'] != undefined ? fieldPluginOptions['minLength'] : 4;
				
				formField = jQuery('<input />');
				formField.attr('type', 'hidden');
				
				var currentValue = (element.attr('data-current-value') != undefined && element.attr('data-current-value') != '') ? element.attr('data-current-value') : '0';
				if(currentValue != '0')
					formField.val(currentValue);
				
				var autocompleteField = jQuery('<input />');
				autocompleteField.attr('type', 'text');
				autocompleteField.attr('placeholder', optionPlaceholder);
				
				autocompleteField.typeahead({
					source: function (query, process) {
						return $.getJSON(
							grid.options.modelFormFieldsMapping[index]['refreshDataUrl'],
							{ query: query },
							function (data) {

								tmpIds = [];
								tmpDesc = [];

								$.each(data, function (i, element) {
									tmpIds[i] = element;
									tmpDesc.push(element);
								});

								return process(tmpDesc);
							}
						);
					},
					updater: function (item) {

						var valor = jQuery.inArray(item, tmpIds);		
						if(valor == -1)
							return;

						formField.val(valor);

						return item;
					},
					items: optionItems,
					minLength: optionMinLength,
					matcher: function(item) {
						return ~item.toLowerCase().indexOf(this.query.toLowerCase());
					}
				});
			}
			// Padr?o ? texto
			else {
				
				formField = jQuery('<input />');
				formField.attr('type', 'text');
			
				if (!isNew && element.html() != grid.options.blankDisplay) formField.val(element.html());
			}

			// Op??es personalizadas de HTML
			for (attr in fieldHtmlOptions) {
				grid.log({'Setando configura??o personalizada do campo:': {attr: fieldHtmlOptions[attr]} } );
				formField.attr(attr, fieldHtmlOptions[attr]);
			}
	
			formField.addClass('perspectiva-grid-field');
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
			else if (fieldType == 'autocomplete') {
				formField.attr('id', grid.options.modelName + '_' + attributeName);
				element.html(formField);
				formField.after(autocompleteField);
			}
			else {
				formField.attr('id', grid.options.modelName + '_' + attributeName);
				element.html(formField);

				// Se for uma data, agora pode chamar o datepicker
				if (fieldType == 'date') {
					formField.datepicker(jQuery.extend(jQuery.datepicker.regional[uiLanguage], fieldPluginOptions));
				}
			}
			
			if(fieldHtmlOptions['prepend'] != undefined) {
				var span = jQuery('<span />');
				span.attr('class', 'add-on');
				span.attr('style', 'padding: 0 0.5em; font-weight: bold;');
				span.html(fieldHtmlOptions['prepend']);
				formField.after(span);
				formField.attr('style', 'width: 50%	;');
			}
		}
		// ? a coluna dos bot?es?
		else if (element.hasClass('button-column')) {

			var confirmar = jQuery('<a />');
			confirmar.attr('href', 'javascript:void(0)');

			var cancelar = confirmar.clone();

			confirmar.html('Confirmar');
			cancelar.html('Cancelar');

			if (grid.options.isUsingBootstrap) {
				confirmar.addClass('confirm icon-ok-sign tr_confirm');
				cancelar.addClass('cancel icon-remove-sign tr_cancel');
			}
			else {
				confirmar.addClass('confirm tbl_icon tr_confirm');
				cancelar.addClass('cancel tbl_icon tr_cancel');
			}
			
			element.html(confirmar);
			element.append(cancelar);
			
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
	});
	
	// Oculta a linha original e carrega a edit?vel
	originalRow.hide();
	originalRow.after(editableRow);
        editableRow.trigger("inLineEditStart");
};

/**
 * Cancela a edi??o inline removendo a <tr/> nova e exibindo a original
 */
perspectivaGrid.prototype.cancelInlineEdition = function(button) {
	
	// Pega as linhas
	var editableRow = jQuery(button).parentsUntil('tr').parent();
	var originalRow = editableRow.prev();
	
	// Remove a linha edit?vel e carrega a original
	originalRow.show();
	editableRow.remove();
};

/**
 * Cancela a cria??o inline removendo os <tr/> novos
 */
perspectivaGrid.prototype.cancelInlineCreate = function(button) {
	
	var editableRow = jQuery(button).parentsUntil('tr').parent();
	var originalRow = editableRow.prev();
	
	originalRow.remove();
	editableRow.remove();
};

/**
 * Posta a edi??o inline e testa a valida??o
 */
perspectivaGrid.prototype.confirmInlineEdition = function(button) {
	var grid = this;
	
	// Pega as linhas
	var editableRow = jQuery(button).parentsUntil('tr').parent();
	var originalRow = editableRow.prev();
	
	// Coleta os dados
	var tempForm = grid.getTempForm();
	
	editableRow.find('td').each(function(index, element){
		
		// Adiciona somente colunas edit?veis (e n?o campos de check para exclus?o, etc.)
		if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
			
			// Pega o campo do form
			var campo = jQuery(element).children();
			var novoCampo = null;

			// ? radio ou checkbox?
			var isCheckbox = false;
			var isRadio = false;
			var isAutocomplete = false;

			if (grid.options.modelFormFieldsMapping != undefined && grid.options.modelFormFieldsMapping[index] != undefined) {

				isCheckbox = (grid.options.modelFormFieldsMapping[index]['type'] == 'checkbox');
				isRadio = (grid.options.modelFormFieldsMapping[index]['type'] == 'radio');
				isAutocomplete = (grid.options.modelFormFieldsMapping[index]['type'] == 'autocomplete');
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
			else if(isAutocomplete) {
				novoCampo = jQuery(element).find('input[type="hidden"]').clone();
			}
			else {
				novoCampo = campo.clone();
			}

			tempForm.append(novoCampo);

			// Garante que o valor continua id?ntico
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
	
	if (grid.options.charset.toUpperCase() != 'UTF-8') {
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
		success: function(jsonData, status, xhr) {

			if (typeof jsonData != 'object') {
				return perspectivaGrid.requestError();
			}

			var stickyMessage = false;
			var messageType = 'success';
			var message = null;

			// Caso retorne uma mensagem
			if (jsonData.message != undefined) {
				message = jsonData.message;
			}
			
			// Salvou!
			if (jsonData.saved == true) {

				// Se n?o tem mensagem
				if (message == null) {
					message = 'Os dados foram salvos com sucesso';
				}

				grid.log('Atualizando lista...');

				jQuery('#' + grid._gridId).yiiGridView('update', {
					data: {
						clear: new Date().getTime()
					},
					complete: function(jqXHR, status) {

						grid.log('Completou AJAX lista!');
						
						if (status == 'success') {

							grid.log('Success atualizar lista!');
							
							// Remove elemento, caso necessite
							editableRow.find('td').each(function(index){
								if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
									grid.updateSelectOption(index);
								}
							});
						}
					}
				});
			}
			else {

				messageType = 'error';
				stickyMessage = true;

				// Se n?o tem mensagem
				if (message == null) {
					message = 'Ocorreu um erro ao salvar os dados';
				}

				// Exibe erros de valida??o
				if (jsonData.errors != undefined) {
				
					var errorMessage = [];
					
					for (attribute in jsonData.errors) {
						
						jQuery('#' + grid.options.modelName + '_' + attribute).addClass('error erro');
						
						var list = [];
						
						for (i in jsonData.errors[attribute]) {
							list.push(jsonData.errors[attribute][i]);
						}
						
						errorMessage.push(list.join('<br />'));
					}
					
					message = errorMessage.join('<br />');
				}
			}

			perspectivaGrid.message(message, messageType, stickyMessage);
		},
		error: function() {
			perspectivaGrid.requestError();
		}
	});
};

perspectivaGrid.prototype.exportData = function() {
	
	// Pega um formulario temporario
	var tempForm = this.getTempForm();
  
	// Adiciona campos
	jQuery("#" + this._gridId + " .filters .perspectiva-grid-field").add("#" + this._gridId + " .filters select").each(function(indice, elemento){

		// Clona pro browser n?o se perder
		var clone = jQuery(elemento).clone();
		clone.attr("id", "");

		tempForm.append(clone);
	});

	var grid = this;

	var funcaoExportar = function(email) {

		// Par?metros da URL
		var urlParams = grid.options.exportFlagName + '=true';

		// Serializa filtros
		var filters = tempForm.serialize();
		tempForm.remove();

		var url = jQuery("#url-" + grid._gridId).val();

		if (-1 == url.indexOf('?')) {
			url += '?';
		}
		else {
			url += '&';
		}

		// Se manda por email
		if (email != undefined) {

			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			if (!re.test(email)) {
				return perspectivaGrid.message('Digite um e-mail v?lido.', 'error');
			}

			urlParams += '&' + grid.options.csvReceiverEmailFlagName + '=' + encodeURI(email);
		}

		url += urlParams;

		if (email == undefined) {
			window.open(url, '_blank');
		}
		else {

			jQuery('.ui-dialog-buttonset button:first-child').attr('disabled', 'disabled');

			jQuery.ajax({
				url: url,
				type: 'get',
				success: function(data) {

					var successMessage = 'A planilha foi solicitada com sucesso!';

					if (data == '_JOB_REPETIDO_') {
						successMessage = 'A planilha j? foi solicitada e est? sendo processada.';
					}
					else if (data == '_JOB_FINALIZADO_') {
						successMessage = 'A planilha foi gerada com sucesso. Verifique a caixa de entrada do seu e-mail. Caso n?o encontre o link, verifique a caixa de lixo eletr?nico.';
					}

					perspectivaGrid.message(successMessage, 'success');
				},
				error: function() {
					perspectivaGrid.message('Ocorreu um erro ao solicitar a gera??o da planilha.', 'error');
				},
				complete: function() {
					jQuery('.ui-dialog-buttonset button:first-child').removeAttr('disabled')
					jQuery('#' + grid._gridId + '-dialog').dialog('close');
				}
			});
		}
	}

	// Se a quantidade de registros extrapolar a m?xima
	var rowsTotal = parseInt(jQuery('#rows-total-' + this._gridId).val(), 10);
	var rowsLimit = parseInt(jQuery('#rows-limit-' + this._gridId).val(), 10);

	if (rowsTotal > rowsLimit) {

		var mensagem = 'Quantidade de dados grande demais (acima de ';
		mensagem += rowsLimit + ' registros). Por favor filtre as informa??es.';

		return perspectivaGrid.message(mensagem, 'notice');
	}

	// Se a quantidade de registros for muito alta
	var isStreamed = (parseInt(jQuery('#is-streamed-' + this._gridId).val(), 10) != 0);

	if (isStreamed == false) {

		jQuery('#' + this._gridId + '-dialog').dialog('open');

		var buttonExport = jQuery('.ui-dialog-buttonset button:first-child');

		if (!buttonExport.data('clicking-set')) {

			buttonExport.data('clicking-set', 'true');

			buttonExport.click(function(){
				funcaoExportar(
					jQuery('#' + grid._gridId + '-dialog input').val()
				);
			});
		}
	}
	else {
		funcaoExportar();
	}
};

/**
 * Atualiza select, se solicitado para html type select
 */
perspectivaGrid.prototype.updateSelectOption = function(index) {
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
 * Chama atualiza??o de todos os elementos
 */
perspectivaGrid.prototype.updateAllSelectOption = function() {
	var grid = this;
	
	for(index in grid.options.modelAttributesMapping) {
		if (grid.options.modelAttributesMapping != undefined && grid.options.modelAttributesMapping[index] != undefined) {
			grid.updateSelectOption(index);
		}
	}
};

/**
 * Dump de vari?veis
 */
perspectivaGrid.prototype.log = function(variable) {

	if (this.options.debug && console != undefined && console.log != undefined) {
		console.log(variable);
	}
};

(function ( $, window, undefined ) {

	$.fn['perspectivaGrid'] = function ( options ) {
		
		return this.each(function () {
			
			if (!$.data(this, 'plugin_perspectivaGrid')) {
				
				$.data(this, 'plugin_perspectivaGrid', new perspectivaGrid( this, options ));
			}
		});
	}

}(jQuery, window));
