<?php

Yii::import('zii.widgets.grid.CGridView');

/**
 * Especialização do CGridView Yii, que possibilita:
 * 
 * <ul>
 *	<li>Edição dos dados de um modelo inline (na tabela)</li>
 *  <li>Um botão para exportar os dados em CSV sem necessidade de qualquer configuração manual</li>
 *  <li>Exclusão em massa</li>
 * </ul> 
 */
class PGridView extends CGridView
{	
	// Table types.
	const TYPE_STRIPED = 'striped';
	const TYPE_BORDERED = 'bordered';
	const TYPE_CONDENSED = 'condensed';
	const TYPE_HOVER = 'hover';
	
	/**
	 * @var string|array the table type.
	 * Valid values are 'striped', 'bordered' and/or 'condensed'.
	 */
	public $type;
	
	/**
	 * Se será feito debug do grid ou não (inclusive do JavaScript)
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * Classe da tabela
	 * @var string
	 */
	public $itemsCssClass = 'tabela-style';
	
	/**
	 * Indica se este grid possui um botão que permite exportar seus dados
	 * @var boolean
	 */
	public $isExportable = false;
	
	/**
	 * Indica se este grid possui edição inline (na tabela)
	 * @var boolean 
	 */
	public $isEditable = false;
	
    /**
     * Nome da variável GET pra indicar que está exportando
     * @var string 
     */
    public $exportFlagName = 'export';
    
    /**
     * Nome do arquivo exportado. Será dado append no AnoMesDia.
     * @var string 
     */
    public $exportFileName = 'export';
    
    /**
     * Label do botão que exporta
     * @var string 
     */
    public $exportButtonLabel = 'Exportar Planilha';
    
    /**
     * Label do botão que limpa o filtro
     * @var string 
     */
    public $clearFilterButtonLabel = 'Limpar Filtro';
    
    /**
     * Parâmetro para limpar filtros salvos na sessão
     * @var string 
     */
    public $clearSessionFilterUrlParam = 'limpa_sessao';
    
    /**
     * Ativa ou desativa o botão de limpar o filtro
     * @var boolean 
     */
    public $clearFilterButtonEnabled;
    
    /**
     * Ativa ou desativa o botão de cadastrar
     * @var boolean 
     */
    public $createButtonEnabled;
    
    /**
     * Ativa ou desativa o botão de excluir vários registros
     * @var boolean 
     */
    public $deleteManyButtonEnabled;
    
    /**
     * Quantidade de registros que carregará por consulta (para não estourar a memória)
     * @var integer 
     */
    public $recordsLoadingStep = 1000;
	
	/**
	 * Mapeamento de posição das células e atributos de um grid com edição inline
	 * 
	 * Por exemplo: célula 0 terá o campo do atributo "nome_completo", etc.
	 * 
	 * Por padrão pegará todas as colunas do grid que tenham definida a propriedade "name" que referencia o modelo.
	 * 
	 * @var array 
	 */
	public $modelAttributesMapping = null;
	
	/**
	 * Nome do modelo da edição inline. Por padrão tenta encontrar o modelo do dataProvider
	 * 
	 * @var string 
	 */
	public $modelName = null;
	
	/**
	 * Expressão PHP para a URL onde serão postados os dados da edição, e que
	 * deverá retornar um JSON com erros de validação e o status, no seguinte formato:
	 * 
	 * Sucesso: {saved: true}
	 * Erro:    {saved: false, errors: { ...erros... } }
	 * 
	 * @var string 
	 */
	public $ajaxUpdateUrlExpression = null;
	
	/**
	 * URL onde serão postados os dados da inserção, e que deverá retornar 
	 * um JSON com erros de validação e o status, no seguinte formato:
	 * 
	 * Sucesso: {saved: true}
	 * Erro:    {saved: false, errors: { ...erros... } }
	 * 
	 * @var string 
	 */
	public $ajaxCreateUrl = null;
	
