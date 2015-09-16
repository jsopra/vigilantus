<?php
namespace app\components;

use app\components\ActiveRecord;
use app\models\Cliente;
use Yii;

class ClienteActiveRecord extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function find()
    {
        $className = get_called_class();
        $queryClassName = str_replace('\\models\\', '\\models\\query\\', $className) . 'Query';
        $tableName = $className::tableName();

        $query = class_exists($queryClassName) ? new $queryClassName($className) : new ActiveQuery($className);

        if (self::temFiltroCliente()) {

            $idCliente = intval(Yii::$app->user->identity->cliente->id);
            $query->andWhere(
                '[[' . $tableName . '.cliente_id]] IS NULL OR [[' . $tableName . '.cliente_id]] = ' . $idCliente
            );
        }

        return $query;
    }

    /**
     * @inheritdoc
     */
	public function beforeValidate()
	{
        if (self::temFiltroCliente()) {
            $this->cliente_id = Yii::$app->user->identity->cliente->id;

            if(self::temFiltroMunicipio()) {
                $this->municipio_id = Yii::$app->user->identity->cliente->municipio->id;
            }
        }

		return parent::beforeValidate();
    }

    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id'])->via('cliente');
    }

    /**
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
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

    /**
     * @return boolean
     */
    protected static function temFiltroMunicipio()
    {
        return isset(static::getTableSchema()->columns['municipio_id']);
    }
}
