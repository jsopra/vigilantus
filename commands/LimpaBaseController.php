<?php
namespace app\commands;

use Yii;
use app\components\Console;
use app\models\Bairro;
use app\models\BoletimRg;
use yii\console\Controller;

class LimpaBaseController extends Console
{
    public function actionIndex()
    {
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionRgBairro($idbairro)
    {
        $bairro = Bairro::find()->andWhere(['id' => $idbairro])->one();
        if(!$bairro) {
            return Controller::EXIT_CODE_NORMAL;
        }

        $boletinsRg = BoletimRg::find()->doBairro($bairro->id)->all();
        foreach($boletinsRg as $boletim) {
            $boletim->delete();
        }

        return Controller::EXIT_CODE_NORMAL;
    }
}
