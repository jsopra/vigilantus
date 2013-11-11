<?php

Yii::import('system.gii.GiiModule');

/**
 * Sobrescreve o gerador padrão do Gii para corrigir
 * a falha com sessões (https://github.com/yiisoft/yii/issues/278)
 * e para implementar eventuais funcionalidades.
 */
class PGiiModule extends GiiModule
{
	/**
	 * Initializes the gii module.
	 */
	public function init()
	{
		// Aponta o Gii pro diretório correto
		Yii::setPathOfAlias('gii', Yii::getPathOfAlias('system.gii'));
		
		// Namespace do componente atual e do Giix
		$giixDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'giix';
		Yii::setPathOfAlias('fidelizext', dirname(__FILE__));
		Yii::setPathOfAlias('ext.giix-core', $giixDir . DIRECTORY_SEPARATOR . 'giix-core');
		Yii::setPathOfAlias('ext.giix-components', $giixDir . DIRECTORY_SEPARATOR . 'giix-components');
		
		// Chama o inicializador padrão
		parent::init();
		
		// Corrige o erro com classes de sessão personalizadas
		Yii::app()->setComponents(array(
			'session' => array (
                'class'=>'CHttpSession',
			),
		), false);
		
		// Aponta pras views e controllers do Gii padrão
		$this->setControllerPath(Yii::getPathOfAlias('gii.controllers'));
		$this->setViewPath(Yii::getPathOfAlias('gii.views'));
		
		// Adiciona os geradores próprios
		$this->generatorPaths[]='perspectivaext.gii';
		
		// Recarrega controllers
		$this->controllerMap=$this->findGenerators();
	}
}
