<?php 
namespace app\jobs;

use Yii;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\FocoTransmissor;
use app\models\Cliente;

class RefreshFocosJob implements AbstractJob
{
    public function run($params = []) 
    { 
        FocoTransmissorRedis::deleteAll();
        
        $clientes = Cliente::find()->all(); 
        foreach($clientes as $cliente) {

            $focos = FocoTransmissor::find()
                ->select(['distinct on (especie_transmissor_id, imovel_id, bairro_quarteirao_id) focos_transmissores.*'])
                ->doCliente($cliente->id)
                ->ativo()
                ->all();

            foreach($focos as $foco) {

                $quarteirao = $foco->bairroQuarteirao;

                $quarteirao->loadCoordenadas();
                if(!$quarteirao->coordenadas) {
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

               	$focoRedis->informacao_publica = $foco->isInformacaoPublica() ? '1' : '0';

                $focoRedis->save();
            }     
        }
    }
}