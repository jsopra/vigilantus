<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Municipio;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\redis\FocoAtivo;
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

        FocoAtivo::deleteAll();
        
        $municipios = \app\models\Municipio::find()->all(); 
        foreach($municipios as $municipio) {
        
            $focos = FocoTransmissor::find()->doMunicipio($municipio->id)->ativo()->all();
            foreach($focos as $foco) {

                $quarteirao = $foco->bairroQuarteirao;

                $quarteirao->loadCoordenadas();
                if(!$quarteirao->coordenadas) {
                    continue;
                }

                $focoAtivo = new FocoAtivo;

                $focoAtivo->id =  $foco->id;
                $focoAtivo->municipio_id = $municipio->id;
                $focoAtivo->bairro_quarteirao_id =  $quarteirao->id;
                $focoAtivo->bairro_id = $quarteirao->bairro_id;
                $focoAtivo->imovel_lira = ($foco->imovel ? ($foco->imovel->imovel_lira) : null);
                $focoAtivo->setQuarteiraoCoordenadas($quarteirao->coordenadas);
                $focoAtivo->especie_transmissor_id =  $foco->especie_transmissor_id;
                $focoAtivo->cor_foco =  $foco->especieTransmissor->cor;
                $focoAtivo->setCentroQuarteirao($quarteirao->getCentro());
                $focoAtivo->qtde_metros_area_foco = $foco->especieTransmissor->qtde_metros_area_foco;
                $focoAtivo->save();
            }     
            
        }
        
        return Controller::EXIT_CODE_NORMAL;
    }
}