	/**
	 * Se permite mostrar um botão para excluir várias linhas ao mesmo tempo.
	 * 
	 * Funciona melhor com uma coluna do tipo checkbox:
	 * 
	 * array(
	 *		'id'             => 'selectedItems',
	 *		'class'          => 'CCheckBoxColumn',
	 *		'selectableRows' => 2,
	 *	),
	 * 
	 * NULL    = Detecta a URL correta automaticamente (modelo/ajaxDelete)
	 * FALSE   = Desativa a funcionalidade
	 * STRING  = URL da action de exclusão
	 * 
	 * @var null|boolean|string 
	 */
	public $deleteUrl = null;
	
	/**
	 * Nome do botão de exclusão.
	 * @var string 
	 */
	public $deleteLabel = 'Excluir';
	
	/**
	 * Se permite mostrar um botão para cadastrar um novo modelo.
	 * 
	 * NULL    = Detecta a URL correta automaticamente (modelo/create)
	 * FALSE   = Desativa a funcionalidade
	 * STRING  = URL da action de cadastro
	 * 
	 * @var null|boolean|string 
	 */
	public $createUrl = null;
	
	/**
	 * Nome do botão de cadastro.
	 * @var string 
	 */
	public $createLabel = 'Cadastrar';
	
    /**
     * Filtros que não vieram do CGridView e precisam ser renderizados
     * @var array 
     */    
    protected $extraFilters = array();
	
	/**
	 * @var string the CSS class name for the pager container. Defaults to 'pagination'.
	 */
	public $pagerCssClass = 'pagination';
	/**
	 * @var array the configuration for the pager.
	 * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
	 */
	public $pager = array('class'=>'bootstrap.widgets.TbPager');
	
