<?php

Yii::import('zii.widgets.grid.CGridColumn');

class FModalColumn extends CGridColumn
{	
	public $filter;
	public $fieldType;
	public $modalId;
	public $name;
	public $iconClass;
	public $value;
	public $tooltipText = null;
	public $onClick;
	public $hideLinkExpression;
	public $sortable = true;
	public $modalTitle;
	public $modalHeader;
	public $modalContent;
	public $modalAjaxContent;
	public $modalFunctionToProcessContent;
	public $requestType = 'GET';
	public $requestIsJSON = false;

	/**
	 * @var boolean Se o campo será exportado ou não no CSV gerado
	 */
	public $exportable = true;

	/**
	 * @var string the type of the attribute value. This determines how the attribute value is formatted for display.
	 * Valid values include those recognizable by {@link CGridView::formatter}, such as: raw, text, ntext, html, date, time,
	 * datetime, boolean, number, email, image, url. For more details, please refer to {@link CFormatter}.
	 * Defaults to 'text' which means the attribute value will be HTML-encoded.
	 */
	public $type='raw';
	
	public $textBeforeLink;
	public $textAfterLink;
	
	public $filterHtmlOptions=array();
	
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
					'url'=>'javascript:void(0)',
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
			$('.FModalColumn_" . $this->modalId . "').live(\"click\", function(e){
				e.preventDefault();
				
				if($(this).attr('data-modal-title') != '')
					$('#" . $this->modalId . "').children('.modal-header').children('h4').html($(this).attr('data-modal-title'));
				
				$('#" . $this->modalId . "').children('.modal-body').html('');

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
			echo '<a href="javascript:void(0)" data-modal-title="' . ($this->modalTitle ? $this->evaluateExpression($this->modalTitle,array('row'=>$row,'data'=>$data)) : '') . '" ajax_url="' . ($modalAjaxContent ? $modalAjaxContent : null) . '" class="FModalColumn_' . $this->modalId . '" ' . ($this->tooltipText ? 'rel="tooltip" title="' . $this->tooltipText . '"' : '') . ($onClick ? 'onClick="' . $onClick . '"' : '') . '>' . $columnContent . '</a>';
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
	
	public function renderFilterCell() {

		echo CHtml::openTag('td',$this->filterHtmlOptions);
		
		if(is_string($this->filter))
			echo $this->filter;
		else if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strpos($this->name,'.')===false) {
			
			if(is_array($this->filter))
				echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id'=>false,'prompt'=>''));
			else if($this->filter===null)
				echo CHtml::activeTextField($this->grid->filter, $this->name, array('id'=>false));
		} else
			parent::renderFilterCellContent();
		
		echo '</td>';
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