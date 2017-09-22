<?php

namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\OcorrenciaTipoProblema;
use Yii;

class OcorrenciasMesReport extends Model
{

    public $ano;
    public $problema_id;
    public $usuario;

    public function rules()
    {
        return [
            ['ano', 'integer'],
            ['usuario', 'safe'],
            ['problema_id', 'exist', 'targetClass' => OcorrenciaTipoProblema::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'problema_id' => 'Problema',
        ];
    }

    public function getSeries()
    {
        $series = [
            'recebidas' => [],
            'finalizadas' => [],
        ];

        foreach ($this->getMeses() as $id => $mes) {
            $recebidas = Ocorrencia::find()->criadaEm($id, $this->ano);
            $finalizadas = Ocorrencia::find()->finalizadaEm($id, $this->ano);

            if($this->problema_id) {
                $recebidas->doProblema($this->problema_id);
                $finalizadas->doProblema($this->problema_id);
            }

            if ($this->usuario) {
            $setoresDoUsuario = $this->usuario->getIdsSetores();
                if (count($setoresDoUsuario) > 0) {
                    $recebidas->andWhere("setor_id IN (" . implode(',', $setoresDoUsuario) . ")");
                    $finalizadas->andWhere("setor_id IN (" . implode(',', $setoresDoUsuario) . ")");
                }
            }

            $series['recebidas'][] = (int) $recebidas->count();
            $series['finalizadas'][] = (int) $finalizadas->count();
        }
        return $series;
    }

    public function getMeses($onlyLabel = false)
    {
        $meses = [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez'
        ];

        return !$onlyLabel ? $meses : array_values($meses);
    }
}