	/**
	 * Inicializa o grid
	 */
	public function init()
	{
		parent::init();
		
		// Se o filtro foi desativado, evita bugs
		if (!is_object($this->filter)) {
			$this->filter = null;
		}

		// Default de botão de limpar filtro
		if ($this->isEditable && $this->clearFilterButtonEnabled === null) {
			$this->clearFilterButtonEnabled = true;
		}

		// Se for um dataprovider com modelo
		$refersToModel = isset($this->dataProvider->model);

		if ($refersToModel) {

			// Default dos botões de cadastro/excluir vários
			if ($this->deleteManyButtonEnabled === null) {
				$this->deleteManyButtonEnabled = true;
			}
			if ($this->createButtonEnabled === null) {
				$this->createButtonEnabled = true;
			}

			if ($this->createUrl === false) {
				$this->createButtonEnabled = false;
			}
			if ($this->deleteUrl === false) {
				$this->deleteManyButtonEnabled = false;
			}
			
			// Se não foi definido o nome do modelo
			if ($this->modelName === null) {

				$this->modelName = get_class($this->dataProvider->model);
			}
				
			// Temporário
            $moduleName = Yii::app()->controller->module ? Yii::app()->controller->module->id : null;
			$controllerName = $this->modelName;

			if ($controllerName) {
				$controllerName[0] = strtolower($controllerName[0]);
			}
			
			// Se for um grid com edição inline
			if ($this->isEditable) {
				
				// Se não foram definidos os atributos do modelo carrega os "name" das colunas
				if ($this->modelAttributesMapping === null) {
					
					$this->modelAttributesMapping = array();
					
					foreach ($this->columns as $k => $column) {
					
						if (isset($column->name)) {

							if (!$column->headerHtmlOptions) {
								$column->headerHtmlOptions = array();
							}

							$this->modelAttributesMapping[$k] = $column->name;
						}
					}
				}
				
				// Se não foi definido o nome do parâmetro "ajax"
				if ($this->ajaxUpdateUrlExpression === null) {
					
					$this->ajaxUpdateUrlExpression = 'Yii::app()->createUrl("' . $moduleName . '/' . $controllerName . '/ajaxSave", array("id" => $data->id))'; 
				}
				
				// Se não foi definida a URL para criação de novos usuários
				if ($this->ajaxCreateUrl === null) {
					$this->ajaxCreateUrl = Yii::app()->createUrl($moduleName . '/' . $controllerName . '/ajaxSave');
				}
			}
			
			// Se precisa detectar automaticamente estas URLs (false = não usado)
			if ($this->createButtonEnabled && $this->createUrl === null) {
				$this->createUrl = Yii::app()->createUrl($moduleName . '/' . $controllerName . '/create');
			}
			
			if ($this->deleteManyButtonEnabled !== false) {

				// URL padrão de exclusão em massa
				if ($this->deleteUrl === null) {
					$this->deleteUrl = Yii::app()->createUrl($moduleName . '/' . $controllerName . '/ajaxDelete');
				}
				
				$hasCheckboxColumn = false;
				
				foreach ($this->columns as $column) {
					
					if ($column instanceof PCheckBoxColumn && $column->selectableRows == 2) {
						$hasCheckboxColumn = true;
						break;
					}
				}
				
				// Se não houver uma coluna com os checkboxes, desativa a exclusão em massa
				if (!$hasCheckboxColumn) {
					$this->deleteManyButtonEnabled = false;
				}
			}
		}
		// Se não se refere a um modelo
		else {

			// Default dos botões de cadastro/excluir vários
			if ($this->deleteManyButtonEnabled === null)
				$this->deleteManyButtonEnabled = false;

			if ($this->createButtonEnabled === null)
				$this->createButtonEnabled = false;

			// Não foi definido o nome do modelo? Então... 
			if ($this->modelName === null) {
				
				// ... não consegue adivinhar a URL de criação
				if ($this->createButtonEnabled && $this->createUrl === null)
					throw new Exception(Yii::t(
						'Perspectiva', 
						'Informe o nome do modelo ("modelName") para detectar a URL de cadastro ou a informe explicitamente ("createUrl").'
					));
				
				// ... não consegue adivinhar a URL de exclusão em massa
				if ($this->deleteManyButtonEnabled && $this->deleteUrl === null)
					throw new Exception(Yii::t(
						'Perspectiva', 
						'Informe o nome do modelo ("modelName") para detectar a URL de exclusão em massa ou a informe explicitamente ("deleteUrl").'
					));
				
				// ... se for um grid com edição inline...
				if ($this->isEditable) {
					
					// ... não consegue adivinhar a URL de cadastro inline
					if ($this->ajaxCreateUrl === null)
						throw new Exception(Yii::t(
							'Perspectiva', 
							'Informe o nome do modelo ("modelName") para detectar a URL do AJAX do cadastro inline ou a informe explicitamente ("ajaxCreateUrl").'
						));

					// ... não consegue adivinhar a URL de atualização inline
					if ($this->ajaxUpdateUrlExpression === null)
						throw new Exception(Yii::t(
							'Perspectiva', 
							'Informe o nome do modelo ("modelName") para detectar a URL do AJAX de atualização inline ou a informe explicitamente ("ajaxUpdateUrlExpression").'
						));
				}
			}
		}
		
		if (isset($this->type))
		{
			if (is_string($this->type))
				$this->type = explode(' ', $this->type);

			if (!empty($this->type))
			{
				$validTypes = array(self::TYPE_STRIPED, self::TYPE_BORDERED, self::TYPE_CONDENSED, self::TYPE_HOVER);

				foreach ($this->type as $type)
				{
					if (in_array($type, $validTypes))
						$classes[] = 'table-'.$type;
				}
			}
		}
		
		// Coloca a função de recarregar o PGridView no CGridView
		$userDefinedFunction = $this->afterAjaxUpdate;

		if ($userDefinedFunction != null) {

			$userDefinedFunction = ' var userDefinedFunction = ' . $userDefinedFunction . '; '
			                     . 'userDefinedFunction(gridId, ajaxResponseData); '
			;
		}

		// Não podemos deixar sobrescrever o init()
		$this->afterAjaxUpdate = 'function(gridId, ajaxResponseData){ window.loadedPGridView[gridId].init(); ' . $userDefinedFunction . ' }';

		// Função de erro padrão
		if ($this->ajaxUpdateError == null) {

			$this->ajaxUpdateError = 'function(xhr,textStatus,errorThrown,errorMessage){ PerspectivaGrid.message(xhr.responseText, "error"); }';
		}
	}
	
	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		// Icones personalizados para todos os grids
		foreach ($this->columns as $k => $column) {
			
			// Botões
            if (is_array($column) && isset($column['class']) && strpos($column['class'],'PButtonColumn') !== false) {
                
                /*if (!isset($column['viewButtonImageUrl']))
                    $this->columns[$k]['viewButtonImageUrl']   = Yii::app()->baseUrl.'/../webol/images/grid/view.png';
                if (!isset($column['updateButtonImageUrl']))
                    $this->columns[$k]['updateButtonImageUrl'] = Yii::app()->baseUrl.'/../webol/images/grid/update.png';
                if (!isset($column['deleteButtonImageUrl']))
                    $this->columns[$k]['deleteButtonImageUrl'] = Yii::app()->baseUrl.'/../webol/images/grid/delete.png';
                */
				$this->columns[$k]['updateButtonOptions'] = array('class' => 'update icon-pencil','rel'=>'tooltip','data-title'=>'Atualizar');
				$this->columns[$k]['deleteButtonOptions'] = array('class' => 'delete icon-trash','rel'=>'tooltip','data-title'=>'Deletar');
            }
			// Se for uma coluna padrão
			else if (is_string($column)) {
				
				$this->columns[$k] = array(
					'name'  => $column,
					'class' => 'ext.internal.PDataColumn',
				);
			}
			// Se não tem classe definida
			else if (is_array($column) && !isset($column['class'])) {
				$this->columns[$k]['class'] = 'ext.internal.PDataColumn';
			}
        }
        
