<?php
/**
 * Adiciona funcionalidades de i18n � classe base de AR
 * 
 * Criada pela quest�o de getters e setters que n�o se resolvia com behaviors.
 * 
 * Para acessar o valor float de um atributo, use o sufixo _float
 */
abstract class PActiveRecord extends CActiveRecord
{
	/**
	 * Ativa ou desativa a internacionaliza��o.
	 * @var boolean
	 */
	protected $_i18n = true;

	/**
	 * Desativa a internacionaliza��o para os seguintes atributos:
	 * @var array
	 */
	protected $_i18nIgnoredAttributes = array();

	/**
	 * Se manter� registro das mudan�as nos atributos
	 * @var boolean
	 */
	protected $_trackChangedAttributes = false;

	/**
	 * Armazena os atributos originais do modelo carregados pelo find
	 * para posterior verifica��o com attributeChanged e attributeOriginalValue
	 * @var array
	 */
	protected $_originalAttributes = array();

	/**
	 * Constructor.
	 * @param string $scenario scenario name. See {@link CModel::scenario} for more details about this parameter.
	 */
	public function __construct($scenario='insert')
	{
		Yii::import('application.validators.*');

		CValidator::$builtInValidators['allExist'] = 'FAllExistValidator';
		CValidator::$builtInValidators['cnpj'] = 'FCnpjValidator';
		CValidator::$builtInValidators['date'] = 'FDateValidator';
		CValidator::$builtInValidators['dateInterval'] = 'FDateIntervalValidator';
        CValidator::$builtInValidators['uniqueMultiColumnValidator'] = 'uniqueMultiColumnValidator';
        CValidator::$builtInValidators['uniqueMultiColumn'] = 'uniqueMultiColumnValidator';

		parent::__construct($scenario);
		$fDbCriteria = new CDbCriteria($this->getDbCriteria()->toArray());
		$this->setDbCriteria($fDbCriteria);

		if ($this->_i18n)
			$this->attachBehavior('I18NBehavior', array('class' => 'application.extensions.I18NBehavior'));
	}

	/**
	 * Retorna os atributos que ser�o ignorados na internacionaliza��o.
	 * @return array
	 */
	public function getI18nIgnoredAttributes()
	{
		return $this->_i18nIgnoredAttributes;
	}

	/**
	 * Seta os atributos que ser�o ignorados na internacionaliza��o.
	 * @param array $attributes
	 */
	public function setI18nIgnoredAttributes(array $attributes)
	{
		$this->_i18nIgnoredAttributes = $attributes;
	}

	/**
	 * Ap�s chamar o afterFind e seus behaviours, armazena os valores originais dos atributos.
	 */
	public function afterFind()
	{
	    $return = parent::afterFind();

	    if ($this->_trackChangedAttributes) {
	    	$this->_originalAttributes = $this->attributes;
	    }

	    return $return;
	}

	/**
	 * Informa se o atributo foi alterado desde que foi carregado.
	 * @return boolean
	 */
	public function attributeChanged($attribute)
	{ 
		$columnIsBool = false;	
		$tableSchema = $this->getTableSchema();
		
		if( isset($tableSchema->columns[$attribute]) && 
			property_exists($tableSchema->columns[$attribute], 'type')  &&
			$tableSchema->columns[$attribute]->type == 'boolean') {
				
				$columnIsBool = true;
		}
			
		if($columnIsBool)
			return (int) $this->$attribute !== (int) $this->attributeOriginalValue($attribute);
			
		return strval($this->$attribute) !== strval($this->attributeOriginalValue($attribute)); 
	}

	/**
	 * Retorna o valor original de um atributo, ao ser carregado.
	 * @return Mixed
	 */
	public function attributeOriginalValue($attribute)
	{
		// O isset ou array_key_exists n�o retornaria corretamente se houvesse um valor NULL do DB
		if (!in_array($attribute, array_keys($this->_originalAttributes))) {

			throw new Exception($attribute . ' n�o foi carregado pelo populateRecord');
		}

		return $this->_originalAttributes[$attribute]; 
	}
	
