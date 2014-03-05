<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BoletimRgFechamentoQuery extends ActiveQuery
{  
    public function doBoletim($id) {
        $this->andWhere('boletim_rg_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function doTipoDeImovel($id) {
        $this->andWhere('imovel_tipo_id = :tipo', [':tipo' => $id]);
        return $this;
    }

    public function doTipoLira($foco) {
        $this->andWhere('imovel_lira = :lira', [':lira' => $foco]);
        return $this;
    }
}