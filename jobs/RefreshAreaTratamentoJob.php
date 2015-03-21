<?php
namespace app\jobs;

use Yii;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\Cliente;

class RefreshAreaTratamentoJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if(!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        $clientes = Cliente::find()->all();
        foreach($clientes as $cliente) {

            $especiesTransmissor = [null] + EspecieTransmissor::find()->doCliente($cliente->id)->all();

            foreach($especiesTransmissor as $especie) {

                $tiposLira = [null, true, false];

                foreach($tiposLira as $lira) {

                    //limpa
                    $cacheKey = 'quarteiroes_area_tratamento_' . $cliente->id;
                    if($especie) {
                        $cacheKey .= '_especie_' . $especie->id;
                    }

                    if($lira !== null) {
                        $cacheKey .= '_lira_' . ($lira === true ? 'true' : 'false');
                    }

                    Yii::$app->cache->set($cacheKey, false);

                    //recria
                    BairroQuarteirao::getIDsAreaTratamento($cliente->id, ($especie ? $especie->id : null), $lira);
                }
            }
        }

        return true;
    }
}
