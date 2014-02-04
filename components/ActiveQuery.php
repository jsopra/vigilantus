<?php
namespace app\components;

use yii\db\ActiveQuery as YiiActiveQuery;

class ActiveQuery extends YiiActiveQuery
{
    /**
     * @return ActiveQuery
     */
    public function randomOrdered()
    {
        $this->orderBy('RANDOM()');
        return $this;
    }
    
    /**
     * @param string $descriptionAttribute
     * @param string $idAttribute 'id' by default
     * @return array
     */
    public function listData($descriptionAttribute, $idAttribute = 'id')
    {
        $this->select($idAttribute . ',' . $descriptionAttribute);
        $this->orderBy($descriptionAttribute);
        
        $data = [];
        
        foreach ($this->all() as $object) {
            $data[$object->$idAttribute] = $object->$descriptionAttribute;
        }
        
        return $data;
    }
}