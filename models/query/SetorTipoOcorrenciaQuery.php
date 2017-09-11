<?php

namespace app\models\query;

use app\components\ActiveQuery;

class SetorTipoOcorrenciaQuery extends ActiveQuery
{
    public function doSetor($id) {
        $this->andWhere('setor_id = :id', [':id' => $id]);
        return $this;
    }
}
