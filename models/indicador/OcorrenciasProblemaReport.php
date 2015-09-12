<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\OcorrenciaTipoProblema;
use Yii;

class OcorrenciasProblemaReport extends Model
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

    public function getProblemas($returnObject = false)
    {
        $return = [];

        if($this->problema_id) {
            $problemas = [OcorrenciaTipoProblema::find()->where(['id' => $this->problema_id])->one()];
        } else {
            $problemas = OcorrenciaTipoProblema::find()->ativos()->all();
        }

        if($returnObject) {
            return $problemas;
        }

        foreach($problemas as $problema) {
            $return[] = $problema->nome;
        }

        if (!$this->problema_id) {
            $return[] = 'Outros';
        }

        return $return;
    }

    public function getSeries()
    {
        $series = [
            'recebidas' => [],
            'finalizadas' => [],
        ];

        foreach ($this->getProblemas(true) as $problema) {
            $series['recebidas'][] = (int) Ocorrencia::find()->criadaNoAno($this->ano)->doProblema($problema->id)->count();
            $series['finalizadas'][] = (int) Ocorrencia::find()->finalizadaNoAno($this->ano)->doProblema($problema->id)->count();
        }

        // Outros tipos
        if (!$this->problema_id) {
            $series['recebidas'][] = (int) Ocorrencia::find()->criadaNoAno($this->ano)->deProblemaDigitado()->count();
            $series['finalizadas'][] = (int) Ocorrencia::find()->finalizadaNoAno($this->ano)->deProblemaDigitado()->count();
        }

        return $series;
    }
}
