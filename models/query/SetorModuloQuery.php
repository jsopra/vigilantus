<?php
namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class SetorModuloQuery extends ActiveQuery
{
    public function doSetor($id)
    {
        $this->andWhere('setor_id = :id', [':id' => $id]);
        return $this;
    }

    public function dosSetores($ids)
    {
        $this->andWhere('setor_id IN (' . implode(',', $ids) . ')');
        return $this;
    }

    public function doModulo($id)
    {
        $this->andWhere('modulo_id = :idModulo', [':idModulo' => $id]);
        return $this;
    }
}