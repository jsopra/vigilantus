<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Municipio;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\BoletimRgFechamento;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\redis\FechamentoRg as FechamentoRgRedis;
use app\models\FocoTransmissor;

class CacheVigilantusController extends Controller
{

    public function actionIndex()
    {
        return Controller::EXIT_CODE_NORMAL;
    }
    
    /**
     * Limpa cache de areas de tratamento
     * @return int 
     */
    public function actionRefreshAreaTratamento() 
    {
        $municipios = \app\models\Municipio::find()->all(); 
        foreach($municipios as $municipio) {
            
            $especiesTransmissor = [null] + EspecieTransmissor::find()->doMunicipio($municipio->id)->all();
            
            foreach($especiesTransmissor as $especie) {

                $tiposLira = [null, true, false];
                
                foreach($tiposLira as $lira) {
                
                    //limpa
                    $cacheKey = 'quarteiroes_area_tratamento_' . $municipio->id;
                    if($especie) {
                        $cacheKey .= '_especie_' . $especie->id;
                    }
                    
                    if($lira !== null) {
                        $cacheKey .= '_lira_' . ($lira === true ? 'true' : 'false');
                    }

                    Yii::$app->cache->set($cacheKey, false);

                    //recria
                    BairroQuarteirao::getIDsAreaTratamento($municipio->id, ($especie ? $especie->id : null), $lira);
                }
            }
        }
        
        return Controller::EXIT_CODE_NORMAL;
    }
    
    public function actionGenerateFocos()
    {

        FocoTransmissorRedis::deleteAll();
        
        $municipios = \app\models\Municipio::find()->all(); 
        foreach($municipios as $municipio) {
        
            $focos = FocoTransmissor::find()
                ->distinct()
                ->select('especie_transmissor_id, imovel_id, bairro_quarteirao_id')
                ->doMunicipio($municipio->id)
                ->all();

            foreach($focos as $foco) {

                $quarteirao = $foco->bairroQuarteirao;

                $quarteirao->loadCoordenadas();
                if(!$quarteirao->coordenadas) {
                    continue;
                }

                $focoRedis = new FocoTransmissorRedis;

                $focoRedis->municipio_id = $municipio->id;
                $focoRedis->bairro_quarteirao_id =  $quarteirao->id;
                $focoRedis->bairro_id = $quarteirao->bairro_id;
                $focoRedis->imovel_lira = ($foco->imovel ? ($foco->imovel->imovel_lira) : null);
                $focoRedis->setQuarteiraoCoordenadas($quarteirao->coordenadas);
                $focoRedis->especie_transmissor_id =  $foco->especie_transmissor_id;
                $focoRedis->cor_foco =  $foco->especieTransmissor->cor;
                $focoRedis->setCentroQuarteirao($quarteirao->getCentro());
                $focoRedis->qtde_metros_area_foco = $foco->especieTransmissor->qtde_metros_area_foco;
                $focoRedis->save();
            }     
            
        }
        
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFechamentoRg()
    {
        \app\models\redis\Queue::push('RefreshFechamentoRgJob'); 
        return Controller::EXIT_CODE_NORMAL;
    }
}