    /**
     * Retorna se o atributo(s) foi alterado desde que foi carregado
     * @param Mixed $attributes String para checar apenas um atributo ou Array de atributos para checar v�rios
     * @return Mixed boolean ou array de booleans indexados pelo nome dos atributos
     */
    public function checkChanged($attributes)
    {
        $class = get_class($this);
        $old = $class::model()->findByPk($this->primaryKey);

        if (empty($old))
            return false;

        $res = false;

        if (is_array($attributes)) { //Compara multiplos atributos
            $res = array();
            foreach ($attributes as $attribute) {
                $res[$attribute] = $this->$attribute != $old->$attribute;
            }
        }
        else { //Compara um atributo
           $res = $this->$attributes != $old->$attributes;
        }
        
        return $res;
    }
        
	/**
	 * PHP getter magic method.
	 * This method is overridden so that AR attributes can be accessed like properties.
	 * @param string $name property name
	 * @return mixed property value
	 * @see getAttribute
	 */
	public function __get($name)
	{
		if ($this->_i18n) {

			$nameArray = explode('_', $name);
			
			if (array_pop($nameArray) == 'float') {
				
				$name = implode('_', $nameArray);
				
				if ($this->hasAttribute($name)) {
					
					return self::parseFloat($this->$name);                
				}
				
				$name .= '_float';
			}
		}
		
		return parent::__get($name);
	}
	
	/**
	 * PHP setter magic method.
	 * This method is overridden so that AR attributes can be accessed like properties.
	 * @param string $name property name
	 * @param mixed $value property value
	 */
	public function __set($name, $value)
	{
		if ($this->_i18n && !$this->hasAttribute($name)) {

			$nameArray = explode('_', $name);
			
			if (array_pop($nameArray) == 'float') {
				
				$name = implode('_', $nameArray);
				
				if ($this->hasAttribute($name)) {
					
					if (is_float($value) || is_int($value)) {
						$value = Yii::app()->numberFormatter->formatDecimal($value);
					}
					
					return $this->$name = $value;
				}
			}
		}
		
		return parent::__set($name, $value);
	}

	/**
	 * Cria uma c�pia do objeto atual no banco de dados
	 * @param array $overridenAtributes Se algum atributo precisar ser modificado,
	 *                                  basta inform�-lo aqui. Ex: relacionamentos.
	 * @return FActiveRecord O objeto do registro criado
	 */
	public function createCopy(array $overridenAttributes = array())
	{
		$className = get_called_class();

		$model = new $className;

		// Evita problemas com atributos "safe"
		foreach ($this->attributes as $attribute => $value)
			$model->$attribute = $value;

		$pks = (array) $this->tableSchema->primaryKey;

		foreach ($pks as $pk)
			$model->$pk = null;

		foreach ($overridenAttributes as $attribute => $value) {
			$model->$attribute = $value;
		}

		if (!$model->save()) {
			throw new Exception('Erro ao duplicar objeto do tipo ' . get_class($this));
		}

		return $model;
	}

	/**
	 * Converte o objeto em um array. (necess�rio m�todo est�tico por que nem
	 * todos os modelos estendem do FActiveRecord, e n�o podemos alterar sem
	 * testar os efeitos colaterais de I18N, etc.)
	 * @param CModel $object
	 * @param array $attributesMap opcional, mapeia quais atributos deve retornar,
	 * e se � necess�rio renome�-los. Por exemplo:
	 * <code>
	 * array(
	 *     'id',
	 *     'hora',
	 *     'descricao' => 'ds_produto',
	 *     'ativo',
	 * )
	 * </code>
	 */
	public static function objectToArray($object, $attributesMap = null)
	{
		if ($attributesMap) {

			$filtered = array();

			foreach ($attributesMap as $key => $attribute) {

				$value = null;

				if (strpos($attribute, '.') !== false) {

					$value = $object;
					$attr = null;

					foreach (explode('.', $attribute) as $attr) {
						if (is_object($value)) {
							$value = $value->$attr;
						}
					}

					$attribute = $attr;
				}
				else {
					$value = $object->$attribute;
				}

				$key = is_numeric($key) ? $attribute : $key;

				$filtered[$key] = $value;
			}

			return $filtered;
		}
		else {
			return $object->attributes;
		}
	}

