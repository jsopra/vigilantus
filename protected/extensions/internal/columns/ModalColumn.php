<?php
/*
ModalColumn
--------------

Modos:
-------

- Modal delegado à aplicação: apenas define link, tooltip, icone do modal, delegando o processamento do modal à aplicação do programador;

- Modal com conteúdo ajax não tratado: todo o processo de um modal para um retorno ajax de algo que pode ser colado no modal (ex: um html ou texto);

- Modal com conteúdo ajax tratado: todo o processo de um modal para um retorno ajax de algum conteúdo que deve ser tratado por uma função js (ex: um json que na aplicação defino um layout);

- Modal com conteúdo estático: você quer a coluna do modal, mas já tem o conteúdo do modal "em mãos".

Parâmetros:
--------
$modalId: Identificador do modal - Obrigatório
$value: Valor da coluna da grid (um texto estático ou mesmo um $data->nome...) - Obrigatório
$name: Nome do atributo da coluna para o modelo da grid
$tooltipText: quer um tooltip no link? Passe o texto aqui
$iconClass: quer um ícone na coluna? Passe o nome da classe css aqui (será associada à um <i></i>)
$hideLinkExpression: o modal deve ser exibido somente para alguns casos? Passe a condição aqui (ex: 1 == $data->id)
$sortable: ordenar? booleano
$modalHeader: nome que aparecerá no cabeçalho do modal
$modalContent: conteúdo estático do modal
$modalAjaxContent: url que o modal vai ter de buscar a informação a mostrar
$modalFunctionToProcessContent: o resultado do modal terá de ser tratado? passe aqui o nome da função que receberá "data" e fará o ajuste, devolvendo o html pronto (ex: "JsonToLi")
$requestIsJSON: define se resultado do ajax é JSON (padrão é falso). Se true, então há de passar $modalFunctionToProcessContent
$requestType: POST ou GET, com default GET
[Demais parâmetros do zii.widgets.grid.CGridColumn]


Exemplos:

Modal delegado à aplicação:
----------------------------
$this->widget('ext.fidelize.FGridView', array(
	'columns'=>array(
		array(
			'modalId' => 'id4',
			'header' => Yii::t('Processo','modal delegado à aplicação'),
			'class' => 'ModalColumn',
			'iconClass' => 'icon-comment opacity50',
			'value' => '"Detalhar"',
			'tooltipText' => 'Ver histórico do Processo',
			'htmlOptions' => array('style' => 'width: 100px;'),
			'onClick' => '"exibeHistorico({$data->id}, false);"',
		),
	),
);

Modal com conteúdo ajax não tratado:
----------------------------
$this->widget('ext.fidelize.FGridView', array(
	'columns'=>array(
		array(
			'modalId' => 'id3',
			'header' => Yii::t('Processo','modal com conteudo ajax nao tratado'),
			'class' => 'ModalColumn',
			'iconClass' => 'icon-comment opacity50',
			'value' => '"Detalhar"',
			'tooltipText' => 'Ver histórico do Processo',
			'htmlOptions' => array('style' => 'width: 100px;'),
			'modalHeader' => 'Histórico de Processo',
			'modalAjaxContent' => 'Yii::app()->createUrl("processo/historico", array("id" => $data->id))',
			'requestIsJSON' => true,
		),
	),
);

Modal com conteúdo ajax tratado:
----------------------------
$this->widget('ext.fidelize.FGridView', array(
	'columns'=>array(
		array(
			'modalId' => 'id1',
			'header' => Yii::t('Processo','modal com conteudo ajax tratado'),
			'class' => 'ModalColumn',
			'iconClass' => 'icon-comment opacity50',
			'value' => '"Detalhar"',
			'tooltipText' => 'Ver histórico do Processo',
			'htmlOptions' => array('style' => 'width: 100px;'),
			'modalHeader' => 'Histórico de Processo',
			'modalAjaxContent' => 'Yii::app()->createUrl("processo/historico", array("id" => $data->id))',
			'modalFunctionToProcessContent' => 'processaItensHistorico',
			'requestIsJSON' => true,
		),
	),
);

Modal com conteúdo estático:
----------------------------
$this->widget('ext.fidelize.FGridView', array(
	'columns'=>array(
		array(
			'modalId' => 'id2',
			'header' => Yii::t('Processo','modal com conteudo fixo'),
			'class' => 'ModalColumn',
			'iconClass' => 'icon-comment opacity50',
			'value' => '"Detalhar"',
			'tooltipText' => 'Ver histórico do Processo',
			'htmlOptions' => array('style' => 'width: 100px;'),
			'modalHeader' => 'Histórico de Processo',
			'modalContent' => 'teste', // ou mesmo um render partial... 
		),
	),
);
*/
		
Yii::import('zii.widgets.grid.CGridColumn');

class ModalColumn extends CGridColumn {
	
	public $modalId;
	public $name;
	public $iconClass;
	public $value;
	public $tooltipText = null;
	public $onClick;
	public $hideLinkExpression;
	public $sortable = true;
	public $modalHeader;
	public $modalContent;
	public $modalAjaxContent;
	public $modalFunctionToProcessContent;
	public $requestType = 'GET';
	public $requestIsJSON = false;
	
	public $textBeforeLink;
	public $textAfterLink;
	
