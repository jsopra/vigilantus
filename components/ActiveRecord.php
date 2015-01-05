<?php

namespace app\components;

use \IntlDateFormatter;
use app\components\ActiveQuery;
use app\components\StringHelper;
use yii\db\ActiveRecord as YiiActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\validators\Validator;
use app\models\UsuarioRole;
use app\models\Cliente;
use perspectivain\postgis\ActiveRecord as PostgisActiveRecord;

class ActiveRecord extends PostgisActiveRecord
{
    const SAVE_OBJECT = 1;
    const DONT_SAVE_OBJECT = 0;

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

            $behaviors['TimestampBehavior'] = [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => [],
            ];

            if ($this->hasAttribute('data_cadastro')) {
                $behaviors['TimestampBehavior']['attributes'][ActiveRecord::EVENT_BEFORE_INSERT] = 'data_cadastro';
            }

            if ($this->hasAttribute('data_atualizacao')) {
                $behaviors['TimestampBehavior']['attributes'][ActiveRecord::EVENT_BEFORE_UPDATE] = 'data_atualizacao';
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
     * @inheritdoc
     */
    public static function find()
    {
        $className = get_called_class();
        $queryClassName = str_replace('\\models\\', '\\models\\query\\', $className) . 'Query';
        $tableName = $className::tableName();

        $query = class_exists($queryClassName) ? new $queryClassName($className) : new ActiveQuery($className);

        return $query;
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
     * @param string $groupingRelation Se quiser agrupar por uma relation (ex: bairros agrupados por cidade no <select>)
     * @param string $groupingRelationAttribute Nome do atributo da relation usado para o <optgroup>
     * @return array
     */
    public static function listData($descriptionAttribute, $idAttribute = 'id', $groupingRelation = null, $groupingRelationAttribute = 'id')
    {
        return static::find()->listData($descriptionAttribute, $idAttribute, $groupingRelation, $groupingRelationAttribute);
    }

    /**
     * Procura pelos atributos. Se não encontrar, cria um novo
     * @param array $attributes atributo => valor
     * @param boolean $save Se deve salvar o objeto antes de retornar ou só construí-lo
     * @return ActiveRecord
     */
    public static function findOrCreate($attributes, $save = true)
    {
        $object = static::find()->where($attributes)->one();

        if (!$object) {

            $object = new static($attributes);

            if ($save && false == $object->save()) {
                throw new \Exception('Falhou ao salvar objeto! Erros: ' . print_r($object->errors, true));
            }
        }

        return $object;
    }
}
