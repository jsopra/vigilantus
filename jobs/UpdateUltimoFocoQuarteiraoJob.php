<?php
namespace app\jobs;

use Yii;

class UpdateUltimoFocoQuarteiraoJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        \app\models\BairroQuarteirao::updateAll(['data_ultimo_foco' => null]);

        foreach (\app\models\Cliente::find()->ativo()->all() as $cliente) {

            foreach (\app\models\FocoTransmissor::find()
                ->doCliente($cliente->id)
                ->ativo()
                ->orderBy('data_entrada ASC')
                ->each(10) as $foco
            ) {

                $foco->bairroQuarteirao->data_ultimo_foco = $foco->data_entrada;
                $foco->bairroQuarteirao->setScenario('updateAttributes');
                $foco->bairroQuarteirao->save();

                foreach($foco->getAreaTratamento() as $quarteirao) {

                    $quarteirao->data_ultimo_foco = $foco->data_entrada;
                    $quarteirao->setScenario('updateAttributes');
                    $quarteirao->save();
                }
            }
        }

        return true;
    }
}
