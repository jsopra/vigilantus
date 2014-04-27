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
     * @param string $groupingRelation Se quiser agrupar por uma relation (ex: bairros agrupados por cidade no <select>)
     * @param string $groupingRelationAttribute Nome do atributo da relation usado para o <optgroup>
     * @return array
     */
    public function listData($descriptionAttribute, $idAttribute = 'id', $groupingRelation = null, $groupingRelationAttribute = 'id')
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass;

        if ($model->hasAttribute($descriptionAttribute)) {
            $this->select($idAttribute . ',' . $descriptionAttribute);
            $this->orderBy($descriptionAttribute);
        }

        if ($groupingRelation) {
            $this->with($groupingRelation);
        }
        
        $data = [];
        
        foreach ($this->all() as $object) {

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

        return $data;
    }
}