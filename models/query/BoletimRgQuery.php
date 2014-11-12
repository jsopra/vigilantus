<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BoletimRgQuery extends ActiveQuery
{  
    public function daFolha($folha) {
        $this->andWhere('folha = :folha', [':folha' => $folha]);
        return $this;
    }
    
    public function daData($data) {
        $this->andWhere('data = :data', [':data' => $data]);
        return $this;
    }
    
    public function doBairro($id) {
        $this->andWhere('bairro_id = :bairro', [':bairro' => $id]);
        return $this;
    }
    
    public function doBairroQuarteirao($id) {
        $this->andWhere('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $id]);
        return $this;
    }
}

    