	public function init() {
		
		$this->_validate();
		
		parent::init();
		
		$cs=Yii::app()->getClientScript();
		$gridId = $this->grid->getId() . '_' . $this->modalId;
		
		if(!$this->onClick) :
			Yii::app()->controller->beginWidget('bootstrap.widgets.TbModal', array('id'=> $this->modalId)); 
		?>
 
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4><?php echo $this->modalHeader ? $this->modalHeader : ($this->header ? $this->header : null); ?></h4>
			</div>

			<div class="modal-body"></div>

			<div class="modal-footer">
				<?php Yii::app()->controller->widget('bootstrap.widgets.TbButton', array(
					'label'=>'Fechar',
					'url'=>'#',
					'htmlOptions'=>array('data-dismiss'=>'modal'),
				)); ?>
			</div>

			<?php 
			Yii::app()->controller->endWidget();
			
			$jqueryCall = $this->requestType == 'POST' ? '$.post' : ($this->requestIsJSON ? '$.getJSON' : '$.get');
			
			if($this->modalContent) {
				$scriptResult = "$('#" . $this->modalId . "').children('.modal-body').html('" . $this->modalContent . "');";
			}
			else if($this->modalFunctionToProcessContent) {
				$scriptResult = $jqueryCall . "($(this).attr('ajax_url'), function(data) {
						$('#" . $this->modalId . "').children('.modal-body').html(" . $this->modalFunctionToProcessContent . "(data));
					});
				";
			}
			else {

				$scriptResult = $jqueryCall . "($(this).attr('ajax_url'), function(data) {
						$('#" . $this->modalId . "').children('.modal-body').html(data);
					});
				";
			}

			$script = "
			$('.modalColumn_" . $this->modalId . "').live(\"click\", function(e){
				e.preventDefault();

				" . $scriptResult . "

				$('#" . $this->modalId . "').modal('show');
			});";

			$cs->registerScript((__CLASS__.$gridId.'#' . $this->modalId),$script);
			
		endif;
	}
	
	protected function renderDataCellContent($row, $data) {

		$columnData = $this->value ? $this->evaluateExpression($this->value,array('row'=>$row,'data'=>$data)) : $data->{$this->name};
		$columnContent = $columnData;
		
		$hideLinkExpression = $this->hideLinkExpression ? $this->evaluateExpression($this->hideLinkExpression,array('row'=>$row,'data'=>$data)) : null;
		
		if($this->iconClass && $columnData && !$hideLinkExpression)
			$columnContent .= "&nbsp;&nbsp;<i class=\"{$this->iconClass}\"></i>";
				
		$onClick = $this->onClick ? $this->evaluateExpression($this->onClick,array('row'=>$row,'data'=>$data)) : null;
		
		$modalAjaxContent = $this->modalAjaxContent ? $this->evaluateExpression($this->modalAjaxContent,array('row'=>$row,'data'=>$data)) : null;
		
		if($this->textBeforeLink)
			echo $this->evaluateExpression($this->textBeforeLink,array('row'=>$row,'data'=>$data));
		
		if(!$hideLinkExpression)
			echo '<a href="#" ajax_url="' . ($modalAjaxContent ? $modalAjaxContent : null) . '" class="modalColumn_' . $this->modalId . '" ' . ($this->tooltipText ? 'rel="tooltip" title="' . $this->tooltipText . '"' : '') . ($onClick ? 'onClick="' . $onClick . '"' : '') . '>' . $columnContent . '</a>';
		else
			echo $columnContent;
		
		if($this->textAfterLink)
			echo $this->evaluateExpression($this->textAfterLink,array('row'=>$row,'data'=>$data));
	}
	
	protected function renderHeaderCellContent() {
		
		if($this->grid->enableSorting && $this->sortable && $this->name!==null)
			echo $this->grid->dataProvider->getSort()->link($this->name,$this->header);
		
		else if($this->name!==null && $this->header===null) {
			
			if($this->grid->dataProvider instanceof CActiveDataProvider)
				echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
			else
				echo CHtml::encode($this->name);
		}
		else
			parent::renderHeaderCellContent();
	}

	
	private function _validate() {
		
		if(!$this->value || (!$this->modalId && !$this->onClick)) {
			throw new Exception(Yii::t('System', 'Parâmetros insuficientes para classe'), 500);
		}
		
		if(!$this->modalContent && !$this->modalAjaxContent && !$this->onClick) {
			throw new Exception(Yii::t('System', 'Parâmetros insuficientes para modal'), 500);
		}
		
		if($this->onClick && ($this->modalContent || $this->modalAjaxContent)) {
			throw new Exception(Yii::t('System', 'Você está definindo ação de clique e opções de modal ao mesmo tempo!'), 500);
		}
		
		if(!$this->modalAjaxContent && $this->modalFunctionToProcessContent) {
			throw new Exception(Yii::t('System', 'Se você não está usando conteúdo via ajax, não deve definir uma função para processamento do retorno assíncrono'), 500);
		}
		
		if($this->modalContent && $this->modalAjaxContent) {
			throw new Exception(Yii::t('System', 'Conteúdo deve ser pré-definido OU via ajax'), 500);
		}
		
		if($this->requestType != 'POST' && $this->requestType != 'GET') {
			throw new Exception(Yii::t('System', 'Tipo de requisição não suportada'), 500);
		}
		
		if($this->requestIsJSON && !$this->modalFunctionToProcessContent) {
			throw new Exception(Yii::t('System', 'Você deve definir qual função javascript vai tratar o JSON do ajax'), 500);
		}
		
		if($this->requestType == 'POST' && $this->requestIsJSON) {
			throw new Exception(Yii::t('System', 'POST JSON não implementado'), 500);
		}
	}
}