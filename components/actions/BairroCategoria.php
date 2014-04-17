<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Bairro;
use yii\helpers\Json;

class BairroCategoria extends Action
{
    public function run()
    {
        $bairroID = isset($_REQUEST['bairro_id']) ? $_REQUEST['bairro_id'] : null;
        
        if(!is_numeric($bairroID))
            exit;
        
		$oBairro = Bairro::findOne(intval($bairroID));
        if(!$oBairro instanceof Bairro)
            exit;

		echo Json::encode(['id' => $oBairro->categoria->id , 'descricao' => $oBairro->categoria->nome]);
    }
}