<?php

namespace app\models\query;

use app\components\ActiveQuery;

class SetorQuery extends ActiveQuery
{  
    public function queNao($id)
    {
        $this->andWhere('id <> :id', [':id' => $id]);
        return $this;
    }

    public function padraoParaOcorrencias()
    {
        $this->andWhere('padrao_ocorrencias IS TRUE');
        return $this;
    }
}
