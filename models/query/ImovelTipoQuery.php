<?php
namespace app\models\query;

use app\components\ActiveQuery;

class ImovelTipoQuery extends ActiveQuery
{  
    public function daSigla($sigla) {
        
        $this->andWhere('sigla = :sigla', [':sigla' => $sigla]);
        return $this;
    }
    
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