	/**
	 * Converte o objeto em um array.
	 * @param array $attributesMap opcional, mapeia quais atributos deve retornar,
	 * e se � necess�rio renome�-los. Por exemplo:
	 * <code>
	 * array(
	 *     'id',
	 *     'hora',
	 *     'descricao' => 'ds_produto',
	 *     'ativo',
	 * )
	 * </code>
	 */
	public function toArray($attributesMap = null)
	{
		return self::objectToArray($this, $attributesMap);
	}

	/**
	 * Scope que limita a query atual para N resultados
	 * @param integer $limit
	 * @return FActiveRecord
	 */
	public function limitTo($limit)
	{
		$limit = intval($limit);

		if ($limit > 0) {

			$this->getDbCriteria()->mergeWith(array('limit' => $limit));
		}

		return $this;
	}

	/**
	 * Scope que faz a consulta trazer somente as colunas especificadas
	 * @param array|string $columns colunas necess�rias
	 * @return FActiveRecord
	 */
	public function columns($columns)
	{
		if (!is_array($columns)) {
			$columns = array($columns);
		}
		
		$alias = $this->getTableAlias() . '.';

		$criteria = new FDbCriteria;
		$criteria->select = $alias . implode(', ' . $alias, $columns);

		$this->getDbCriteria()->mergeWith($criteria);

		return $this;
	}

	/**
	 * D� um find all na consulta atual e verifica se cada um dos IDs existe no banco
	 * @return array IDs que n�o existem no banco de dados (ou no WHERE dessa consulta)
	 */
	public function getMissingDbIdsIn(array $ids)
	{
		$idsInexistentes = $ids;

		$tableName = $this->tableName();
		$table = $this->getDbConnection()->getSchema()->getTable($tableName);

		$pk = ($table->primaryKey ? $table->primaryKey : $this->primaryKey());
		$alias = $this->getTableAlias();

		$pkColumn = $alias . '.' . $pk;

		$criteria = new FDbCriteria;
		$criteria->select = $pkColumn;
		$criteria->addInCondition($pkColumn, $idsInexistentes);

		foreach ($this->findAll($criteria) as $model) {

			$chave = array_search($model->$pk, $idsInexistentes);
			unset($idsInexistentes[$chave]);
		}

		return $idsInexistentes;
	}

	/**
	 * @return string 
	 */
	public function getCriteriaSQL()
	{
		$table = $this->tableName();
		$criteria = clone $this->getDbCriteria();
		$alias = $criteria->alias ? $criteria->alias : 't';

		if (!empty($criteria->params)){	
			foreach ($criteria->params as $param => $value) {
				if (is_string($value)) {
					$value = "'" . pg_escape_string($value) . "'";
				}
				$criteria->condition = preg_replace("'" . $param . "'", $value, $criteria->condition, 1);					
			}
			$criteria->params = null;
		}

		$command = $this->getCommandBuilder()->createFindCommand($table, $criteria, $alias);

		return $command->getText();
	}
	
	/**
	 * Busca atributos que foram alterados
	 * @return array
	 */ 
	public function getChangedAttributes() {
			
		$changedAttributes = array();
			
		$attributes = $this->getAttributes();
		foreach($attributes as $attribute => $value)		
			if($this->owner->attributeChanged($attribute)) 
				$changedAttributes[] = $attribute;
		
		return $changedAttributes;
	}
		
}
