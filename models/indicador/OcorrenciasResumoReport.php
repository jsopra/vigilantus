<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\OcorrenciaTipoProblema;
use app\models\OcorrenciaStatus;
use Yii;

class OcorrenciasResumoReport extends Model
{
    public $ano;

    public function rules()
    {
        return [
            ['ano', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
        ];
    }

    public function getTotalDenunciasRecebidas()
    {
        return Ocorrencia::find()->criadaNoAno($this->ano)->count();
    }

    public function getTotalDenunciasFinalizadas()
    {
        return Ocorrencia::find()->criadaNoAno($this->ano)->fechada()->count();
    }

    public function getTotalDenunciasPendentes()
    {
        return Ocorrencia::find()->criadaNoAno($this->ano)->aberta()->count();
    }

    public function getTempoAtendimentoMedio()
    {
        $ocorrencias = Ocorrencia::find()->criadaNoAno($this->ano)->all();
        $qtdeOcorrencias = count($ocorrencias);
        $tempoTotal = 0;

        foreach($ocorrencias as $ocorrencia) {
            $tempoTotal += $ocorrencia->qtde_dias_em_aberto;
        }

        return $qtdeOcorrencias > 0 ? round(($tempoTotal / $qtdeOcorrencias), 2) : 0;
    }

    public function getTotalDenunciasAbertasDia($data)
    {
        return Ocorrencia::find()->criadaNoDia($data)->aberta()->count();
    }

    public function getTotalDenunciasFechadasDia($data)
    {
        return Ocorrencia::find()->fechadaNoDia($data)->fechada()->count();
    }
}
