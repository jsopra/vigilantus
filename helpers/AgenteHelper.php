<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\Equipe;
use app\models\Agente;

class AgenteHelper extends YiiStringHelper
{
    /**
     * @return array
     */
    public static function getPorEquipe()
    {
        $data = [];

        $equipes = Equipe::find()->all();
        foreach($equipes as $equipe) {

            $agentes = $equipe->agentes;
            foreach($agentes as $agente) {
                $data[$equipe->nome][$agente->id] = $agente->nome;
            }
        }

        return $data;
    }
}
