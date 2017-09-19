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
}