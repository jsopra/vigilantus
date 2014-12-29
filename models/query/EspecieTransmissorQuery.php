<?php

namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class EspecieTransmissorQuery extends ActiveQuery
{  
    public function doNome($nome) {
        
        $this->andWhere('nome = :nome', [':nome' => $nome]);
        return $this;
    }
}
