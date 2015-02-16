<?php

namespace app\models\query;

use app\components\ActiveQuery;

class PontoEstrategicoQuery extends ActiveQuery
{
    public function queNao($id) {
        $this->andWhere('id <> :quenao', [':quenao' => $id]);
        return $this;
    }
}
