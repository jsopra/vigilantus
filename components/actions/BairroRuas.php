<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use app\models\BairroRua;
use yii\helpers\Json;

class BairroRuas extends Action
{
    public function run()
    {
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;
        $onlyName = isset($_REQUEST['onlyName']) && $_REQUEST['onlyName'] == 'true';
        $queryString = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;
        
        if(!is_numeric($bairroID))
            exit;
        
		$oBairro = Bairro::find(intval($bairroID));
        if(!$oBairro instanceof Bairro)
            exit;

        $array = [];

        $query = BairroRua::find();
        $query->andWhere(['"bairro_id"' => $oBairro->id]);
  
        if($queryString)
            $query->andWhere('nome ILIKE \'%' . $queryString . '%\'');
        
        $ruas = $query->all();

        foreach($ruas as $rua)
            if(!$onlyName)
                $array[$rua->id] = $rua->nome;
            else
                $array[] = (string) $rua->nome;

		echo Json::encode($array);
    }
}