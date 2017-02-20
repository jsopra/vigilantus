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
    public $cliente_id;

    public function rules()
    {
        return [
            ['ano', 'integer'],
            ['cliente_id', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'cliente_id' => 'Cliente',
        ];
    }

    public function getTotalDenunciasRecebidas()
    {
        $query = Ocorrencia::find()->criadaNoAno($this->ano);

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        return $query->count();
    }

    public function getTotalDenunciasFinalizadas()
    {
        $query = Ocorrencia::find()->criadaNoAno($this->ano)->fechada();

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        return $query->count();
    }

    public function getTotalDenunciasPendentes()
    {
        $query = Ocorrencia::find()->criadaNoAno($this->ano)->aberta();

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        return $query->count();
    }

    public function getTempoAtendimentoMedio()
    {
        $query = Ocorrencia::find()->criadaNoAno($this->ano);
        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        $ocorrencias = $query->all();
        $qtdeOcorrencias = count($ocorrencias);
        $tempoTotal = 0;

        foreach($ocorrencias as $ocorrencia) {
            $tempoTotal += $ocorrencia->qtde_dias_em_aberto;
        }

        return $qtdeOcorrencias > 0 ? round(($tempoTotal / $qtdeOcorrencias), 2) : 0;
    }

    public function getTotalDenunciasAbertasDia($data)
    {
        $query = Ocorrencia::find()->criadaNoDia($data)->aberta();

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        return $query->count();
    }

    public function getTotalDenunciasFechadasDia($data)
    {
        $query = Ocorrencia::find()->fechadaNoDia($data)->fechada();

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        return $query->count();
    }

    public function getAvaliacaoMedia()
    {
        $queryTotal = Ocorrencia::find()
            ->avaliada()
            ->criadaNoAno($this->ano);

        if ($this->cliente_id) {
            $query->doCliente($this->cliente_id);
        }

        $queryRating = clone $queryTotal;

        $qtdeOcorrencias = $queryTotal->count();
        $rating = $queryRating->sum('rating');

        return $qtdeOcorrencias > 0 ? round(($rating / $qtdeOcorrencias), 2) : 0;
    }
}
