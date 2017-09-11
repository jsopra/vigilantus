<?php

namespace app\models\search;

use app\components\SearchModel;

class SetorTipoOcorrenciaSearch extends SearchModel
{
    public $id;
    public $setor_id;
    public $tipos_problemas_id;

    public function rules()
    {
        return [
            [['id', 'setor_id','tipos_problemas_id'], 'integer'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'setor_id');
        $this->addCondition($query, 'tipos_problemas_id');
    }
}
