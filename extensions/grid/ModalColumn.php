<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\extensions\grid;

use Yii;
use Closure;
use yii\helpers\Html;
use yii\grid\Column;
use app\components\themes\DetailwrapAsset;
use yii\bootstrap\Modal;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ModalColumn extends Column
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
    public $linkTitle = '';
    public $customScript = null;

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
	public $format='raw';
	
	public $textBeforeLink;
	public $textAfterLink;
	
	public $filterHtmlOptions=array();
	
	public function init() {
		
		$this->_validate();
		
		parent::init();
		
        $view = Yii::$app->getView();
        
		if(!$this->onClick) :
            
            echo '
                <div class="modal fade" id="' . $this->modalId . '" tabindex="-1" role="dialog" aria-labelledby="Feedback" aria-hidden="true">
    
                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>' . ($this->modalHeader ? $this->modalHeader : ($this->header ? $this->header : null)) . '</h4>
                        </div>

                        <div class="modal-body"></div>

                        <div class="modal-footer">
                            <div class="pull-left">
                                <p class="modal-feedback-message"></p>
                            </div>
                            <div class="pull-right">
                                ' . Html::submitButton('Fechar', ['class' => 'btn btn-flat success submitFeedback', 'data-dismiss' => 'modal']) . '
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div> 
            </div>  
            ';
        
        
			$jqueryCall = $this->requestType == 'POST' ? 'jQuery.post' : ($this->requestIsJSON ? 'jQuery.getJSON' : 'jQuery.get');
			
			if($this->modalContent) {
				$scriptResult = "jQuery('#" . $this->modalId . "').children('.modal-dialog').children('.modal-content').children('.modal-body').html('" . $this->modalContent . "');";
			}
			else if($this->modalFunctionToProcessContent) {
				$scriptResult = $jqueryCall . "(jQuery(this).attr('ajax_url'), function(data) {
						jQuery('#" . $this->modalId . "').children('.modal-dialog').children('.modal-content').children('.modal-body').html(" . $this->modalFunctionToProcessContent . "(data));
					});
				";
			}
			else {

				$scriptResult = $jqueryCall . "(jQuery(this).attr('ajax_url'), function(data) {

						jQuery('#" . $this->modalId . "').children('.modal-dialog').children('.modal-content').children('.modal-body').html(data);

						" . $this->customScript . "
					});
				";
			}

			$script = "
			jQuery('.FModalColumn_" . $this->modalId . "').on(\"click\", function(e){
				e.preventDefault();
				
				if(jQuery(this).attr('data-modal-title') != '') {
					jQuery('#" . $this->modalId . "').children('.modal-dialog').children('.modal-content').children('.modal-header').children('h4').html(jQuery(this).attr('data-modal-title'));
				}
				
				jQuery('#" . $this->modalId . "').children('.modal-dialog').children('.modal-content').children('.modal-body').html('<span class=\"glyphicon glyphicon-refresh glyphicon-refresh-animate\"></span> Carregando...');

				" . $scriptResult . "

				jQuery('#" . $this->modalId . "').modal('show');
			});";

            
            $view->registerJs($script);
			
		endif;
	}
	
	protected function renderDataCellContent($model, $key, $index) {

        $columnContent = '';
        
        $hideLinkExpression = $this->hideLinkExpression ? call_user_func($this->hideLinkExpression, $model, $key, $index, $this) : null;
        
        if($this->iconClass && !$hideLinkExpression)
			$columnContent .= "<i class=\"{$this->iconClass}\"></i>&nbsp;";
        
		$columnContent .= call_user_func($this->value, $model, $key, $index, $this);
		
		$onClick = $this->onClick ? call_user_func($this->onClick, $model, $key, $index, $this) : null;

		$modalAjaxContent = $this->modalAjaxContent ? call_user_func($this->modalAjaxContent, $model, $key, $index, $this) : null;
		
        $finalHtml = '';
        
		if($this->textBeforeLink)
			$finalHtml .= call_user_func($this->textBeforeLink, $model, $key, $index, $this);
		
		if(!$hideLinkExpression)
			$finalHtml .= '<a href="javascript:void(0)" title="' . $this->linkTitle . '" data-modal-title="' . ($this->modalTitle ? call_user_func($this->modalTitle, $model, $key, $index, $this) : '') . '" ajax_url="' . ($modalAjaxContent ? $modalAjaxContent : null) . '" class="FModalColumn_' . $this->modalId . '" ' . ($this->tooltipText ? 'rel="tooltip" title="' . $this->tooltipText . '"' : '') . ($onClick ? 'onClick="' . $onClick . '"' : '') . '>' . $columnContent . '</a>';
		else
			$finalHtml .= $columnContent;
		
		if($this->textAfterLink)
			$finalHtml .= call_user_func($this->textAfterLink, $model, $key, $index, $this);
        
        return $finalHtml;
	}
	
	
	private function _validate() {
		
		if(!$this->value || (!$this->modalId && !$this->onClick)) 
			throw new Exception('System', 'Parâmetros insuficientes para classe', 500);
		
		if(!$this->modalContent && !$this->modalAjaxContent && !$this->onClick)
			throw new Exception('System', 'Parâmetros insuficientes para modal', 500);
		
		if($this->onClick && ($this->modalContent || $this->modalAjaxContent))
			throw new Exception('System', 'Você está definindo ação de clique e opções de modal ao mesmo tempo!', 500);
		
		if(!$this->modalAjaxContent && $this->modalFunctionToProcessContent)
			throw new Exception('System', 'Se você não está usando conteúdo via ajax, não deve definir uma função para processamento do retorno assíncrono', 500);
		
		if($this->modalContent && $this->modalAjaxContent)
			throw new Exception('System', 'Conteúdo deve ser pré-definido OU via ajax', 500);
		
		if($this->requestType != 'POST' && $this->requestType != 'GET')
			throw new Exception('System', 'Tipo de requisição não suportada', 500);
		
		if($this->requestIsJSON && !$this->modalFunctionToProcessContent)
			throw new Exception('Você deve definir qual função javascript vai tratar o JSON do ajax', 500);
		
		if($this->requestType == 'POST' && $this->requestIsJSON)
			throw new Exception('POST JSON não implementado', 500);
	}
}