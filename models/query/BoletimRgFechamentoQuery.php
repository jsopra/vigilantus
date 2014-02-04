<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BoletimRgFechamentoQuery extends ActiveQuery
{  
    public function doBoletim($id) {
        $this->andWhere('boletim_rg_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function daCondicaoDeImovel($id) {
        $this->andWhere('condicao_imovel_id = :condicao', [':condicao' => $id]);
        return $this;
    }
    
    public function doTipoDeImovel($id) {
        $this->andWhere('imovel_tipo_id = :tipo', [':tipo' => $id]);
        return $this;
    }
    
    public function doTipoDeFoco($foco) {
        $this->andWhere('area_de_foco = :foco', [':foco' => $foco]);
        return $this;
    }
}