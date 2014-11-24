<?php

namespace app\models\query\redis;

use Yii;
use app\components\RedisActiveQuery;

class FocoTransmissorQuery extends RedisActiveQuery
{      
    public function doBairro($id)
    {
        $this->andWhere(['bairro_id' => $id]);
        return $this;
    }
    
    public function doImovelLira($status)
    {
        $this->andWhere(['imovel_lira' => $status === true ? 1 : 0]);
        return $this;
    }
    
    public function daEspecieDeTransmissor($id)
    {
        $this->andWhere(['especie_transmissor_id' => $id]);
        return $this;
    }

    public function informacaoPublica()
    {
        $this->andWhere(['informacao_publica' => 1]);

        return $this;
    }
}
