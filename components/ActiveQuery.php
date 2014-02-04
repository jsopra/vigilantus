<?php
namespace app\components;

use yii\db\ActiveQuery as YiiActiveQuery;

class ActiveQuery extends YiiActiveQuery
{
    /**
     * @return ActiveQuery
     */
    public function randomOrdered()
    {
        $this->orderBy('RANDOM()');
        return $this;
    }
}