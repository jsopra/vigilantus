<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BairroQuery extends ActiveQuery
{  
    public function doNome($nome) {
        $this->andWhere('nome = :nome', [':nome' => $nome]);
        return $this;
    }
    
    public function comQuarteiroes() {
        
        $this->andWhere('id IN (SELECT DISTINCT bairro_id FROM bairro_quarteiroes)');
        return $this;
    }
    
    public function comCoordenadas() {
        $this->andWhere('coordenadas_area IS NOT NULL');
        return $this;
    }
}