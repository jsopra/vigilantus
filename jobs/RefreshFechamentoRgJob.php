<?php 
namespace app\jobs;

use Yii;
    use app\models\redis\FechamentoRg as FechamentoRgRedis;
use app\models\BoletimRgFechamento;

class RefreshFechamentoRgJob implements AbstractJob
{
    public function run($params = []) 
    { 
        FechamentoRgRedis::deleteAll();

        Yii::$app->cache->set('ultima_atualizacao_cache_rg', null, (60*60*24*7*4));

        $municipios = \app\models\Municipio::find()->all(); 
        foreach($municipios as $municipio) {
        
            $query = BoletimRgFechamento::find()->doTipoLira(false);
            $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
            $query->andWhere('
                boletins_rg.data = (
                    SELECT MAX(data)
                    FROM boletins_rg brg
                    WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                )
            ');

            $fechamentos = $query->all();
            foreach($fechamentos as $boletimFechamento) {

                $fechamento = new FechamentoRgRedis;

                $fechamento->municipio_id = $municipio->id;
                $fechamento->bairro_quarteirao_id =  $boletimFechamento->boletimRg->bairro_quarteirao_id;
                $fechamento->bairro_id = $boletimFechamento->boletimRg->bairro_id;
                $fechamento->lira = $boletimFechamento->imovel_lira == true ? '1' : '0';
                $fechamento->boletim_rg_id =  $boletimFechamento->boletimRg->id;
                $fechamento->data = $boletimFechamento->boletimRg->data;
                $fechamento->quantidade = $boletimFechamento->quantidade;
                $fechamento->imovel_tipo_id = $boletimFechamento->imovel_tipo_id;
                $fechamento->save();
            }     
            
        }

        Yii::$app->cache->set('ultima_atualizacao_cache_rg', date('d/m/Y H:i:s'), (60*60*24*7*4));
    }
}