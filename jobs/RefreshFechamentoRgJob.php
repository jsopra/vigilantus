<?php
namespace app\jobs;

use Yii;
use app\models\redis\FechamentoRg as FechamentoRgRedis;
use app\models\BoletimRgFechamento;
use app\models\Cliente;

class RefreshFechamentoRgJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        FechamentoRgRedis::deleteAll();

        Yii::$app->cache->set('ultima_atualizacao_cache_rg', null, (60*60*24*7*4));

        foreach (\app\models\Cliente::find()->ativo()->all() as $cliente) {

            $query = BoletimRgFechamento::find()->doCliente($cliente->id)->doTipoLira(false);

            $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
            $query->andWhere('
                boletins_rg.data = (
                    SELECT MAX(data)
                    FROM boletins_rg brg
                    WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                )
            ');

            foreach ($query->each(10) as $boletimFechamento) {

                $fechamento = new FechamentoRgRedis;
                $fechamento->cliente_id = $cliente->id;
                $fechamento->bairro_quarteirao_id =  $boletimFechamento->boletimRg->bairro_quarteirao_id;
                $fechamento->bairro_id = $boletimFechamento->boletimRg->bairro_id;
                $fechamento->lira = $boletimFechamento->imovel_lira == true ? '1' : '0';
                $fechamento->boletim_rg_id =  $boletimFechamento->boletimRg->id;
                $fechamento->data = $boletimFechamento->boletimRg->data;
                $fechamento->quantidade = $boletimFechamento->quantidade;
                $fechamento->imovel_tipo_id = $boletimFechamento->imovel_tipo_id;
                $fechamento->quantidade_foco = $boletimFechamento->boletimRg->quarteirao->data_ultimo_foco ? $boletimFechamento->quantidade : 0;
                $fechamento->save();
            }

        }

        Yii::$app->cache->set('ultima_atualizacao_cache_rg', date('d/m/Y H:i:s'), (60*60*24*7*4));

        return true;
    }
}
