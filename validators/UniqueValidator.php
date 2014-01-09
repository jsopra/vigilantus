<?php

namespace app\validators;

use yii\validators\UniqueValidator as YiiUniqueValidator;

class UniqueValidator extends YiiUniqueValidator
{
    /*
     * Se é chave composta, apontar para os demais atributos
     * @var array|string
     */
    public $compositeWith;
    
    /**
     * @inheritdoc
     */
    public function validateAttribute($object, $attribute)
    {
        if (!$this->compositeWith) {
            return parent::validateAttribute($object, $attribute);
        }
        
        /** @var ActiveRecordInterface $targetClass */
        $targetClass = $this->targetClass === null ? get_class($object) : $this->targetClass;
        
        $params = [];
        
        $compositeAttributes = (array) $this->compositeWith;
        $compositeAttributes[] = $attribute;
        
        foreach ($compositeAttributes as $compositeAttribute) {
            $params[$compositeAttribute] = $object->$compositeAttribute;
        }

        $query = $targetClass::find();
        $query->where($params);

        if (!$object instanceof ActiveRecordInterface || $object->getIsNewRecord()) {
            // if current $object isn't in the database yet then it's OK just to call exists()
            $exists = $query->exists();
        } else {
            // if current $object is in the database already we can't use exists()
            /** @var ActiveRecordInterface[] $objects */
            $objects = $query->limit(2)->all();
            $n = count($objects);
            if ($n === 1) {
                $keys = array_keys($params);
                $pks = $targetClass::primaryKey();
                sort($keys);
                sort($pks);
                if ($keys === $pks) {
                    // primary key is modified and not unique
                    $exists = $object->getOldPrimaryKey() != $object->getPrimaryKey();
                } else {
                    // non-primary key, need to exclude the current record based on PK
                    $exists = $objects[0]->getPrimaryKey() != $object->getOldPrimaryKey();
                }
            } else {
                $exists = $n > 1;
            }
        }

        if ($exists) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}
