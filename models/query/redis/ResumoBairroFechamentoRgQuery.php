<?php

namespace app\models\query\redis;

use Yii;
use app\components\RedisActiveQuery;

class ResumoBairroFechamentoRgQuery extends RedisActiveQuery
{
    public function doBairro($id)
    {
        $this->andWhere(['bairro_id' => $id]);
        return $this;
    }
}
