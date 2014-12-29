<?php

namespace app\models\query;

use app\components\ActiveQuery;

class DenunciaHistoricoQuery extends ActiveQuery
{  
	public function daDenuncia($id) 
	{
        $this->andWhere('denuncia_id = :idDenuncia', [':idDenuncia' => $id]);
        return $this;
    }
}
