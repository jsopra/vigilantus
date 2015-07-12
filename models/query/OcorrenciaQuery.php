<?php

namespace app\models\query;

use app\components\ActiveQuery;

class OcorrenciaQuery extends ActiveQuery
{
    public function aberta()
    {
        $this->andWhere('data_fechamento IS NULL');
        return $this;
    }

    public function fechada()
    {
        $this->andWhere('data_fechamento IS NOT NULL');
        return $this;
    }

    public function anteriorA($dias)
    {
        $this->andWhere("data_criacao + interval '" . $dias . " days' >= CURRENT_DATE");
        return $this;
    }

    public function entre($inicio, $fim)
    {
        $this->andWhere("CURRENT_DATE BETWEEN data_criacao + interval '" . $inicio . " days' AND data_criacao + interval '" . $fim . " days'  ");
        return $this;
    }

    public function posteriorA($dias)
    {
        $this->andWhere("data_criacao + interval '" . $dias . " days' < CURRENT_DATE");
        return $this;
    }

    public function doNumeroControle($numero)
    {
        $this->andWhere('numero_controle = :numero_controle', [':numero_controle' => trim($numero)]);
        return $this;
    }
}
