<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BairroQuery extends ActiveQuery
{  
    public function comQuarteiroes() {
        
        $this->andWhere('id IN (SELECT DISTINCT bairro_id FROM bairro_quarteiroes)');
        return $this;
    }
}