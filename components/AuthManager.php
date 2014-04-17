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
                $this->getItem(Yii::$app->user->identity->getRBACRole()),
                Yii::$app->user->identity->id
            );
        }
    }

    /**
     * Evita que salve em um arquivo
     */
    public function save()
    {
    }
}
