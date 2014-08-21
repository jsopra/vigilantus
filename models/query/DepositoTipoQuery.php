<?php

namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class DepositoTipoQuery extends ActiveQuery
{  
    public function daSigla($sigla) {
        
        $this->andWhere('sigla = :sigla', [':sigla' => $sigla]);
        return $this;
    }
}
