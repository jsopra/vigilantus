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
        \perspectivain\gearman\BackgroundJob::register(
            'RefreshAreaTratamentoJob',
            ['key' => getenv('GEARMAN_JOB_KEY')],
            \perspectivain\gearman\BackgroundJob::NORMAL,
            \Yii::$app->params['gearmanQueueName']
        );
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFocos()
    {
        \perspectivain\gearman\BackgroundJob::register(
            'RefreshFocosJob',
            ['key' => getenv('GEARMAN_JOB_KEY')],
            \perspectivain\gearman\BackgroundJob::NORMAL,
            \Yii::$app->params['gearmanQueueName']
        );
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFechamentoRg()
    {
        \perspectivain\gearman\BackgroundJob::register(
            'RefreshResumoFechamentoRgJob',
            ['key' => getenv('GEARMAN_JOB_KEY')],
            \perspectivain\gearman\BackgroundJob::NORMAL,
            \Yii::$app->params['gearmanQueueName']
        );
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionUpdateUltimoFocoQuarteirao()
    {
        \perspectivain\gearman\BackgroundJob::register(
            'UpdateUltimoFocoQuarteiraoJob',
            ['key' => getenv('GEARMAN_JOB_KEY')],
            \perspectivain\gearman\BackgroundJob::NORMAL,
            \Yii::$app->params['gearmanQueueName']
        );
        return Controller::EXIT_CODE_NORMAL;
    }
}
