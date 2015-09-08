<?php

namespace app\models\query;

use app\components\ActiveQuery;

class SetorUsuarioQuery extends ActiveQuery
{
    public function doSetor($id) {
        $this->andWhere('setor_id = :id', [':id' => $id]);
        return $this;
    }
}
