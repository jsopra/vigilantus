<?php

namespace app\models\query;

use app\components\ActiveQuery;

class OcorrenciaHistoricoQuery extends ActiveQuery
{
	public function daOcorrencia($id)
	{
        $this->andWhere('ocorrencia_id = :idOcorrencia', [':idOcorrencia' => $id]);
        return $this;
    }
}
