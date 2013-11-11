<?php
/**
 * Igual ao "exist", mas testa em campos que recebem arrays.
 */
class AllExistValidator extends CValidator
{
    /**
	 * @var string the ActiveRecord class name that should be used to
	 * look for the attribute value being validated. Defaults to null,
	 * meaning using the ActiveRecord class of the attribute being validated.
	 * You may use path alias to reference a class name here.
	 * @see attributeName
	 */
	public $className;
	/**
	 * @var string the ActiveRecord class attribute name that should be
	 * used to look for the attribute value being validated. Defaults to null,
	 * meaning using the name of the attribute being validated.
	 * @see className
	 */
	public $attributeName;
	/**
	 * @var array additional query criteria. This will be combined with the condition
	 * that checks if the attribute value exists in the corresponding table column.
	 * This array will be used to instantiate a {@link CDbCriteria} object.
	 * @since 1.0.8
	 */
	public $criteria=array();
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
    protected function validateAttribute($object, $attribute)
    {
        $values = (array) $object->$attribute;

		if ($this->allowEmpty && !$values)
			return;

		$className = $this->className===null? get_class($object) : Yii::import($this->className);
		$attributeName = $this->attributeName===null? $attribute : $this->attributeName;

		$finder = FActiveRecord::model($className);

		$table = $finder->getTableSchema();

		if(($column = $table->getColumn($attributeName)) === null)
			throw new CException(Yii::t('yii','Table "{table}" does not have a column named "{column}".',
				array('{column}'=>$attributeName,'{table}'=>$table->name)));

		$sqlParams = array();
		foreach ($values as $key => $value) {
			$sqlParams[':param' . $key] = $value;
		}

		$criteria = array(
			'condition'=> ($column->rawName . ' IN (' . implode(', ', array_keys($sqlParams)) . ')'), 
			'params' => $sqlParams
		);

		if($this->criteria !== array()) {
			$criteria=new CDbCriteria($criteria);
			$criteria->mergeWith($this->criteria);
		}

		// Verifica se cada um existe
		$rows = $finder->findAll($criteria);
		$notFoundValues = $values;

		foreach ($rows as $row) {

			if (false !== ($key = array_search($row->$attributeName, $notFoundValues))) {

				unset($notFoundValues[$key]);
			}
		}

		// Lança um erro pros IDs não encontrados
		foreach ($notFoundValues as $value) {
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" is invalid.');
			$this->addError($object,$attribute,$message,array('{value}'=>$value));
		}
    }

}
