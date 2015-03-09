<?php

namespace app\models\query;

use app\components\ActiveQuery;

class SocialAccountQuery extends ActiveQuery
{
    public function daRede($social) {

        $this->andWhere('social = :social', [':social' => $social]);
        return $this;
    }
}
