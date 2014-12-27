<?php

namespace app\models\query;

use app\components\ActiveQuery;

class ConfiguracaoQuery extends ActiveQuery
{
    public function doId($id)
    {
        $this->andWhere('id = :idconfiguracao', [':idconfiguracao' => $id]);
        return $this;
    }
}
