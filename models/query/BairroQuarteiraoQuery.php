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
        $this->andWhere('numero_quarteirao = :numero', [':numero' => ltrim($numero, '0')]);
        return $this;
    }
    
    public function daSequencia($numero) {
        $this->andWhere('seq = :sequencia', [':sequencia' => $numero]);
        return $this;
    }
    
    public function comCoordenadas() {
        $this->andWhere('coordenadas_area IS NOT NULL');
        return $this;
    }
    
    public function queNao($id) {
        $this->andWhere('id <> :quenao', [':quenao' => $id]);
        return $this;
    }
    
    public function comFocosAtivos($lira = null, $especieTransmissor = null) {
        
        $whereLira = null;
        if($lira !== null)
            $whereLira = $lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE';
        
        $whereEspecie = '';
        if($especieTransmissor !== null)
            $whereEspecie = ' AND et.id = ' . $especieTransmissor;
        
        $query = "
        id IN (
            SELECT focos_transmissores.bairro_quarteirao_id
            FROM focos_transmissores
            JOIN especies_transmissores et ON focos_transmissores.especie_transmissor_id = et.id
            LEFT JOIN imoveis ON focos_transmissores.imovel_id = imoveis.id
            WHERE 
                " . ($whereLira ? $whereLira . ' AND ' : '') . "
                data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * et.qtde_dias_permanencia_foco AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
                " . $whereEspecie . "
        )";
        
        $this->andWhere($query);
        
        return $this;
    }
    
    public function emAreaDeTratamento($lira = null, $especieTransmissor = null) {
        
        $whereLira = null;
        if($lira !== null)
            $whereLira = $lira === true ? 'imovel_lira = TRUE' : 'imovel_lira = FALSE';
        
        $whereEspecie = '';
        if($especieTransmissor !== null)
            $whereEspecie = ' AND et.id = ' . $especieTransmissor;
        
        $query = "
        id IN (
            SELECT br.id
            FROM focos_transmissores ft
            JOIN especies_transmissores et ON ft.especie_transmissor_id = et.id
            JOIN bairro_quarteiroes bf on ft.bairro_quarteirao_id = bf.id
            LEFT JOIN imoveis i on ft.imovel_id = i.id
            LEFT JOIN bairro_quarteiroes br	ON ST_DWithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area), et.qtde_metros_area_foco, true)
            WHERE 
                " . ($whereLira ? $whereLira . ' AND ' : '') . "
                data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * et.qtde_dias_permanencia_foco AND NOW() AND
                (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
                " . $whereEspecie . "
        )";
        
        $this->andWhere($query);
        
        return $this;
        	
    }
}