		// Instancia normalmente
        parent::initColumns();
    }
	
	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		$htmlOptions = array();
		
		if($this->rowCssClassExpression!==null)
		{
			$data=$this->dataProvider->data[$row];
			$htmlOptions['class'] = $this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data));
		}
		else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			$htmlOptions['class'] = $this->rowCssClass[$row%$n];
		
		// Se for um grid edit?vel, precisa armazenar a URL com o ID de destino
		if ($this->isEditable) {
			
			$data=$this->dataProvider->data[$row];
			$htmlOptions['data-ajax-update-url'] = $this->evaluateExpression($this->ajaxUpdateUrlExpression, array('data'=>$data));
		}
		
		echo CHtml::openTag('tr', $htmlOptions);
		
		foreach($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}
	
	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<tbody>\n";

		if($n>0)
		{
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else
		{
			echo '<tr><td class="empty" colspan="'.count($this->columns).'">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		
		// Linha para adição de campos
		if ($this->isEditable && $this->ajaxCreateUrl !== false) {
			
			$row = isset($row) ? $row : 1;
			
			if ($this->rowCssClassExpression !== null && isset($this->dataProvider->data[$row])) {
				$data=$this->dataProvider->data[$row];
				echo '<tr class="'.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data)).'">';
			}
			else if (is_array($this->rowCssClass) && ($n=count($this->rowCssClass)) > 0)
				echo '<tr class="'.$this->rowCssClass[$row%$n].'">';
			else
				echo '<tr>';
			
			$totalColumns = count($this->columns);
			
			for ($i = 0; $i < ($totalColumns - 1); $i++)
				echo '<td>', $this->blankDisplay, '</td>';
			
			echo '<td class="button-column"><a href="' . $this->ajaxCreateUrl . '" class="add icon-plus-sign" rel="tooltip" data-title="Cadastrar">Adicionar</a></td>';
				
			echo "</tr>\n";;
		}
		
		echo "</tbody>\n";
	}
    
    /**
     * Renderiza o botão que limpa os filtros 
     */
    public function renderClearFilterButton()
    {
		$params = $_GET;
        if(!isset($params['r'])){
            $request = 'site/index';
        }else{
            $request = $params['r'];
            unset($params['r']);
        }
        
        $modelClass = get_class($this->dataProvider->model);
        
        if (isset($params[$modelClass])) {
            unset($params[$modelClass]);
        }
        
        if (isset($params['ajax'])) {
            unset($params['ajax']);
        }
        
        // Para casos onde usa filtro na sessão
        $params[$this->clearSessionFilterUrlParam] = 1;
        
        $url = Yii::app()->createUrl($request, $params);
        
        echo 
            '<input class="btn button-clear" type="button" value="',
            $this->clearFilterButtonLabel,
            '" onclick="window.location=\'',
            $url,
            '\'">'
        ;
    }
    
    /**
	 * Renders the data items for the grid view.
     * @return string
	 */
	public function renderItems()
	{
		// Publica assets
		$assetsUrl = Yii::app()->getAssetManager()->publish(
			Yii::getPathOfAlias('ext.internal.assets'), // deve apontar para o diretório atual
			false, // FALSE $hashByName
			-1, //$level=-1
			YII_DEBUG //$forceCopy=NULL
		);

		// Registra assets e jQuery
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/pgridview.js');
		Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/jquery.toastmessage.js');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/main.css');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/pgridview.css');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/jquery.toastmessage.css');
		
		$options = array(
			'isEditable'              => $this->isEditable,
			'isExportable'            => $this->isExportable,
			'hasClearFilter'          => $this->clearFilterButtonEnabled,
			'modelAttributesMapping'  => $this->modelAttributesMapping,
			'modelName'               => $this->modelName,
			'blankDisplay'            => $this->blankDisplay,
			'charset'                 => Yii::app()->charset,
			'debug'                   => ($this->debug ? true : false),
		);
		
		// Se tem botões
		if ($this->isExportable || $this->createUrl !== false || $this->deleteUrl !== false) {
			
			echo '<div class="grid-buttons">', "\n";
			
			
			// Tem a funcionalidade de exportar ativa?
			if ($this->isExportable) {

				echo '
				<div class="grid-export-button"><input class="btn button-export" type="button" id="'
				. $this->getId() . '-export-btn" value="'. $this->exportButtonLabel . '" />
				';

				if ($this->filter && $this->clearFilterButtonEnabled) {

					$this->renderClearFilterButton();
				}

				echo "</div>\n";

				// Configurações passadas pro JS
				$options['exportFlagName'] = $this->exportFlagName;
			}

			// Se tem o botão de cadastrar ou excluir
			if ($this->createButtonEnabled || $this->deleteManyButtonEnabled) {
				
				echo '<div class="grid-crud-buttons">';
				
				// Botão de excluir?
				if ($this->deleteManyButtonEnabled) {
					
					echo '<input type="button" class="btn delete-many" data-url="',
						$this->deleteUrl, '" value="', $this->deleteLabel, '" />'
					;
				}
				
				// Botão de cadastrar?
				if ($this->createButtonEnabled) {
					
					echo '<a class="link button-create" href="',
						$this->createUrl, '">', $this->createLabel, '</a>'
					;
				}
				
				echo '</div>';
			}
			
			echo '</div>';
		}

		// Configurações de linhas editáveis
		if ($this->isEditable) {

			$options['modelFormFieldsMapping'] = array();

			// Pega o esquema da tabela para detectar informações adicionais
			$tableSchema = (isset($this->dataProvider->model) ? $this->dataProvider->model->getTableSchema() : null);

			foreach ($this->columns as $index => $column) {

				if ($column instanceof PDataColumn && $column->name && isset($tableSchema->columns[$column->name])) {

					$tableColumn = ($tableSchema ? $tableSchema->columns[$column->name] : null);

					$fieldData = array();

					// Garante a mesma ordem dos dados no JS
					if (is_array($column->fieldData) && count($column->fieldData) > 0) {

						foreach ($column->fieldData as $value => $label) {

							$fieldData[] = array(
								'value' => $value,
								'label' => $label,
							);
						}
					}

					$fieldHtmlOptions = $column->fieldHtmlOptions ? $column->fieldHtmlOptions : array();
					$pluginOptions = $column->fieldPluginOptions ? $column->fieldPluginOptions : array();

					// Seta configurações adicionais baseadas no banco de dados
					if ($column->fieldType == 'text' && $tableColumn) {

						// Maxlength
						if (!isset($fieldHtmlOptions['maxlength']) && $tableColumn->type == 'string' && $tableColumn->size) {
							$fieldHtmlOptions['maxlength'] = $tableColumn->size;
						}
					}

					// Configurações padrão para um campo do tipo Date
					if ($column->fieldType == 'date') {

						// Inclui os assets necessários
						$jQueryUiCssFile = Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css';
						
						Yii::app()->clientScript->registerCoreScript('jquery.ui');
						Yii::app()->clientScript->registerCssFile($jQueryUiCssFile);

						$pluginOptions = array(
							'uiLanguage' => Yii::app()->language,
							'showMonthAfterYear' => false,
							'dateFormat' => Date::getDateFormat(),
							'changeMonth' => true,
							'changeYear' => true,
							'showButtonPanel' => true,
							'constrainInput' => true,
							'showAnim' => 'slide',
							'duration' => 'fast',
						);

						// Mescla configurações padrão com as do usuário
						$pluginOptions = array_merge($pluginOptions, $column->fieldPluginOptions);

						// Converte formato de data do PHP pro JS
						$pluginOptions['dateFormat'] = preg_replace('/(y(?=y))?(d(?=dd))?(m(?=mm))?/', '', strtolower($pluginOptions['dateFormat']));

						// Corrige código do idioma que é diferente no JS
						switch (substr(strtolower($pluginOptions['uiLanguage']), 0, 2)) {

							case 'en':
								$pluginOptions['uiLanguage'] = 'en-GB';
								break;

							case 'pt':
								$pluginOptions['uiLanguage'] = 'pt-BR';
								break;
						}
					}

					$options['modelFormFieldsMapping'][$index] = array(
						'type'           => $column->fieldType,
						'data'           => $fieldData,
						'htmlOptions'    => $fieldHtmlOptions,
						'pluginOptions'  => $pluginOptions,
						'refreshDataUrl' => $column->fieldRefreshDataUrl,
					);
				}
			}
		}
		
		// Instancia os scripts do GRID
		Yii::app()->clientScript->registerScript('Perspectiva-grid-instance-' . $this->getId(), '
		jQuery("#' . $this->getId() . '").PerspectivaGrid(' . CJSON::encode($options) . ');
		', CClientScript::POS_READY);
        
        parent::renderItems();
    }
    
    /**
     * Verifica se printa a tabela ou retorna a planilha CSV
     */
    public function run()
    {
        if (isset($_GET[$this->exportFlagName]) && $_GET[$this->exportFlagName]) {
            
            // Sem limite de tempo
            set_time_limit(0);
            
            // Limpa qualquer saída do navegador
            ob_clean();
            
            // Headers
            if ($this->debug) {

            	header("Content-type: text/plain; charset=" . Yii::app()->charset);
            }
			else {
                
                header("Content-Type: application/csv; charset=" . Yii::app()->charset);
                header('Content-Disposition: attachment; filename="' . $this->exportFileName . '_' . date('YmdHis') . '.csv"');
			}
            
            // Abre pra escrever na tela
            $handle = fopen('php://output', 'w');
            
            // Monta header
            $header = array();
            
            foreach ($this->columns as $column) {
                
                if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn'))) {
                    continue;
                }
                
                $name = null;
                
                if ($column->header) {
                    $name = $column->header;
                }
                elseif ($this->dataProvider instanceof CActiveDataProvider) {
                    $name = $this->dataProvider->model->getAttributeLabel($column->name);
                }
				elseif ($column->name) {
					$name = $column->name;
				}
                
                $header[] = $name;
            }
			
			// Corrige bug com o Excel quando a primeira coluna se chama ID
			if (isset($header[0]) && strtoupper($header[0]) == 'ID') {
				$header[0] = ' ' . $header[0];
			}
            
            // Imprime cabeçalho
            fputcsv($handle, $header, ';');
            
            // Seta em quantos registros por vez ele carrega (pra não estourar a memória)
            $pagination = $this->dataProvider->getPagination();
            $pagination->setPageSize($this->recordsLoadingStep);
            
            $steps = $pagination->getPageCount();
            
            for ($currentStep = 0; $currentStep < $steps; $currentStep++) {
                
                // Muda bloco atual
                $pagination->setCurrentPage($currentStep);
                
                // Obtém dados
                $rows = $this->dataProvider->getData(true);
            
                // Monta o CSV
                foreach ($rows as $data) {

                    $row = array();

                    foreach ($this->columns as $column) {

                        if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn'))) {
                            continue;
                        }

                        $value = null;

                        if ($column->value !== null) {

                            $value = $this->evaluateColumnExpression($column->value, array('data'=>$data));
                        }
                        else if ($column->name !== null) {
                            $value = CHtml::value($data, $column->name);
                        }

                        $row[] = strip_tags(($value === null) ? '' : $this->getFormatter()->format($value, $column->type));
                    }

                    // Imprime na tela
                    fputcsv($handle, $row, ';');
                }
            }

            // Encerra
            fclose($handle);
            
            exit;
        }
        else {
            
            $this->registerClientScript();

            echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
            
            // Se houver filtro
            if ($this->filter != null && isset($_GET[get_class($this->dataProvider->model)])) {
                
                // Colunas que se referem a atributos
                $attributesNames = array();

                foreach ($this->columns as $column) {

                    if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn'))) {
                        continue;
                    }

                    if ($column->name) {
                        $attributesNames[] = $column->name;
                    }
                }

                // Para cada atributo não nulo do modelo, ele tenta aplicar qualquer possível filtro
                $extraFilters = array();
                
                $class = $this->dataProvider->modelClass;
                
                $this->dataProvider->model->setScenario('search');
            
                $this->dataProvider->model->attributes = $_GET[$class];
                
                // Pega nome dos atributos reais
                $classAttributes = array_keys($this->dataProvider->model->attributeNames()) + get_class_vars($class);

                foreach ($_GET[$class] as $attribute => $value) {

                    // Se tiver valor, não for array, e não estiver nas colunas do grid (e for um atributo válido, claro)
                    if ($value && !is_array($value) && !in_array($attribute, $attributesNames) && 
                        (property_exists($class, $attribute) || in_array($attribute, $classAttributes) )) {

                        $extraFilters[] = $attribute;
                    }
                }
                
                // Seta para que o renderizador pegue adiante
                $this->extraFilters = $extraFilters;
            }
            
            echo CHtml::hiddenField('url-' . $this->getId(), $_SERVER['REQUEST_URI']) . "\n";
            
            $this->renderContent();

            if ($this->dataProvider && $this->dataProvider instanceof CActiveDataProvider)
            	$this->renderKeys();

            echo CHtml::closeTag($this->tagName);
        }
    }
    
    /**
	 * Renderiza os filtros, colocando na primeira coluna os possíveis filtros extra
	 * @since 1.1.1
	 */
	public function renderFilter()
	{
        // Renderizador padrão
        parent::renderFilter();
        
        // Se não tem filtros extra, retorna
        if (!count($this->extraFilters)) {
            return;
        }
        
        // Se exibe filtros
		if ($this->filter !== null) {
            
			echo "<tr class=\"{$this->filterCssClass}\" style=\"display: none\">\n";
            
            echo '<td colspan="' . count($this->columns) . '">' . "\n";
            
            foreach ($this->extraFilters as $attribute) {
                        
                echo CHtml::activeHiddenField($this->dataProvider->model, $attribute) . "\n";
            }
            
            echo "</td>\n</tr>\n";
		}
	}
    
    /**
     * Converte uma expressão do GRID em uma string 
     * @return string
     */
    protected function evaluateColumnExpression($_expression_, $_data_ = array())
    {
        extract($_data_);
        return eval('return '.$_expression_.';');
    }
	
	/**
	 * Converte um nome de classe em um id separado por hifens
	 * 
	 * @see CCodeModel::class2id
	 * @param string $className
	 * @return string
	 */
	protected function class2id($className)
	{
		return trim(strtolower(str_replace('_','-',preg_replace('/(?<![A-Z])[A-Z]/', '-\0', $className))),'-');
	}
}
