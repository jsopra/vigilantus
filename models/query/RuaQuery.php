<?php
namespace app\models\query;

use app\components\ActiveQuery;

class RuaQuery extends ActiveQuery
{  
    public function daRua($nome) {
        $this->andWhere('nome = :rua', [':rua' => $nome]);
        return $this;
    }
}