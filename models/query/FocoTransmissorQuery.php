<?php

namespace app\models\query;

use Yii;
use app\models\BairroQuarteirao as AppBairroQuarteirao;
use app\components\ActiveQuery;

class FocoTransmissorQuery extends ActiveQuery
{  
    public function doBairro($id) {
        
        $this->joinWith(['imovel', 'imovel.bairroQuarteirao']);
        $this->andWhere('bairro_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function doImovelLira($lira) {
        
        $this->joinWith('imovel');
        $this->andWhere($lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE');
        return $this;
    }
    
    public function daAreaDeTratamento(AppBairroQuarteirao $quarteirao) {
        
        $query = "
        id IN (
            SELECT ft.id
            FROM focos_transmissores ft
            JOIN imoveis i on ft.imovel_id = i.id
            JOIN bairro_quarteiroes bf on i.bairro_quarteirao_id = bf.id
            LEFT JOIN bairro_quarteiroes br	ON ST_DWithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area), " . Yii::$app->params['quantidadeMetrosFocoParaAreaDeTratamento'] . ", true)
            WHERE 
                br.id = " . $quarteirao->id . " AND
                data_coleta BETWEEN NOW() - INTERVAL '" . Yii::$app->params['quantidadeDiasFocoAtivo'] . " DAYS' AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        )";
        
        $this->andWhere($query);
        
        return $this;
    }
}
