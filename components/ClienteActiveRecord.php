<?php
namespace app\components;

use app\components\ActiveRecord;
use app\models\Cliente;

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
            
            $idCliente = intval(\Yii::$app->session->get('user.cliente')->id);
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
            $this->cliente_id = \Yii::$app->session->get('user.cliente')->id;
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
            && \Yii::$app->has('session')
            && \Yii::$app->session->get('user.cliente') instanceof Cliente
            && isset(static::getTableSchema()->columns['cliente_id'])
        );
    }
}