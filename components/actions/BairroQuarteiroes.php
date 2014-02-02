<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use yii\helpers\Json;

class BairroQuarteiroes extends Action
{
    public function run()
    {
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;
        $onlyName = isset($_REQUEST['onlyName']) && $_REQUEST['onlyName'] == 'true';
        
        if(!is_numeric($bairroID))
            exit;
        
		$oBairro = Bairro::find(intval($bairroID));
        if(!$oBairro instanceof Bairro)
            exit;

        $array = [];
        
        $quarteiroes = $oBairro->quarteiroes;
      
        foreach($quarteiroes as $quarteirao)
            if(!$onlyName)
                $array[$quarteirao->id] = $quarteirao->numero_quarteirao;
            else
                $array[] = (string) $quarteirao->numero_quarteirao;

		echo Json::encode($array);
    }
}