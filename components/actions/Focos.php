<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\FocoTransmissor;
use yii\helpers\Json;

class Focos extends Action
{
    public function run()
    {
        $queryString = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

        $array = [];

        $query = FocoTransmissor::find();
        $query->join = [
            ['INNER JOIN', 'bairro_quarteiroes bq', 'focos_transmissores.bairro_quarteirao_id = bq.id'],
            ['INNER JOIN', 'bairros b', 'bq.bairro_id = b.id']
        ];


        if($queryString) {
            $strFoco = "b.nome || ' - Quarteirão nº ' || bq.numero_quarteirao || ' (' || data_entrada || ')'";
            $query->andWhere($strFoco . ' ILIKE \'' . $queryString . '%\'');
        }

        if($id) {
            $query->andWhere('focos_transmissores.id = ' . $id);
        }

        $focos = $query->all();

        foreach($focos as $foco) {
            $array[] = [
                'id' => $foco->id,
                'text' => $foco->bairroQuarteirao->bairro->nome . ' - Quarteirão nº ' . $foco->bairroQuarteirao->numero_quarteirao . ' (' . $foco->getFormattedAttribute('data_entrada') . ')'
            ];
        }

        echo Json::encode($array);
    }
}
