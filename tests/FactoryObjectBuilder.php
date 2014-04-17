<?php

namespace tests;

use Phactory\Builder;
use Phactory\HasOneRelationship;
use yii\db\ActiveRecordInterface;

class FactoryObjectBuilder extends Builder
{
    /**
     * @param string $name
     * @return string
     */
    protected function getClassName($name)
    {
        return 'app\\models\\' . ucfirst($name);
    }

    protected function toObject($phactoryName, $values)
    {
        $className = $this->getClassName($phactoryName);
        $object = new $className;

        foreach ($values as $key => $value) {

            if ($value instanceof ActiveRecordInterface) {
                $value = $value->id;
            }

            $object->$key = $value;
        }

        return $object;
    }

    protected function saveObject($name, $object)
    {
        if ($object instanceof ActiveRecordInterface) {
            unset($object->id);
            if (false == $object->save()) {
                throw new \Exception('Couldn\'t save the object: ' . print_r($object->errors, true));
            }
        }
        
        return $object;
    }
}
