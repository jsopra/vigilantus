<?php

namespace app\components;

use \IntlDateFormatter;
use app\components\StringHelper;
use yii\db\ActiveRecord as YiiActiveRecord;
use yii\db\Expression;
use yii\validators\Validator;
use yii\db\ActiveQuery;
use app\models\Municipio;
use app\models\UsuarioRole;

class ActiveRecord extends YiiActiveRecord
{
    protected $dateDbTypes = [
        'date', 'datetime', 'timestamp', 'timestamp without time zone',
        'timestamptz'
    ];
    
    protected $dateOutcomeFormat = 'Y-m-d';
    protected $dateTimeOutcomeFormat = 'Y-m-d H:i:s';

    protected $dateIncomeFormat = 'yyyy-MM-dd';
    protected $dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';
    
    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $behaviors = [];

        if ($this->hasAttribute('data_cadastro') || $this->hasAttribute('data_atualizacao')) {

            $behaviors['AutoTimestamp'] = [
                'class' => 'yii\behaviors\AutoTimestamp',
                'timestamp' => new Expression('NOW()'),
                'attributes' => [],
            ];

            if ($this->hasAttribute('data_cadastro')) {
                $behaviors['AutoTimestamp']['attributes'][ActiveRecord::EVENT_BEFORE_INSERT] = 'data_cadastro';
            }

            if ($this->hasAttribute('data_atualizacao')) {
                $behaviors['AutoTimestamp']['attributes'][ActiveRecord::EVENT_BEFORE_UPDATE] = 'data_atualizacao';
            }
        }
        
        Validator::$builtInValidators['date'] = 'app\validators\DateValidator';
        Validator::$builtInValidators['unique'] = 'app\validators\UniqueValidator';

        $this->attachBehaviors($behaviors);

        parent::__construct($config);
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (substr($name, 0, 9) == 'formatted') {
            
            $referedName = substr($name, 10);
            
            if ($this->hasAttribute($referedName)) {
                return true;
            }
        }
        
        return parent::__isset($name);
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (substr($name, 0, 9) == 'formatted') {
            
            $referedName = substr($name, 10);
            
            if ($this->hasAttribute($referedName)) {
                return $this->getFormattedAttribute($referedName);
            }
        }
        
        return parent::__get($name);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (substr($name, 0, 9) == 'formatted') {
            
            $referedName = substr($name, 10);
            
            if ($this->hasAttribute($referedName)) {
                return $this->setFormattedAttribute($referedName, $value);
            }
        }
        
