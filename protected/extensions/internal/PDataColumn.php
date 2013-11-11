<?php

Yii::import('zii.widgets.grid.CDataColumn');

class PDataColumn extends CDataColumn
{
	const FIELD_TEXT = 'text';
	const FIELD_DATE = 'date';
	const FIELD_CHECKBOX = 'checkbox';
	const FIELD_SELECT = 'select';
	const FIELD_RADIO = 'radio';

	/**
	 * Tipo de campo de formulário
	 * 
	 * Suporta: 
	 * 
	 *   text       <input type="text" />
	 *   checkbox   <input type="checkbox" />
	 * 
	 * Com o parâmetro "fieldData":
	 * 
	 *   radio      <input type="radio" />
	 *   select     <select></select>
	 * 
	 * @var string 
	 */
	public $fieldType = 'text';
	
	/**
	 * Dados para campos do tipo 'select' ou 'radio'.
	 * 
	 * @var array
	 */
	public $fieldData = array();
	
	/**
	 * Dados de atributos do campo HTML que serão setados por jQuery.
	 * Exemplo:
	 * 
	 * 'maxlength' => 60, 'size' => 10, 'onkeyup' => 'fazAlgo(this)'
	 * 
	 * @var array
	 */
	public $fieldHtmlOptions = array();
	
	/**
	 * Se necessita atualizar o select via requisição http
	 * @var string
	 */
	public $fieldRefreshDataUrl = '';

	/**
	 * Se usa algum plugin, como datepicker, colorpicker, permite alterar as configurações padrão.
	 * @var array
	 */
	public $fieldPluginOptions = array();
	
	/**
	 * Renders a data cell.
	 * @param integer $row the row number (zero-based)
	 */
	public function renderDataCell($row)
	{
		$data=$this->grid->dataProvider->data[$row];
		$options=$this->htmlOptions;
		if($this->cssClassExpression!==null)
		{
			$class=$this->evaluateExpression($this->cssClassExpression,array('row'=>$row,'data'=>$data));
			if(isset($options['class']))
				$options['class'].=' '.$class;
			else
				$options['class']=$class;
		}
		
		// Valor atual do campo
		if ($this->grid->isEditable && $this->name)
			$options['data-current-value'] = $this->evaluateExpression("\$data->" . $this->name, array('data'=>$data));
		
		echo CHtml::openTag('td',$options);
		$this->renderDataCellContent($row,$data);
		echo '</td>';
	}
}
