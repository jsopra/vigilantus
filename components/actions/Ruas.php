<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\Rua;
use yii\helpers\Json;

class Ruas extends Action
{
    public function run()
    {
        $onlyName = isset($_REQUEST['onlyName']) && $_REQUEST['onlyName'] == 'true';
        $queryString = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;

        $array = [];

        $query = Rua::find();

        if($queryString) {
            $query->andWhere('nome ILIKE \'%' . $queryString . '%\'');
        }

        $ruas = $query->all();

        foreach($ruas as $rua) {

            if(!$onlyName) {
                $array[$rua->id] = $rua->nome;
            }
            else {
                $array[] = (string) $rua->nome;
            }
        }

		echo Json::encode($array);
    }
}
