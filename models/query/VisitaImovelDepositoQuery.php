<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[VisitaImovelDeposito]].
 *
 * @see VisitaImovelDeposito
 */
class VisitaImovelDepositoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VisitaImovelDeposito[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisitaImovelDeposito|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
