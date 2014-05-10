<?php
namespace app\models\query;

use app\components\ActiveQuery;

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
    
    public function daSequencia($numero) {
        $this->andWhere('seq = :numero', [':numero' => $numero]);
        return $this;
    }
    
    public function comCoordenadas() {
        $this->andWhere('coordenadas_area IS NOT NULL');
        return $this;
    }
}