<?php

Yii::import('system.gii.generators.crud.CrudCode');

class PerspectivaCrudCode extends CrudCode
{
	public $testFunctionalPath = 'application.tests.functional';
	
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'model'=>'Classe do Modelo',
			'controller'=>'ID do Controller',
			'baseControllerClass'=>'Classe Base do Controller',
		));
	}
	
	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;
		$controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller.php';

		$this->files[]=new CCodeFile(
			$this->controllerFile,
			$this->render($controllerTemplateFile)
		);

		$files=scandir($templatePath);
		foreach($files as $file)
		{
			if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='controller.php' && $file !== 'functional.php')
			{
				$this->files[]=new CCodeFile(
					$this->viewPath.DIRECTORY_SEPARATOR.$file,
					$this->render($templatePath.'/'.$file)
				);
			}
			else if ($file == 'functional.php') {
				$this->files[]=new CCodeFile(
					Yii::getPathOfAlias($this->testFunctionalPath).'/'.$this->getModelClass().'Test.php',
					$this->render($templatePath.'/'.$file)
				);
			}
		}
	}
	
	public function generateActiveRow($modelClass, $column)
	{
		if ($column->type === 'boolean')
			return "\$form->checkBoxRow(\$model,'{$column->name}')";
		else if (stripos($column->dbType,'text') !== false)
			return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
		else
		{
			if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='passwordFieldRow';
			else
				$inputField='textFieldRow';

			if ($column->type!=='string' || $column->size===null)
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5'))";
			else
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5','maxlength'=>$column->size))";
		}
	}
}
