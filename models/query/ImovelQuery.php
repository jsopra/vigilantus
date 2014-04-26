<?php
namespace app\models\query;

use app\components\ActiveQuery;

class ImovelQuery extends ActiveQuery
{  
    public function daRua($rua) {
        $this->andWhere('rua_id = :rua', [':rua' => $rua]);
        return $this;
    }
    
    public function doQuarteirao($quarteirao) {
        $this->andWhere('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $quarteirao]);
        return $this;
    }
    
    public function doNumero($numero) {
        $this->andWhere('numero = :numero', [':numero' => $numero]);
        return $this;
    }
    
    public function daSeq($seq) {
        $this->andWhere('sequencia = :seq', [':seq' => $seq]);
        return $this;
    }
    
    public function doComplemento($complemento) {
        $this->andWhere('complemento = :compl', [':compl' => $complemento]);
        return $this;
    }
    
    public function doTipoLira($foco) {
        $this->andWhere('imovel_lira = :lira', [':lira' => $foco]);
        return $this;
    }
}