<?php
namespace app\jobs;

use Yii;
use app\models\redis\ResumoBairroFechamentoRg;
use app\models\redis\ResumoImovelFechamentoRg;
use app\models\BoletimRgFechamento;
use app\models\Cliente;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\ImovelTipo;

class RefreshResumoFechamentoRgJob implements AbstractJob
{
    public function run($params = [])
    {
        ResumoBairroFechamentoRg::deleteAll();
        ResumoImovelFechamentoRg::deleteAll();

        Yii::$app->cache->set('ultima_atualizacao_resumo_cache_rg', null, (60*60*24*7*4));

        foreach(Cliente::find()->each(10) as $cliente) {

            foreach(Bairro::find()->doCliente($cliente->id)->each(10) as $bairro) {

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

                $queryQuantidadeFoco = clone $query;
                $queryQuantidadeFoco->innerJoin('bairro_quarteiroes', 'bairro_quarteiroes.id=boletins_rg.bairro_quarteirao_id');
                $queryQuantidadeFoco->andWhere('bairro_quarteiroes.data_ultimo_foco is not null');

                $resumoFechamento = new ResumoBairroFechamentoRg;
                $resumoFechamento->cliente_id = $cliente->id;
                $resumoFechamento->bairro_id = $bairro->id;

                $resumoFechamento->quantidade = $query->sum('quantidade');
                if(!$resumoFechamento->quantidade) {
                    $resumoFechamento->quantidade = 0;
                }

                $resumoFechamento->quantidade_foco = $queryQuantidadeFoco->sum('quantidade');
                if(!$resumoFechamento->quantidade_foco) {
                    $resumoFechamento->quantidade_foco = 0;
                }

                $resumoFechamento->save();
            }

            //imoveis
            foreach(ImovelTipo::find()->ativo()->doCliente($cliente->id)->each(10) as $tipo) {

                $query = BoletimRgFechamento::find()->doCliente($cliente->id)->doTipoLira(false)->doTipoDeImovel($tipo->id);

                $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
                $query->andWhere('
                    boletins_rg.data = (
                        SELECT MAX(data)
                        FROM boletins_rg brg
                        WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                    )
                ');

                $queryQuantidadeFoco = clone $query;
                $queryQuantidadeFoco->innerJoin('bairro_quarteiroes', 'bairro_quarteiroes.id=boletins_rg.bairro_quarteirao_id');
                $queryQuantidadeFoco->andWhere('bairro_quarteiroes.data_ultimo_foco is not null');

                $resumoFechamento = new ResumoImovelFechamentoRg;
                $resumoFechamento->cliente_id = $cliente->id;
                $resumoFechamento->imovel_tipo_id = $tipo->id;

                $resumoFechamento->quantidade = $query->sum('quantidade');
                if(!$resumoFechamento->quantidade) {
                    $resumoFechamento->quantidade = 0;
                }

                $resumoFechamento->quantidade_foco = $queryQuantidadeFoco->sum('quantidade');
                if(!$resumoFechamento->quantidade_foco) {
                    $resumoFechamento->quantidade_foco = 0;
                }

                $resumoFechamento->save();
            }
        }

        Yii::$app->cache->set('ultima_atualizacao_resumo_cache_rg', date('d/m/Y H:i:s'), (60*60*24*7*4));
    }
}
