<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\OcorrenciaTipoProblema;
use app\models\OcorrenciaStatus;
use Yii;

class OcorrenciasStatusReport extends Model
{
    public $ano;
    public $problema_id;

    public function rules()
    {
        return [
            ['ano', 'integer'],
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

    public function getStatus($returnIndexedArray= false)
    {
        $return = [];

        $status = OcorrenciaStatus::getDescricoes();

        if($returnIndexedArray) {
            return $status;
        }

        foreach($status as $st) {
            $return[] = $st;
        }

        return $return;
    }

    public function getSeries()
    {
        $series = [];

        foreach ($this->getStatus(true) as $id => $texto) {

            $ocorrencias = Ocorrencia::find()->criadaNoAno($this->ano)->doStatus($id);

            if($this->problema_id) {
                $ocorrencias->doProblema($this->problema_id);
            }

            $series[] = (int) $ocorrencias->count();
        }

        return $series;
    }
}
