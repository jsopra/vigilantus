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
	 * Se permite que relat?rios com muito dados sejam exportados em um job
	 * rodando em background.
	 * @var boolean
	 */
	public $allowBackgroundExport = false;

	/**
	 * Se ser? feito debug do grid ou n?o (inclusive do JavaScript)
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * Classe da tabela
	 * @var string
	 */
	public $itemsCssClass = 'tabela-style';
	
	/**
	 * Indica se este grid possui um bot?o que permite exportar seus dados
	 * @var boolean
	 */
	public $isExportable = false;
	
	/**
	 * Indica se ? para fixar os headers e footers
	 * @var boolean
	 */
	public $isFixed = false;
	
	/**
	 * Indica se este grid possui edi??o inline (na tabela)
	 * @var boolean 
	 */
	public $isEditable = false;
	
    /**
     * Nome da vari?vel GET pra indicar que est? exportando
     * @var string 
     */
    public $exportFlagName = 'export';

    /**
     * Nome da vari?vel GET que armazena o endere?o de e-mail de quem receber?
     * a vers?o CSV deste grid (caso haja muitas linhas para processar).
     * @var string 
     */
    public $csvReceiverEmailFlagName = '_emailToSendGridCsv';

    /**
     * E-mail do destinat?rio no envio do relat?rio por e-mail, caso seja
     * necess?rio, que vir? preenchido por padr?o (ex: email do usuario logado).
     * @var string 
     */
    public $csvReceiverEmail;

    /**
     * Array de rodapes da tabela.
     * 
     * @var array 
     */
    public $footers;
    
    
    
    /**
     * Nome do arquivo exportado. Ser? dado append no AnoMesDia.
     * @var string 
     */
    public $exportFileName = 'export';
    
    /**
     * Label do bot?o que exporta
     * @var string 
     */
    public $exportButtonLabel = 'Exportar Planilha';
    
    /**
     * Label do bot?o que limpa o filtro
     * @var string 
     */
    public $clearFilterButtonLabel = 'Limpar Filtro';
    
    /**
     * Par?metro para limpar filtros salvos na sess?o
     * @var string 
     */
    public $clearSessionFilterUrlParam = 'limpa_sessao';
    
    /**
     * Ativa ou desativa o bot?o de limpar o filtro
     * @var boolean 
     */
    public $clearFilterButtonEnabled;
    
    /**
     * Ativa ou desativa o bot?o de cadastrar
     * @var boolean 
     */
    public $createButtonEnabled;
    
    /**
     * Ativa ou desativa o bot?o de excluir v?rios registros
     * @var boolean 
     */
    public $deleteManyButtonEnabled;
    
    /**
     * Quantidade de registros que carregar? por consulta (para n?o estourar a mem?ria)
     * @var integer 
     */
    public $recordsLoadingStep = 1000;
	
	/**
	 * Mapeamento de posi??o das c?lulas e atributos de um grid com edi??o inline
	 * 
	 * Por exemplo: c?lula 0 ter? o campo do atributo "nome_completo", etc.
	 * 
	 * Por padr?o pegar? todas as colunas do grid que tenham definida a propriedade "name" que referencia o modelo.
	 * 
	 * @var array 
	 */
	public $modelAttributesMapping = null;
	
	/**
	 * Nome do modelo da edi??o inline. Por padr?o tenta encontrar o modelo do dataProvider
	 * 
	 * @var string 
	 */
	public $modelName = null;
	
	/**
	 * Express?o PHP para a URL onde ser?o postados os dados da edi??o, e que
	 * dever? retornar um JSON com erros de valida??o e o status, no seguinte formato:
	 * 
	 * Sucesso: {saved: true}
	 * Erro:    {saved: false, errors: { ...erros... } }
	 * 
	 * @var string 
	 */
	public $ajaxUpdateUrlExpression = null;
	
	/**
	 * URL onde ser?o postados os dados da inser??o, e que dever? retornar 
	 * um JSON com erros de valida??o e o status, no seguinte formato:
	 * 
	 * Sucesso: {saved: true}
	 * Erro:    {saved: false, errors: { ...erros... } }
	 * 
	 * @var string 
	 */
	public $ajaxCreateUrl = null;
	
	/**
	 * Se permite mostrar um bot?o para excluir v?rias linhas ao mesmo tempo.
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
	 * STRING  = URL da action de exclus?o
	 * 
	 * @var null|boolean|string 
	 */
	public $deleteUrl = null;
	
	/**
	 * Nome do bot?o de exclus?o.
	 * @var string 
	 */
	public $deleteLabel = 'Excluir';
	
	/**
	 * Se permite mostrar um bot?o para cadastrar um novo modelo.
	 * 
	 * NULL    = Detecta a URL correta automaticamente (modelo/create)
	 * FALSE   = Desativa a funcionalidade
	 * STRING  = URL da action de cadastro
	 * 
	 * @var null|boolean|string 
	 */
	public $createUrl = null;
	
	/**
	 * Nome do bot?o de cadastro.
	 * @var string 
	 */
	public $createLabel = 'Cadastrar';

	/**
	 * M?ximo de linhas que consegue exportar para CSV imediatamente.
	 * Qualquer n?mero acima destes e o relat?rio ser? processado em background
	 * e enviado por e-mail para quem o solicitou.
	 * @var integer
	 */
	public $maxRowsToExportInstantly = 10000;

	/**
	 * M?ximo de linhas que consegue exportar para CSV de qualquer forma.
	 * Qualquer n?mero acima destes e o relat?rio n?o ser? processado
	 * @var integer
	 */
	public $maxRowsToExportBackground = 200000;

	/**
     * Filtros que n?o vieram do CGridView e precisam ser renderizados
     * @var array 
     */    
    protected $extraFilters = array();

    /**
     * @var PGridCsvExporter
     */
    protected $_exporter;
	
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

		// Default de bot?o de limpar filtro
		if ($this->isEditable && $this->clearFilterButtonEnabled === null) {
			$this->clearFilterButtonEnabled = true;
		}

		// Se for um dataprovider com modelo
		$refersToModel = isset($this->dataProvider->model);

		if ($refersToModel) {

			// Default dos bot?es de cadastro/excluir v?rios
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
			
			// Se n?o foi definido o nome do modelo
			if ($this->modelName === null) {

				$this->modelName = get_class($this->dataProvider->model);
			}
				
			$module = Yii::app()->controller->module ? Yii::app()->controller->module->id : null;
			$controller = Yii::app()->controller ? Yii::app()->controller->id : null;
			$thisUrl = $module ? $module . '/' . $controller : $controller;
			
			// Se for um grid com edi??o inline
			if ($this->isEditable) {
				
				// Se n?o foram definidos os atributos do modelo carrega os "name" das colunas
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
				
				// Se n?o foi definido o nome do par?metro "ajax"
				if ($this->ajaxUpdateUrlExpression === null) {
					
					$this->ajaxUpdateUrlExpression = 'Yii::app()->createUrl("' . $thisUrl . '/ajaxSave", array("id" => $data->id))'; 
				}
				
				// Se n?o foi definida a URL para cria??o de novos usu?rios
				if ($this->ajaxCreateUrl === null) {
					$this->ajaxCreateUrl = Yii::app()->createUrl($thisUrl . '/ajaxSave');
				}
			}
			
			// Se precisa detectar automaticamente estas URLs (false = n?o usado)
			if ($this->createButtonEnabled && $this->createUrl === null) {
				$this->createUrl = Yii::app()->createUrl($thisUrl . '/create');
			}
			
			if ($this->deleteManyButtonEnabled !== false) {

				// URL padr?o de exclus?o em massa
				if ($this->deleteUrl === null) {
					$this->deleteUrl = Yii::app()->createUrl($thisUrl . '/ajaxDelete');
				}
				
				$hasCheckboxColumn = false;
				
				foreach ($this->columns as $column) {
					
					if ($column instanceof FCheckBoxColumn && $column->selectableRows == 2) {
						$hasCheckboxColumn = true;
						break;
					}
				}
				
				// Se n?o houver uma coluna com os checkboxes, desativa a exclus?o em massa
				if (!$hasCheckboxColumn) {
					$this->deleteManyButtonEnabled = false;
				}
			}
		}
		// Se n?o se refere a um modelo
		else {

			// Default dos bot?es de cadastro/excluir v?rios
			if ($this->deleteManyButtonEnabled === null)
				$this->deleteManyButtonEnabled = false;

			if ($this->createButtonEnabled === null)
				$this->createButtonEnabled = false;

			// N?o foi definido o nome do modelo? Ent?o... 
			if ($this->modelName === null) {
				
				// ... n?o consegue adivinhar a URL de cria??o
				if ($this->createButtonEnabled && $this->createUrl === null)
					throw new Exception(Yii::t(
						'Fidelize', 
						'Informe o nome do modelo ("modelName") para detectar a URL de cadastro ou a informe explicitamente ("createUrl").'
					));
				
				// ... n?o consegue adivinhar a URL de exclus?o em massa
				if ($this->deleteManyButtonEnabled && $this->deleteUrl === null)
					throw new Exception(Yii::t(
						'Fidelize', 
						'Informe o nome do modelo ("modelName") para detectar a URL de exclus?o em massa ou a informe explicitamente ("deleteUrl").'
					));
				
				// ... se for um grid com edi??o inline...
				if ($this->isEditable) {
					
					// ... n?o consegue adivinhar a URL de cadastro inline
					if ($this->ajaxCreateUrl === null)
						throw new Exception(Yii::t(
							'Fidelize', 
							'Informe o nome do modelo ("modelName") para detectar a URL do AJAX do cadastro inline ou a informe explicitamente ("ajaxCreateUrl").'
						));

					// ... n?o consegue adivinhar a URL de atualiza??o inline
					if ($this->ajaxUpdateUrlExpression === null)
						throw new Exception(Yii::t(
							'Fidelize', 
							'Informe o nome do modelo ("modelName") para detectar a URL do AJAX de atualiza??o inline ou a informe explicitamente ("ajaxUpdateUrlExpression").'
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
		
		// Coloca a fun??o de recarregar o PGridView no CGridView
		$userDefinedFunction = $this->afterAjaxUpdate;

		if ($userDefinedFunction != null) {

			$userDefinedFunction = ' var userDefinedFunction = ' . $userDefinedFunction . '; '
			                     . 'userDefinedFunction(gridId, ajaxResponseData); '
			;
		}

		// N?o podemos deixar sobrescrever o init()
		$this->afterAjaxUpdate = 'function(gridId, ajaxResponseData){ window.loadedPGridView[gridId].init(); ' . $userDefinedFunction . ' }';

		// Fun??o de erro padr?o
		if ($this->ajaxUpdateError == null) {

			$this->ajaxUpdateError = 'function(xhr,textStatus,errorThrown,errorMessage){ perspectivaGrid.message(xhr.responseText, "error"); }';
		}

		// Se estiver usando bootstrap
		if ($this->isUsingBoostrap()) {
			$this->pagerCssClass = 'pagination';
			$this->pager = array('class' => 'bootstrap.widgets.TbPager');
		}
	}
	
	/**
	 * @return boolean Se est? usando a extens?o do twitter bootstrap
	 */
	protected function isUsingBoostrap()
	{
		return YiiBase::getPathOfAlias('bootstrap') !== false;
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
             
                $this->columns[$k]['viewButtonOptions'] = array(
                    'class'      => 'view icon-view',
                    'rel'        => 'tooltip',
                    'data-title' => 'Exibir',
                );
                $this->columns[$k]['updateButtonOptions'] = array(
                    'class'      => 'update icon-pencil',
                    'rel'        => 'tooltip',
                    'data-title' => 'Atualizar',
                );
                $this->columns[$k]['deleteButtonOptions'] = array(
                    'class'      => 'delete icon-trash',
                    'rel'        => 'tooltip',
                    'data-title' => 'Deletar'
                );
               
                if (!isset($this->columns[$k]['afterDelete']))
					$this->columns[$k]['afterDelete'] = 'perspectivaGrid.afterDelete';
				
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
	 * Renders a table footer.
	 */
	public function renderTableFidelizeFooter()
	{
        if (empty($this->footers)) {
            return true;
        }
		echo "<tfoot>\n";
        echo "<tr>\n";
        foreach ($this->footers as $footer) {
            echo "<td>".$footer."</td>";
        }
        echo "</tr>\n";
		echo "</tfoot>\n";
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
		
		// Linha para adi??o de campos
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
			
			echo '<td class="button-column"><a href="' . $this->ajaxCreateUrl . '" class="add tbl_icon incluir" title="Adicionar um novo registro">Adicionar</a></td>';
				
			echo "</tr>\n";;
		}
		
		echo "</tbody>\n";
        $this->renderTableFidelizeFooter();
        
	}
    
    /**
     * Renderiza o bot?o que limpa os filtros 
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
        
        // Para casos onde usa filtro na sess?o
        $params[$this->clearSessionFilterUrlParam] = 1;
        
        $url = Yii::app()->createUrl($request, $params);
        
        echo 
            '<input class="btn button-clear" type="button" value="',
            $this->clearFilterButtonLabel,
            '" onclick="window.location=\'',
            $url,
            '\'" />'
        ;
    }
    
    /**
	 * Renders the data items for the grid view.
     * @return string
	 */
	public function renderItems()
	{		
		// Se tem bot?es
		if ($this->isExportable || $this->createUrl !== false || $this->deleteUrl !== false) {
			
			echo '<div class="grid-buttons">', "\n";
			
			// Tem a funcionalidade de exportar ativa?
			if ($this->isExportable) {

				// Bot?o exportar
				echo '
				<div class="grid-export-button"><input class="btn button-export" type="button" id="'
				. $this->getId() . '-export-btn" value="'. $this->exportButtonLabel . '" />
				';

				if ($this->filter && $this->clearFilterButtonEnabled) {

					$this->renderClearFilterButton();
				}

				echo "</div>\n";

				Yii::app()->clientScript->registerCoreScript('jquery.ui');

				$dialogId = $this->id . '-dialog';
				$dialogOptions = CJavaScript::encode(array(
		            'modal' => true,
		            'width' => 400,
		            'autoOpen' => false,
		            'buttons' => array(
		            	'Preparar Planilha' => 'js:function(){void(0);}',
		            	'Cancelar'          => 'js:function(){jQuery(this).dialog("close")}'
		            )
				));

				if (Yii::app()->request && !Yii::app()->request->isAjaxRequest) {
					echo '
					<div id="' . $dialogId . '" style="display: none;">
					<p>
						Devido ao grande volume de dados, a planilha ser?
						preparada em segundo plano e um link para acess?-la
						ser? enviado por e-mail em alguns minutos.
					</p>
					<p>
						<strong>Digite seu e-mail: </strong>
						<input type="email" value="' . $this->csvReceiverEmail . '" />
					</p>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#' . $dialogId . '").dialog(' . $dialogOptions . ');
					});
					</script>
					';
				}
			}

			// Se tem o bot?o de cadastrar ou excluir
			if ($this->createButtonEnabled || $this->deleteManyButtonEnabled) {
				
				echo '<div class="grid-crud-buttons">';
				
				// Bot?o de excluir?
				if ($this->deleteManyButtonEnabled) {
					
					echo '<input type="button" class="btn delete-many" data-url="',
						$this->deleteUrl, '" value="', $this->deleteLabel, '" />'
					;
				}
				
				// Bot?o de cadastrar?
				if ($this->createButtonEnabled) {
					
					echo '<a class="link button-create" href="',
						$this->createUrl, '">', $this->createLabel, '</a>'
					;
				}
				
				echo '</div>';
			}
			
			echo '</div>';
		}
        
        parent::renderItems();
    }

    /**
	 * Registra os scripts necess?rios
	 */
	public function registerClientScript()
	{
		// Publica assets
		$assetsUrl = Yii::app()->getAssetManager()->publish(
			Yii::getPathOfAlias('ext.internal.assets'), // deve apontar para o diret?rio atual
			false, // FALSE $hashByName
			-1, //$level=-1
			YII_DEBUG //$forceCopy=NULL
		);

		// Registra assets e jQuery
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/pgridview.js');
		Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/jquery.toastmessage.js');
        if ($this->isFixed) {
            Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/jquery.tablescroll.js');
        }
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/main.css');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/pgridview.css');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/jquery.toastmessage.css');
		
		$options = array(
			'isUsingBoostrap'          => $this->isUsingBoostrap(),
			'isEditable'               => $this->isEditable,
			'isExportable'             => $this->isExportable,
            'isFixed'                  => $this->isFixed,
			'isStreamed'               => $this->isStreamed(),
			'hasClearFilter'           => $this->clearFilterButtonEnabled,
			'modelAttributesMapping'   => $this->modelAttributesMapping,
			'modelName'                => $this->modelName,
			'blankDisplay'             => $this->blankDisplay,
			'charset'                  => Yii::app()->charset,
			'debug'                    => ($this->debug ? true : false),
            'footers'                  => $this->footers,

		);

		// Tem a funcionalidade de exportar ativa?
		if ($this->isExportable) {

			// Configura??es passadas pro JS
			$options['exportFlagName']           = $this->exportFlagName;
			$options['csvReceiverEmailFlagName'] = $this->csvReceiverEmailFlagName;
		}

		// Configura??es de linhas edit?veis
		if ($this->isEditable) {

			$options['modelFormFieldsMapping'] = array();

			// Pega o esquema da tabela para detectar informa??es adicionais
			$tableSchema = (isset($this->dataProvider->model) ? $this->dataProvider->model->getTableSchema() : null);

			foreach ($this->columns as $index => $column) {

				if ($column instanceof PDataColumn && $column->name) {

					$tableColumn = null;
					if($tableSchema && isset($tableSchema->columns[$column->name]))
						$tableColumn = $tableSchema->columns[$column->name];

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

					// Seta configura??es adicionais baseadas no banco de dados
					if ($column->fieldType == 'text' && $tableColumn) {

						// Maxlength
						if (!isset($fieldHtmlOptions['maxlength']) && $tableColumn->type == 'string' && $tableColumn->size) {
							$fieldHtmlOptions['maxlength'] = $tableColumn->size;
						}
					}

					// Configura??es padr?o para um campo do tipo Date
					if ($column->fieldType == 'date') {

						// Inclui os assets necess?rios
						$jQueryUiCssFile = Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css';
						
						Yii::app()->clientScript->registerCoreScript('jquery.ui');
						Yii::app()->clientScript->registerCssFile($jQueryUiCssFile);

						$pluginOptions = array(
							'uiLanguage' => Yii::app()->language,
							'showMonthAfterYear' => false,
							'dateFormat' => FidelizeDate::getDateFormat(),
							'changeMonth' => true,
							'changeYear' => true,
							'showButtonPanel' => true,
							'constrainInput' => true,
							'showAnim' => 'slide',
							'duration' => 'fast',
						);

						// Mescla configura??es padr?o com as do usu?rio
						$pluginOptions = array_merge($pluginOptions, $column->fieldPluginOptions);

						// Converte formato de data do PHP pro JS
						$pluginOptions['dateFormat'] = preg_replace('/(y(?=y))?(d(?=dd))?(m(?=mm))?/', '', strtolower($pluginOptions['dateFormat']));

						// Corrige c?digo do idioma que ? diferente no JS
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
				elseif (($column instanceof FModalColumn) && $column->name) {
					$options['modelFormFieldsMapping'][$index] = array(
						'type'           => $column->fieldType,
						'data'           => null,
						'htmlOptions'    => null,
						'pluginOptions'  => null,
						'refreshDataUrl' => null,
					);
				}
			}
		}

		// Instancia os scripts do GRID
		$idScript = 'PGridView#' . $this->getId();
		$script = 'jQuery("#' . $this->getId() . '").perspectivaGrid(' . CJSON::encode($options) . ');'."\n";

		Yii::app()->clientScript->registerScript($idScript, $script); //, CClientScript::POS_READY);

		parent::registerClientScript();
	}
    
    /**
	 * @return boolean
	 */
	public function isStreamed()
	{
		$naoExtrapolouMaximoRegistrosStream = $this->dataProvider->getTotalItemCount() <= $this->maxRowsToExportInstantly;
		$naoExportaEmBackground = ! $this->allowBackgroundExport;

		return ($naoExportaEmBackground || $naoExtrapolouMaximoRegistrosStream);
	}

	/**
	 * Valida se o tamanho do array a ser exportado ? grande demais
	 * @return boolean
	 */
	public function acimaDoLimiteDeExportacao()
	{
		return $this->dataProvider->getTotalItemCount() > $this->maxRowsToExportBackground;
	}
    
    /**
	 * @return PGridCsvExporter
	 */
	public function getExporter()
	{
		if ($this->_exporter === null) {

			Yii::import('ext.internal.PGridCsvExporter');

			$email = null;

			if (isset($_GET[$this->csvReceiverEmailFlagName])) {
				$email = $_GET[$this->csvReceiverEmailFlagName];
			}

			$exporter = new PGridCsvExporter;

			$exporter->grid = $this;
			$exporter->receiverEmail = $email;

			$this->_exporter = $exporter;
		}

		return $this->_exporter;
	}
    
    /**
     * Verifica se printa a tabela ou retorna a planilha CSV
     * @return void
     */
    public function run()
    {
    	// Se est? exportando
		if (false == empty($_GET[$this->exportFlagName])) {

			if ($this->acimaDoLimiteDeExportacao()) {
				throw new Exception(
					'Tamanho da exportação grande demais (acima de '
					. $this->maxRowsToExportBackground . ' registros). Por favor filtre as informa??es.'
				);
			}

			if ($this->isStreamed()) {
	            return $this->getExporter()->stream();
	        }
	        else {

	        	// Checagem de requisitos, para evitar problemas
	        	if (!Yii::app()->mail || !class_exists('YiiMailMessage')) {
					throw new Exception('Configure o componente "mail" do Yii com a extens?o Yii-Mail!');
				}

				if (!extension_loaded('gearman')) {
		            throw new Exception('A extensão "gearman" do PHP n?o foi instalada!');
		        }

				if (!Yii::app()->gearman) {
		            throw new Exception('A extensão "gearman" do Yii n?o foi instalada!');
		        }

	            return $this->getExporter()->registerJob();
	        }
        }

        $this->registerClientScript();

        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        
        // Se houver filtro
        if ($this->filter != null && isset($_GET[get_class($this->dataProvider->model)])) {
            
            // Colunas que se referem a atributos
            $attributesNames = array();

            foreach ($this->columns as $column) {

                if (!in_array(get_class($column), array('CGridColumn', 'CDataColumn', 'PDataColumn', 'PModalColumn'))) {
                    continue;
                }

                if ($column->name) {
                    $attributesNames[] = $column->name;
                }
            }

            // Para cada atributo n?o nulo do modelo, ele tenta aplicar qualquer poss?vel filtro
            $extraFilters = array();
            
            $class = $this->dataProvider->modelClass;
            
            $this->dataProvider->model->setScenario('search');
        
            $this->dataProvider->model->attributes = $_GET[$class];
            
            // Pega nome dos atributos reais
            $classAttributes = array_keys($this->dataProvider->model->attributeNames()) + get_class_vars($class);

            foreach ($_GET[$class] as $attribute => $value) {

                // Se tiver valor, n?o for array, e n?o estiver nas colunas do grid (e for um atributo v?lido, claro)
                if ($value && !is_array($value) && !in_array($attribute, $attributesNames) && 
                    (property_exists($class, $attribute) || in_array($attribute, $classAttributes) )) {

                    $extraFilters[] = $attribute;
                }
            }
            
            // Seta para que o renderizador pegue adiante
            $this->extraFilters = $extraFilters;
        }
        
        echo CHtml::hiddenField('url-' . $this->getId(), $_SERVER['REQUEST_URI']) . "\n";
        echo CHtml::hiddenField('is-streamed-' . $this->getId(), $this->isStreamed() ? 1 : 0) . "\n";
        echo CHtml::hiddenField('rows-total-' . $this->getId(), $this->dataProvider->getTotalItemCount()) . "\n";
        echo CHtml::hiddenField('rows-limit-' . $this->getId(), $this->maxRowsToExportBackground) . "\n";

        $this->renderContent();

        if ($this->dataProvider && $this->dataProvider instanceof CDataProvider)
        	$this->renderKeys();

        echo CHtml::closeTag($this->tagName);
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
