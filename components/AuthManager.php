<?php
namespace app\components;

use Yii;
use yii\rbac\PhpManager;

class AuthManager extends PhpManager
{
    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            $this->assign(
                Yii::$app->user->identity->id,
                Yii::$app->user->identity->getRBACRole()
            );
        }
    }
}
