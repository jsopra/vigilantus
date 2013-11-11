<?php

Yii::import('zii.widgets.grid.CCheckBoxColumn');

/**
 * Classe para demarcar as linhas
 */

class PCheckBoxColumn extends CCheckBoxColumn
{
	/** FIXME remover atributo quando o Yii for atualizado */
	public $disabled;

	/** FIXME remover método quando o Yii for atualizado */
	protected function renderDataCellContent($row, $data)
	{
		if ($this->disabled !== null) {

			if (!is_array($this->checkBoxHtmlOptions)) {
				$this->checkBoxHtmlOptions = array();
			}

			$this->checkBoxHtmlOptions['disabled'] = $this->evaluateExpression(
				$this->disabled, 
				array('data' => $data, 'row' => $row)
			);
		}

		return parent::renderDataCellContent($row, $data);
	}
}