        return parent::__set($name, $value);
    }
    
    /**
     * Returns the attribute names that are safe to be massively assigned.
     * A safe attribute is one that is associated with a validation rule in the current {@link scenario}.
     * @return array safe attribute names
     */
    public function getSafeAttributeNames()
    {
        $safeAttributes = parent::getSafeAttributeNames();
        $formattedSafe = [];
        
        foreach ($safeAttributes as $attribute) {
            
            if (!empty($this->tableSchema->columns[$attribute])) {
                
                $metaData = $this->tableSchema->columns[$attribute];
                
                if (in_array($metaData->dbType, $this->dateDbTypes)) {
                    
                    $formattedSafe[] = 'formatted_' . $attribute;
                }
            }
        }
        
        return array_merge($safeAttributes, $formattedSafe);
    }

    /**
     * @param string $attribute
     * @return null|string
     */
    public function getFormattedAttribute($attribute)
    {
        if (!empty($this->tableSchema->columns[$attribute])) {
            
            $metaData = $this->tableSchema->columns[$attribute];
                
            if (in_array($metaData->dbType, $this->dateDbTypes) && strlen($this->$attribute)) {
                
                if ($metaData->dbType == 'date') {
                    return \Yii::$app->formatter->asDate($this->$attribute);
                } else {
                    return \Yii::$app->formatter->asDateTime($this->$attribute);
                }
            }
        }
        
        return null;
    }
    
    /**
     * @param string $attribute
     * @param string $value
     * @return void
     */
    public function setFormattedAttribute($attribute, $value)
    { 
        if (!empty($this->tableSchema->columns[$attribute])) {
            
            $metaData = $this->tableSchema->columns[$attribute];
            
            if (in_array($metaData->dbType, $this->dateDbTypes)) {
                
                if (!strlen($value)) {

                    $value = null;

                } elseif ($metaData->dbType == 'date') {

                    $formatter = new IntlDateFormatter(
                        \Yii::$app->language,
                        IntlDateFormatter::MEDIUM,
                        IntlDateFormatter::NONE
                    );

                    $value = date($this->dateOutcomeFormat, $formatter->parse($value));

                } else {

                    $formatter = new IntlDateFormatter(
                        \Yii::$app->language,
                        IntlDateFormatter::MEDIUM,
                        IntlDateFormatter::MEDIUM
                    );

                    $value = date($this->dateTimeOutcomeFormat, $formatter->parse($value));
                }
            }
        }
        
        return $this->setAttribute($attribute, $value);
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        $className = explode('\\', get_called_class());
        $className = array_pop($className);
        
        $tableName = StringHelper::camelToWords($className);

        return str_replace(' ', '_', strtolower($tableName));
    }
    
    /**
     * @param string $descriptionAttribute
     * @param string $idAttribute 'id' by default
     * @return array
     */
    public static function listData($descriptionAttribute, $idAttribute = 'id')
    {
        $query = self::find()
            ->select($idAttribute . ',' . $descriptionAttribute)
            ->orderBy($descriptionAttribute)
        ;
        
        $data = [];
        
        foreach ($query->all() as $object) {
            $data[$object->$idAttribute] = $object->$descriptionAttribute;
        }
        
        return $data;
    }
    
    /**
     * @param ActiveQuery $query
     */
    public static function limit($query, $rows)
    {
        $query->limit($rows);
    }
    
    /**
     * @param ActiveQuery $query
     */
    public static function randomOrdered($query)
    {
        $query->orderBy('RANDOM()');
    }
    
    /**
	 * Creates an [[ActiveQuery]] instance.
	 *
	 * This method is called by [[find()]], [[findBySql()]] to start a SELECT query.
	 * You may override this method to return a customized query (e.g. `CustomerQuery` specified
	 * written for querying `Customer` purpose.)
	 *
	 * You may also define default conditions that should apply to all queries unless overridden:
	 *
	 * ```php
	 * public static function createQuery()
	 * {
	 *     return parent::createQuery()->where(['deleted' => false]);
	 * }
	 * ```
	 *
	 * Note that all queries should use [[Query::andWhere()]] and [[Query::orWhere()]] to keep the
	 * default condition. Using [[Query::where()]] will override the default condition.
	 *
	 * @return ActiveQuery the newly created [[ActiveQuery]] instance.
	 */
	public static function createQuery($modelQuery = null)
	{
        $class = get_called_class();
        
        if($modelQuery) {
            $query = new $modelQuery(['modelClass' => $class]);
        }
        else {
            $query = new ActiveQuery(['modelClass' => $class]);
        }
        
        $model = new $class;
        
        if(\Yii::$app->hasComponent('session') && \Yii::$app->session->get('user.municipio') instanceof Municipio && $model->hasAttribute('municipio_id'))
            $query->andWhere('"municipio_id" IS NULL OR "municipio_id" = ' . \Yii::$app->session->get('user.municipio')->id);
        
        unset($model);
        
        return $query;
	}
    
	public function beforeValidate()
	{
        if(\Yii::$app->hasComponent('session') && \Yii::$app->session->get('user.municipio') instanceof Municipio && $this->hasAttribute('municipio_id'))
            $this->municipio_id = \Yii::$app->session->get('user.municipio')->id;
        
		return parent::beforeValidate();
    }
}
