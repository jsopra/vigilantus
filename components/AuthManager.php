<?php
namespace app\components;

use Yii;
use yii\rbac\PhpManager;
use yii\rbac\Item;

class AuthManager extends PhpManager
{
    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest) {
            $this->assign($this->getItem('Anonimo'), Yii::$app->user->id);
            return;
        }

        $role = Yii::$app->user->identity->getRBACRole();
        //die(var_dump($role));
        $this->assign($this->getItem($role), Yii::$app->user->identity->id);
    }

    /**
     * Does NOT save
     */
    protected function saveToFile($data, $file)
    {
        return null;
    }
}
