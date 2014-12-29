<?php
namespace app\components;

use yii\redis\ActiveQuery as YiiRedisAR;

class RedisActiveQuery extends YiiRedisAR
{
    /**
     * @param int $id
     * @return \app\components\ActiveQuery 
     */
    public function doCliente($id)
    {
        $this->andWhere(['cliente_id' => $id]);
        return $this;
    }
}