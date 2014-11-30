<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BairroQuery extends ActiveQuery
{  
    public function doNome($nome) {
        $this->andWhere('nome = :nome', [':nome' => trim($nome)]);
        return $this;
    }
    
    public function comQuarteiroes() {
        
        $this->andWhere('id IN (SELECT DISTINCT bairro_id FROM bairro_quarteiroes)');
        return $this;
    }
    
    public function comCoordenadas() {
        $this->andWhere('coordenadas_area IS NOT NULL');
        return $this;
    }

    public function comFoco($ano, $especieTransmissor = null) 
    {
        $query = 'id IN (
            SELECT DISTINCT bq.bairro_id
            FROM focos_transmissores ft
            JOIN bairro_quarteiroes bq ON ft.bairro_quarteirao_id = bq.id
            WHERE EXTRACT (YEAR FROM ft.data_entrada) = :ano
        ';

        $params = [':ano' => $ano];

        if($especieTransmissor) {
            $query .= ' AND especie_transmissor_id = :idEspecie';
            $params[':idEspecie'] = $especieTransmissor;
        }

        $query .= ')';

        $this->andWhere($query, $params);

        return $this;
    }
}