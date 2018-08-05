<?php

namespace app\models\query;

use app\components\ActiveQuery;

class EquipeQuery extends ActiveQuery
{
    public function doNome($nome)
    {
        $this->andWhere('nome = :nome', [':nome' => trim($nome)]);
        return $this;
    }
    public function doAgente($id)
    {
        $this->andWhere('id IN (SELECT equipe_id FROM equipe_agentes WHERE id = ' . $id . ')');
        return $this;
    }
}
