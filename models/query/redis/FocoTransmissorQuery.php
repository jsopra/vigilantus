<?php

namespace app\models\query\redis;

use Yii;
use yii\redis\ActiveQuery;

class FocoTransmissorQuery extends ActiveQuery
{      
    public function doMunicipio($id)
    {
        $this->andWhere(['municipio_id' => $id]);
        return $this;
    }
    
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
}
