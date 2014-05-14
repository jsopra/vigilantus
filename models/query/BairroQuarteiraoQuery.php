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
                data_coleta BETWEEN NOW() - INTERVAL '" . Yii::$app->params['quantidadeDiasFocoAtivo'] . " DAYS' AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        )";
        
        $this->andWhere($query);
        
        return $this;
    }
    
    public function emAreaDeTratamento($lira = null) {
        
        $whereLira = null;
        if($lira !== null)
            $whereLira = $lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE';
        
        $query = "
        id IN (
            SELECT br.id
            FROM focos_transmissores ft
            JOIN imoveis i on ft.imovel_id = i.id
            JOIN bairro_quarteiroes bf on i.bairro_quarteirao_id = bf.id
            LEFT JOIN bairro_quarteiroes br	ON ST_DWithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area), " . Yii::$app->params['quantidadeMetrosFocoParaAreaDeTratamento'] . ", true)
            WHERE 
                " . ($whereLira ? $whereLira . ' AND ' : '') . "
                data_coleta BETWEEN NOW() - INTERVAL '" . Yii::$app->params['quantidadeDiasFocoAtivo'] . " DAYS' AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        )";
        
        $this->andWhere($query);
        
        return $this;
        	
    }
}