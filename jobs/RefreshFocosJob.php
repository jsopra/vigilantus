<?php
namespace app\jobs;

use Yii;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\FocoTransmissor;
use app\models\Cliente;

class RefreshFocosJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        FocoTransmissorRedis::deleteAll();

        Yii::$app->cache->set('ultima_atualizacao_resumo_focos', null, (60*60*24*7*4));

        $clientes = Cliente::find()->ativo()->all();
        foreach ($clientes as $cliente) {

            foreach (\app\models\FocoTransmissor::find()
                ->select(['distinct on (especie_transmissor_id, imovel_id, bairro_quarteirao_id) focos_transmissores.*'])
                ->doCliente($cliente->id)
                ->ativo()
                ->each(10) as $foco
            ) {

                $quarteirao = $foco->bairroQuarteirao;

                $quarteirao->loadCoordenadas();
                if (!$quarteirao->coordenadas) {
                    continue;
                }

                $focoRedis = new FocoTransmissorRedis;

                $focoRedis->cliente_id = $cliente->id;
                $focoRedis->bairro_quarteirao_id =  $quarteirao->id;
                $focoRedis->bairro_id = $quarteirao->bairro_id;
                $focoRedis->imovel_lira = ($foco->imovel ? ($foco->imovel->imovel_lira) : null);
                $focoRedis->setQuarteiraoCoordenadas($quarteirao->coordenadas);
                $focoRedis->especie_transmissor_id =  $foco->especie_transmissor_id;
                $focoRedis->cor_foco =  $foco->especieTransmissor->cor;
                $focoRedis->setCentroQuarteirao($quarteirao->getCentro());
                $focoRedis->qtde_metros_area_foco = $foco->especieTransmissor->qtde_metros_area_foco;
                $focoRedis->timestamp_entrada = Yii::$app->formatter->asTimestamp($foco->data_entrada);

               	$focoRedis->informacao_publica = $foco->isInformacaoPublica() ? '1' : '0';

                $focoRedis->save();
            }
        }

        Yii::$app->cache->set('ultima_atualizacao_resumo_focos', date('d/m/Y H:i:s'), (60*60*24*7*4));

        return true;
    }
}
