<?php
namespace app\models\query;

use app\components\ActiveQuery;

class DoencaQuery extends ActiveQuery
{
    public function doNome($nome) {
        $this->andWhere('nome = :nome', [':nome' => trim($nome)]);
        return $this;
    }
}
