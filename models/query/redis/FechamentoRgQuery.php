<?php

namespace app\models\query\redis;

use Yii;
use yii\redis\ActiveQuery;

class FechamentoRgQuery extends ActiveQuery
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

    public function doBairroQuarteirao($id)
    {
        $this->andWhere(['bairro_quarteirao_id' => $id]);
        return $this;
    }
    
    public function doTipoLira($status)
    {
        $this->andWhere(['lira' => $status === true ? 1 : 0]);
        return $this;
    }
    
    public function doTipoImovel($id)
    {
        $this->andWhere(['imovel_tipo_id' => $id]);
        return $this;
    }

    public function daData($data)
    {
        $this->andWhere(['data' => $data]);
        return $this;
    }
}
