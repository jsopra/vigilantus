<?php

namespace app\models\query;

use app\components\ActiveQuery;

class OcorrenciaTipoProblemaQuery extends ActiveQuery
{
	public function ativos()
	{
		$this->andWhere('ativo IS TRUE');
        return $this;
	}

    public function comNome($nome)
    {
        $this->andWhere("nome ILIKE '%" . trim($nome) . "%'");
        return $this;
    }
}
