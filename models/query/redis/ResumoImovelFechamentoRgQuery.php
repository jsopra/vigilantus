<?php

namespace app\models\query\redis;

use Yii;
use app\components\RedisActiveQuery;

class ResumoImovelFechamentoRgQuery extends RedisActiveQuery
{
    public function doTipoImovel($id)
    {
        $this->andWhere(['imovel_tipo_id' => $id]);
        return $this;
    }
}
