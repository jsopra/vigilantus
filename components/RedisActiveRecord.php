<?php
namespace app\components;

use app\models\Municipio;

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

        $idMunicipio = Municipio::find()->one()->id;//intval(\Yii::$app->session->get('user.municipio')->id);
        $query->andWhere(['municipio_id' => $idMunicipio]);
        
        return $query;
    }
}