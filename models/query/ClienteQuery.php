<?php
namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class ClienteQuery extends ActiveQuery
{
    public function doRotulo($rotulo)
    {
        $this->andWhere('rotulo = :rotulo', [':rotulo' => $rotulo]);
        return $this;
    }

    public function ativo()
    {
        $this->andWhere('ativo IS TRUE');
        return $this;
    }
}
