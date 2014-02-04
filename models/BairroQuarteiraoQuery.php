<?php
namespace app\models;

use yii\db\ActiveQuery;

class BairroQuarteiraoQuery extends ActiveQuery
{  
    public function doBairro($id) {
        $this->andWhere('bairro_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function doNumero($numero) {
        $this->andWhere('numero_quarteirao = :numero', [':numero' => $numero]);
        return $this;
    }
}