<?php
namespace app\commands;

use Yii;
use app\components\Console;
use app\models\Cliente;
use yii\console\Controller;
use app\models\mail\OcorrenciasMail;

class MailController extends Console
{
    public function actionIndex()
    {
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionOcorrencias()
    {
        $form = new OcorrenciasMail;

        $form->send();

        return Controller::EXIT_CODE_NORMAL;
    }
}
