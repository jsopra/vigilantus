<?php

namespace app\models\query;

use app\components\ActiveQuery;

class DenunciaTipoProblemaQuery extends ActiveQuery
{  

	public function ativos()
	{
		$this->andWhere('ativo IS TRUE');
        return $this;
	}
}
