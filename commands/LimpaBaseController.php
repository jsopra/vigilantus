<?php
namespace app\commands;

use Yii;
use app\components\Console;
use app\models\Denuncia;
use app\models\DenunciaHistorico;
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

    public function actionDenuncias($iddenuncia)
    {
        $denuncia = Denuncia::find()->andWhere(['id' => $iddenuncia])->one();
        if(!$denuncia) {
            return Controller::EXIT_CODE_NORMAL;
        }

        $historicos = $denuncia->denunciaHistoricos;
        foreach($historicos as $historico) {
            $historico->delete();
        }

        $denuncia->delete();

        return Controller::EXIT_CODE_NORMAL;
    }
}
