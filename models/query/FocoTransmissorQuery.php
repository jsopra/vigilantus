<?php

namespace app\models\query;

use Yii;
use app\models\BairroQuarteirao as AppBairroQuarteirao;
use app\components\ActiveQuery;

class FocoTransmissorQuery extends ActiveQuery
{  
    public function doBairro($id) {
        
        $this->joinWith(['bairroQuarteirao']);
        $this->andWhere('bairro_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function doImovelLira($lira) {
        
        $this->joinWith('imovel');
        $this->andWhere($lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE');
        return $this;
    }
    
    public function daEspecieDeTransmissor($especieTransmissor) {
        
        $this->andWhere('especie_transmissor_id = :idEspecie', [':idEspecie' => $especieTransmissor]);
        return $this;
    }

    public function daAreaDeTratamento(AppBairroQuarteirao $quarteirao) {
        
        $query = "
        id IN (
            SELECT ft.id
            FROM focos_transmissores ft
            JOIN especies_transmissores et ON ft.especie_transmissor_id = et.id
            JOIN bairro_quarteiroes bf on ft.bairro_quarteirao_id = bf.id
            LEFT JOIN bairro_quarteiroes br	ON ST_DWithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area), et.qtde_metros_area_foco, true)
            WHERE 
                br.id = " . $quarteirao->id . " AND
                data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * et.qtde_dias_permanencia_foco AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        )";
        
        $this->andWhere($query);
        
        return $this;
    }
    
    public function ativo() {
        
        $this->joinWith('especieTransmissor');
        $this->andWhere("
            data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * qtde_dias_permanencia_foco AND NOW() AND
            (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
        ");
        
        return $this;
    }
}
