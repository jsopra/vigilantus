<?php

namespace app\components;

use yii\base\Object;

/**
 * Classe que auxilia a geração de dados para drop-down lists (<select>)
 */
class ListData extends Object
{
    public $modelClass;
    public $valueAttribute = 'id';
    public $keyAttribute = 'id';
    public $groupAttribute;
    public $query;

    public function init()
    {
        $modelClass = $this->modelClass;

        if (null === $modelClass) {
            throw new \Exception('You must inform $modelClass name');
        }

        if (null === $this->query) {

            $this->query = $modelClass::find();
        }
        
        $model = new $modelClass;

        if ($model->hasAttribute($this->keyAttribute) && $model->hasAttribute($this->valueAttribute)) {

            $this->query->select($this->keyAttribute . ', ' . $this->valueAttribute);
            $this->orderBy($this->valueAttribute);
        }
    }

    public function getData()
    {
        $groupingRelation = $groupingRelationAttribute = false;

        if (is_array($this->groupAttribute)) {
            list($groupingRelation, $groupingRelationAttribute) = $this->groupAttribute;
        }

        if ($groupingRelation) {
            $this->query->with($groupingRelation);
        }
        
        $data = [];
        
        foreach ($this->query->all() as $object) {

            $key = $object->$idAttribute;
            $value = $object->$descriptionAttribute;

            if ($groupingRelation) {

                $group = $object->$groupingRelation->$groupingRelationAttribute;

                if (!isset($data[$group])) {
                    $data[$group] = [];
                }

                $data[$group][$key] = $value;

            } else {
                $data[$key] = $value;
            }
        }
    }
}