<?php

namespace app\models\search;

use app\components\SearchModel;

class BoletimRgFechamentoSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $boletim_rg_id;
    public $condicao_imovel_id;
    public $quantidade;
    public $area_de_foco;

    public function rules()
    {
        return [
            [['boletim_rg_id'], 'required'],
            [['municipio_id', 'boletim_rg_id', 'condicao_imovel_id', 'condicao_imovel_id', 'quantidade'], 'integer'],
            [['area_de_foco'], 'boolean']
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'boletim_rg_id');
        $this->addCondition($query, 'condicao_imovel_id');
        $this->addCondition($query, 'quantidade');
        $this->addCondition($query, 'area_de_foco');
    }
}
