<?php

namespace app\components;

use yii\db\ActiveRecord as YiiActiveRecord;
use yii\validators\Validator;

class ActiveRecord extends YiiActiveRecord
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        Validator::$builtInValidators['unique'] = 'app\validators\UniqueValidator';

        parent::__construct($config);
    }
}
