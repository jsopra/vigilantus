<?php
namespace app\jobs;

use Yii;
use app\models\redis\ResumoBairroFechamentoRg;
use app\models\redis\ResumoImovelFechamentoRg;
use app\models\BoletimRgFechamento;
use app\models\Cliente;
use app\models\Bairro;
use app\models\ImovelTipo;

class RefreshResumoFechamentoRgJob implements AbstractJob
{
    public function run($params = [])
    {
        ResumoBairroFechamentoRg::deleteAll();
        ResumoImovelFechamentoRg::deleteAll();

        Yii::$app->cache->set('ultima_atualizacao_resumo_cache_rg', null, (60*60*24*7*4));

        $clientes = Cliente::find()->all();
        foreach($clientes as $cliente) {

            //bairros
            $bairros = Bairro::find()->doCliente($cliente->id)->all();
            foreach($bairros as $bairro) {

                $query = BoletimRgFechamento::find()->doCliente($cliente->id)->doTipoLira(false);

                $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
                $query->andWhere('boletins_rg.bairro_id = ' . $bairro->id);
                $query->andWhere('
                    boletins_rg.data = (
                        SELECT MAX(data)
                        FROM boletins_rg brg
                        WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                    )
                ');

                $resumoFechamento = new ResumoBairroFechamentoRg;
                $resumoFechamento->cliente_id = $cliente->id;
                $resumoFechamento->bairro_id = $bairro->id;
                $resumoFechamento->quantidade = $query->sum('quantidade');
                if(!$resumoFechamento->quantidade) {
                    $resumoFechamento->quantidade = 0;
                }

                $resumoFechamento->save();
            }

            //imoveis
            $tipos = ImovelTipo::find()->ativo()->doCliente($cliente->id)->all();
            foreach($tipos as $tipo) {

                $query = BoletimRgFechamento::find()->doCliente($cliente->id)->doTipoLira(false)->doTipoDeImovel($tipo->id);

                $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
                $query->andWhere('
                    boletins_rg.data = (
                        SELECT MAX(data)
                        FROM boletins_rg brg
                        WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                    )
                ');

                $resumoFechamento = new ResumoImovelFechamentoRg;
                $resumoFechamento->cliente_id = $cliente->id;
                $resumoFechamento->imovel_tipo_id = $tipo->id;
                $resumoFechamento->quantidade = $query->sum('quantidade');
                if(!$resumoFechamento->quantidade) {
                    $resumoFechamento->quantidade = 0;
                }

                $resumoFechamento->save();
            }
        }

        Yii::$app->cache->set('ultima_atualizacao_resumo_cache_rg', date('d/m/Y H:i:s'), (60*60*24*7*4));
    }
}
