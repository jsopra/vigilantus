<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout = 'application.views.layouts.main';
	
	public $menu=array();

	public $assetPath;
	
	public $breadcrumbs=array();
	
	public function init() {
		
		parent::init();

		$this->assetPath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.assets'));
		
		Yii::app()->clientScript->registerCoreScript('jquery');
	}
}