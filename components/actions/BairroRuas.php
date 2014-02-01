<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use yii\helpers\Json;

class BairroRuas extends Action
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
        
        $ruas = $oBairro->ruas;
      
        foreach($ruas as $rua)
            if(!$onlyName)
                $array[$rua->id] = $rua->nome;
            else
                $array[] = (string) $rua->nome;

		echo Json::encode($array);
    }
}