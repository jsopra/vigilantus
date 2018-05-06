<?php

namespace app\models\search;

use app\components\SearchModel;

class EquipeSupervisorSearch extends SearchModel
{
    public $id;
    public $equipe_id;
    public $codigo;
    public $usuario_id;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'equipe_id', 'cliente_id', 'usuario_id'], 'integer'],
            [['codigo'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'equipe_id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'usuario_id');
        $this->addCondition($query, 'codigo', true);
    }
}
