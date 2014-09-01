<?php
namespace app\components;

class RedisActiveRecord extends \yii\redis\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function find()
    {
        $className = get_called_class();
        $queryClassName = str_replace('\\models\\', '\\models\\query\\', $className) . 'Query';

        if (class_exists($queryClassName)) { 
            $query = new $queryClassName($className);
        }
        else {
            $query = new \yii\redis\ActiveQuery($className);
        }
        
        return $query;
    }
}