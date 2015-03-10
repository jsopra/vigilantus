<?php

namespace app\models\query;

use app\components\ActiveQuery;

class DenunciaQuery extends ActiveQuery
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
        $this->andWhere("data_criacao + interval '" . $dias . " days' <= coalesce(data_fechamento, NOW())");

        return $this;
    }
}
