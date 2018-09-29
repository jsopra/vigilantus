<?php

namespace app\models\query;

use app\components\ActiveQuery;
use app\models\VisitaStatus;

class SemanaEpidemiologicaQuery extends ActiveQuery
{
    public function atual()
    {
        $this->andWhere('CURRENT_DATE BETWEEN inicio::date AND fim::date');
        return $this;
    }
}
