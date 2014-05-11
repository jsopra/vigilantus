<?php
namespace app\models\query;

use Yii;
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
    
    public function queNao($id) {
        $this->andWhere('id <> :numero', [':numero' => $id]);
        return $this;
    }
    
    public function comFocosAtivos($lira = null) {
        
        $whereLira = null;
        if($lira !== null)
            $whereLira = $lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE';
        
        $query = "
        id IN (
            SELECT bairro_quarteirao_id
            FROM focos_transmissores
            JOIN imoveis ON focos_transmissores.imovel_id = imoveis.id
            WHERE 
                " . ($whereLira ? $whereLira . ' AND ' : '') . "
                data_entrada BETWEEN NOW() - INTERVAL '" . Yii::$app->params['quantidadeDiasFocoAtivo'] . " DAYS' AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        )";
        
        $this->andWhere($query);
        
        return $this;
    }
}