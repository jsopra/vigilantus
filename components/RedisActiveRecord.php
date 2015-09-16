<?php
namespace app\components;

use app\models\Municipio;
use Yii;

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

        if (self::temFiltroCliente()) {
            $query->andWhere(['cliente_id' => intval(\Yii::$app->user->identity->cliente->id)]);
        }

        return $query;
    }

    /**
     * @return boolean
     */
    protected static function temFiltroCliente()
    {
        return (
            php_sapi_name() != 'cli'
            && Yii::$app->has('session')
            && !Yii::$app->user->getIsGuest()
            && Yii::$app->user->identity->cliente instanceof Cliente
            && isset(static::getTableSchema()->columns['cliente_id'])
        );
    }
}
