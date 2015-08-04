<?php
namespace app\models\batch;

use Yii;
use app\batch\Model;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\Ocorrencia as OcorrenciaModel;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaTipoProblema;
use app\forms\TentativaAveriguacaoForm;
use app\models\EquipeAgente;
use app\models\Equipe;
use \IntlDateFormatter;

class Ocorrencia extends Model
{
    /**
     * @inheritdoc
     */
    public function columnLabels()
    {
        return [
            'id' => 'Número da Ocorrência',
            'data_abertura' => 'Data da Abertura',
            'agente' => 'Agente Designado',
            'data_averiguacao' => 'Data da Averiguação',
            'acao_tomada' => 'Ação tomada',
            'classificacao' => 'Classificação',
            'bairro' => 'Bairro',
            'rua' => 'Rua',
            'numero' => 'Número',
        ];
    }

    /**
     * @inheritdoc
     */
    public function columnHints()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function insert($row, $userId = null, $clienteId = null)
    {
        if($row->getValue('id') != '' && OcorrenciaModel::find()->doNumeroControle($row->getValue('id'))->count() > 0) {
            $row->addError('ID já existe');
            return false;
        }

        $formatter = new IntlDateFormatter(
            \Yii::$app->language,
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE
        );

        //@temp
        /*
        if($row->getValue('bairro') == '') {
            return true;
        }
        */

        $bairro = $row->getValue('bairro') != '' ? Bairro::find()->doNome($row->getValue('bairro'))->one() : null;
        $numero = $row->getValue('numero');
        $bairroQuarteirao = $bairro && $numero ? BairroQuarteirao::find()->doBairro($bairro->id)->doNumero($numero)->one() : null;

        //@temp
        /*
        if(!$bairro) {
            $row->addError('Não salvou Bairro');
            return false;
        }
        */

        if($row->getValue('bairro') != '' && !$bairro) {
            $row->addError('Não salvou Bairro');
            return false;
        }

        $ocorrencia = new OcorrenciaModel;
        $ocorrencia->scenario = OcorrenciaModel::SCENARIO_CARGA;

        $ocorrencia->data_criacao = $row->getValue('data_abertura') . ' 00:00:00';
        //$ocorrencia->data_criacao = date('Y-m-d', $formatter->parse($row->getValue('data_abertura'))) . ' 00:00:00';
        $ocorrencia->bairro_id = $bairro ? $bairro->id : null;
        $ocorrencia->endereco = $row->getValue('rua') . ($row->getValue('numero') != '' ? ', nº ' . $row->getValue('numero') : '') . ($row->getValue('bairro') != '' ? ' - Bairro ' . $row->getValue('bairro') : '');
        $ocorrencia->tipo_imovel = null;
        $ocorrencia->status = $this->getStatus($row->getValue('acao_tomada'));

        $tipoProblema = !empty(trim($row->getValue('classificacao'))) ? OcorrenciaTipoProblema::find()->comNome($row->getValue('classificacao'))->one() : null;
        $ocorrencia->ocorrencia_tipo_problema_id = $tipoProblema ? $tipoProblema->id : null;

        $ocorrencia->bairro_quarteirao_id = $bairroQuarteirao ? $bairroQuarteirao->id : null;
        $ocorrencia->data_fechamento = OcorrenciaStatus::isStatusTerminativo($ocorrencia->status) ? $row->getValue('data_averiguacao') . ' 00:00:00' : null;
        //$ocorrencia->data_fechamento = OcorrenciaStatus::isStatusTerminativo($ocorrencia->status) ? date('Y-m-d', $formatter->parse($row->getValue('data_averiguacao'))) . ' 00:00:00' : null;
        $ocorrencia->numero_controle = $row->getValue('id');
        $ocorrencia->mensagem = 'Via carga de importação de Ocorrências';

        $saved = $ocorrencia->save();

        if(!$saved) {
            $row->addError('Não salvou Ocorrência');
            return false;
        }

        $saved = true;

        /*
         * registro historico de visitacao quando agente e data_averiguacao
         */
        if($saved && $row->getValue('data_averiguacao') != '' && $row->getValue('agente') != '') {

            $agente = EquipeAgente::find()->doNome($row->getValue('agente'))->one();
            if(!$agente) {

                $equipe = Equipe::find()->doNome('Ocorrência')->one();
                if(!$equipe) {
                    $row->addError('Não localizou equipe para cadastrar agente');
                    return false;
                }

                $agente = new EquipeAgente;
                $agente->cliente_id = $ocorrencia->cliente_id;
                $agente->equipe_id = $equipe->id;
                $agente->nome = $row->getValue('agente');
                $agente->ativo = true;
                $agente->codigo = null;

                if(!$agente->save()) {
                    $row->addError('Não salvou Agente');
                    return false;
                }

            }

            $averiguacao = new TentativaAveriguacaoForm;
            $averiguacao->agente_id = $agente->id;
            $averiguacao->data = $row->getValue('data_averiguacao') . ' 00:00:00';
            //$averiguacao->data = date('Y-m-d', $formatter->parse($row->getValue('data_averiguacao'))) . ' 00:00:00';
            $averiguacao->observacoes = null;
            $averiguacao->ocorrencia_id = $ocorrencia->id;
            $averiguacao->cliente_id = $ocorrencia->cliente_id;
            $averiguacao->usuario_id = Yii::$app->user->id;

            if (!$averiguacao->save()) {
                $row->addError('Não salvou histórico de visitação');
                return false;
            }

        }

        return true;
    }

    private function getStatus($acaoTomada = null)
    {
        switch($acaoTomada) {

            case 'Solucionado' :
                return OcorrenciaStatus::SOLICIONADO;

            case 'Aberto TR' :
                return OcorrenciaStatus::ABERTO_TERMO_RESPONSABILIDADE;

            case 'Não procedente' :
                return OcorrenciaStatus::NAO_PROCEDENTE;

            case 'Enc. para fiscalização urbana' :
                return OcorrenciaStatus::ENCAMINHADO_FISCALIZACAO_URBANA;

            case 'Enc. para fiscalização sanitária' :
                return OcorrenciaStatus::ENCAMINHADO_FISCALIZACAO_SANITARIA;

            case 'Fechada' :
            case 'Fechado' :
                return OcorrenciaStatus::FECHADO;

            case 'Não encontrado' :
                return OcorrenciaStatus::NAO_ENCONTRADO;

            case 'Extraviada' :
            case 'Extraviado' :
            case 'Extraviada a denúncia' :
                return OcorrenciaStatus::EXTREVIADA;

            case 'Agricultura' :
                return OcorrenciaStatus::AGRICULTURA;

            default :
                return OcorrenciaStatus::APROVADA;
        }
    }
}
