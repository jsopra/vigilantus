<?php
namespace app\models\query;

use app\components\ActiveQuery;

class BoletimRgQuery extends ActiveQuery
{  
    public function comAreasDeFoco()
    {
        $this->andWhere(
            'id IN (SELECT boletim_rg_id FROM boletim_rg_fechamento WHERE area_de_foco = TRUE)'
        );
        return $this;
    }
}

    