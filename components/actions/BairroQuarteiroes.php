<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use yii\helpers\Json;
use app\models\BairroQuarteirao;

class BairroQuarteiroes extends Action
{
    public function run()
    {
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;
        $onlyName = isset($_REQUEST['onlyName']) && $_REQUEST['onlyName'] == 'true';

        if(!is_numeric($bairroID))
            exit;

		$oBairro = Bairro::findOne(intval($bairroID));
        if(!$oBairro instanceof Bairro)
            exit;

        $array = [];

        $quarteiroes = BairroQuarteirao::find()->doBairro($oBairro->id)->orderBy("numero_quarteirao ASC")->all();
        foreach($quarteiroes as $quarteirao)
            if(!$onlyName)
                $array[$quarteirao->id] = $quarteirao->numero_sequencia;
            else
                $array[] = (string) $quarteirao->numero_sequencia;

		echo Json::encode($array);
    }
}
