<?php

namespace app\models\search;

use app\components\SearchModel;

class SetorUsuarioSearch extends SearchModel
{
    public $id;
    public $setor_id;
    public $usuario_id;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'setor_id', 'cliente_id','usuario_id'], 'integer'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'setor_id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'usuario_id');
    }
}
