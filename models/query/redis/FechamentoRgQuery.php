<?php

namespace app\models\query\redis;

use Yii;
use app\components\RedisActiveQuery;
use app\models\EquipeAgente;

class FechamentoRgQuery extends RedisActiveQuery
{      
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

    public function comVisita(EquipeAgente $agente)
    {   /*
        $this->andWhere("bairro_quarteirao_id IN (
            SELECT quarteirao_id
            FROM semana_epidemiologica_visitas
            WHERE agente_id = " . $agente->id . "
        )");
        */
        $this->andWhere(['in', 'bairro_quarteirao_id', [1, 2, 3, 4, 5]]);
        return $this;
    }
}
