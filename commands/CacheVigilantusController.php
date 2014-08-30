<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Municipio;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;

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
        $municipios = \app\models\Municipio::find()->all(); //fix quando tratar de usuario
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
}