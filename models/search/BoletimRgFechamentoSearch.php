<?php

namespace app\models\search;

use app\components\SearchModel;

class BoletimRgFechamentoSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $boletim_rg_id;
    public $quantidade;
    public $area_de_foco;

    public function rules()
    {
        return [
            [['municipio_id', 'boletim_rg_id','quantidade',],'integer'],
            [['area_de_foco'], 'boolean']
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'boletim_rg_id');
        $this->addCondition($query, 'quantidade');
        $this->addCondition($query, 'area_de_foco');
    }
}
