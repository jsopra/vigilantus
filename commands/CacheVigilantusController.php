<?php
namespace app\commands;

use Yii;
use app\components\Console;
use app\models\Municipio;
use app\models\BairroQuarteirao;
use app\models\EspecieTransmissor;
use app\models\BoletimRgFechamento;
use app\models\redis\FechamentoRg as FechamentoRgRedis;
use yii\console\Controller;

class CacheVigilantusController extends Console
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
        \app\models\redis\Queue::push('RefreshFocosJob'); 
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionGenerateFechamentoRg()
    {
        \app\models\redis\Queue::push('RefreshFechamentoRgJob'); 
        return Controller::EXIT_CODE_NORMAL;
    }
}