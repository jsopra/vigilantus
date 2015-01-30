<?php
namespace app\commands;

use Yii;
use app\components\Console;
use app\models\Cliente;
use yii\console\Controller;

class CacheVigilantusController extends Console
{
    public function actionIndex()
    {
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionRefreshAreaTratamento()
    {
        \app\models\redis\Queue::push('RefreshAreaTratamentoJob');
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFocos()
    {
        \app\models\redis\Queue::push('RefreshFocosJob');
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFechamentoRg()
    {
        \app\models\redis\Queue::push('RefreshResumoFechamentoRgJob');
        return Controller::EXIT_CODE_NORMAL;
    }
}
