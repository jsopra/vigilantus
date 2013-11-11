<?php

Yii::import('system.gii.generators.model.ModelCode');

class PerspectivaModelCode extends ModelCode {

	public $modelPath = 'application.models';
	public $testUnitPath = 'application.tests.unit';
	public $testFixturesPath = 'application.tests.fixtures';
	public $baseClass = 'CActiveRecord';
	
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'tablePrefix'=>'Prefixo da Tabela',
			'tableName'=>'Nome da Tabela',
			'modelPath'=>'Diretório do Modelo',
			'testUnitPath'=>'Diretório dos Testes Unitários',
			'testFixturesPath'=>'Diretório das Fixtures',
			'modelClass'=>'Classe do Modelo',
			'baseClass'=>'Classe Base',
			'buildRelations'=>'Construir Relacionamentos',
		));
	}

	public function requiredTemplates()
	{
		return array(
			'model.php',
		);
	}
	
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('testUnitPath, testFixturesPath', 'filter', 'filter'=>'trim'),
			array('testUnitPath, testFixturesPath', 'required'),
			array('testUnitPath, testFixturesPath', 'match', 'pattern'=>'/^(\w+[\w\.]*|\*?|\w+\.\*)$/', 'message'=>'{attribute} should only contain word characters, dots, and an optional ending asterisk.'),
			array('testUnitPath, testFixturesPath', 'validatePathAlias', 'skipOnError'=>true),
			array('testUnitPath, testFixturesPath', 'sticky'),
		));
	}
	
	public function validatePathAlias($attribute,$params)
	{
		if(Yii::getPathOfAlias($this->$attribute)===false)
			$this->addError($attribute,'Must be a valid path alias.');
	}
	
	public function prepare()
	{
		if(($pos=strrpos($this->tableName,'.'))!==false)
		{
			$schema=substr($this->tableName,0,$pos);
			$tableName=substr($this->tableName,$pos+1);
		}
		else
		{
			$schema='';
			$tableName=$this->tableName;
		}
		if($tableName[strlen($tableName)-1]==='*')
		{
			$tables=Yii::app()->db->schema->getTables($schema);
			if($this->tablePrefix!='')
			{
				foreach($tables as $i=>$table)
				{
					if(strpos($table->name,$this->tablePrefix)!==0)
						unset($tables[$i]);
				}
			}
		}
		else
			$tables=array($this->getTableSchema($this->tableName));

		$this->files=array();
		$templatePath=$this->templatePath;
		$this->relations=$this->generateRelations();

		foreach($tables as $table)
		{
			$tableName=$this->removePrefix($table->name);
			$className=$this->generateClassName($table->name);
			$params=array(
				'tableName'=>$schema==='' ? $tableName : $schema.'.'.$tableName,
				'modelClass'=>$className,
				'columns'=>$table->columns,
				'labels'=>$this->generateLabels($table),
				'rules'=>$this->generateRules($table),
				'relations'=>isset($this->relations[$className]) ? $this->relations[$className] : array(),
			);
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias($this->modelPath).'/'.$className.'.php',
				$this->render($templatePath.'/model.php', $params)
			);
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias($this->testUnitPath).'/'.$className.'Test.php',
				$this->render($templatePath.'/unit.php', $params)
			);
			
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias($this->testFixturesPath).'/'.$this->pluralize($params['tableName']).'.php',
				$this->render($templatePath.'/fixtures.php', $params)
			);
		}
	}
}
