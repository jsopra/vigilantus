<?php
namespace app\models\query;

use app\components\ActiveQuery;

class ImovelTipoQuery extends ActiveQuery
{  
    /**
     * @param ActiveQuery $query
     */
    public function ativo()
    {
        $this->andWhere('excluido IS FALSE');
        return $this;
    }
    
    /**
     * @param ActiveQuery $query
     */
    public function excluido()
    {
        $this->andWhere('excluido IS TRUE');
        return $this;
    }
}
