<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use yii\helpers\Json;
use Yii;
use yii\web\HttpException;

class BairroCategoria extends Action
{
    public function run()
    {
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;

        if (!is_numeric($bairroID)) {
            Yii::error('Parâmetro bairro_id inválido', __METHOD__);
            throw new HttpException(400, 'Parâmetro bairro_id inválido');
        }

        $oBairro = Bairro::findOne((int) $bairroID);
        if (!$oBairro instanceof Bairro) {
            Yii::error('Bairro não encontrado: ' . $bairroID, __METHOD__);
            throw new HttpException(404, 'Bairro não encontrado');
        }

        echo Json::encode(['id' => $oBairro->categoria->id, 'descricao' => $oBairro->categoria->nome]);
    }
}