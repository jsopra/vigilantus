<?php

namespace app\models\search;

use app\components\SearchModel;

class EspecieTransmissorSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $nome;
    public $qtde_metros_area_foco;
    public $qtde_dias_permanencia_foco;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'qtde_metros_area_foco', 'qtde_dias_permanencia_foco'], 'integer'],
            [['nome'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'qtde_metros_area_foco');
        $this->addCondition($query, 'qtde_dias_permanencia_foco');
    }
}
