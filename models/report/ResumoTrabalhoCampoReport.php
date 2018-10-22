<?php

namespace app\models\report;
use app\models\EquipeAgente;
use app\models\SemanaEpidemiologicaVisita;
use app\models\VisitaTipo;
use app\models\VisitaPendencia;
use app\models\VisitaStatus;
use app\models\VisitaTipoLarvicida;
use app\models\VisitaTipoAdulticida;
use yii\base\Model;


class ResumoTrabalhoCampoReport extends Model
{
    public $agente_id;
    public $semana_id;


    public function rules()
    {
        return [
            [['agente_id', 'semana_id'], 'required'],
            ['agente_id', 'exist', 'targetClass' => EquipeAgente::className(), 'targetAttribute' => 'id'],
            ['semana_id', 'exist', 'targetClass' => SemanaEpidemiologica::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'agente_id' => 'Agente',
            'semana_id' => 'Semana Epidemiológica',
        ];
    }

    public function getData()
    {
        $visitasAgente = SemanaEpidemiologicaVisita::find()
            ->doAgente($this->agente_id)
            ->daSemanaEpidemiologica($this->semana_id)
            ->all();

        if (count($visitasAgente) == 0) {
            return null;
        }

        $data = [
            '_resumoTrabalho' => [
                'label' => 'Trabalho do Campo',
                'data' => [
                    'imoveis_por_tipo' => [
                        'Total' => 0,
                    ],
                    'imoveis' => [
                        'Tratamento Focal' => 0,
                        'Tratamento Perifocal' => 0,
                        'Inspecionados' => 0,
                        'Recuperados' => 0,
                    ],
                    'tubitos' => 0,
                    'pendencias' => [],
                    'depositos_inspecionados' => [
                        'Total' => 0,
                    ],
                    'depositos_tratamento' => [
                        'Eliminados' => 0,
                    ],
                    'adulticida' => [],
                    'quarteiroes_trabalhados' => [],
                    'quarteiroes_concluidos' => [],
                ],
            ],
            '_resumoLaboratorio' => [
                'label' => 'Trabalho do Laboratório',
                'data' => [
                    /*
                    'aegypti' => [],
                    'albopictus' => [],
                    'depositos_com_especimes' => [],
                    'imoveis_com_especimes' => [],
                    'numero_exemplares' => [],
                    */
                ],
            ],
        ];

        foreach ($visitasAgente as $visita) {

            /* quarteiroes_trabalhados */
            $descricaoQuarteirao = $visita->quarteirao->numero_quarteirao;
            if (!in_array($descricaoQuarteirao, $data['_resumoTrabalho']['data']['quarteiroes_trabalhados'])) {
                $data['_resumoTrabalho']['data']['quarteiroes_trabalhados'][] = $descricaoQuarteirao;
            }

            /* quarteiroes_concluidos */
            if ($visita->visita_status_id == VisitaStatus::CONCLUIDA) {
                if (!in_array($descricaoQuarteirao, $data['_resumoTrabalho']['data']['quarteiroes_trabalhados'])) {
                    $data['_resumoTrabalho']['data']['quarteiroes_concluidos'][] = $descricaoQuarteirao;
                }
            }

            foreach ($visita->visitaImoveis as $imovel) {

                /* imoveis_por_tipo */
                $descricaoImovel = $imovel->tipoImovel->nome;
                if (!isset($data['_resumoTrabalho']['data']['imoveis_por_tipo'][$descricaoImovel])) {
                    $data['_resumoTrabalho']['data']['imoveis_por_tipo'][$descricaoImovel] = 0;
                }
                $data['_resumoTrabalho']['data']['imoveis_por_tipo'][$descricaoImovel] += 1;
                $data['_resumoTrabalho']['data']['imoveis_por_tipo']['Total'] += 1;

                /* imoveis */
                $data['_resumoTrabalho']['data']['imoveis']['Inspecionados'] += 1;
                if ($imovel->visita_tipo == VisitaTipo::RECUPERADA) {
                    $data['_resumoTrabalho']['data']['imoveis']['Recuperados'] += 1;
                }

                /* tubitos */
                $data['_resumoTrabalho']['data']['tubitos'] += $imovel->quantidade_tubitos ? $imovel->quantidade_tubitos : 0;

                /* pendencia */
                if ($imovel->pendencia) {
                    $descricaoPendencia = VisitaPendencia::getDescricao($imovel->pendencia);
                    if (!isset($data['_resumoTrabalho']['data']['pendencias'][$descricaoPendencia])) {
                        $data['_resumoTrabalho']['data']['pendencias'][$descricaoPendencia] = 0;
                    }
                    $data['_resumoTrabalho']['data']['pendencias'][$descricaoPendencia] += 1;
                }

                /* depositos_tratamento */
                $data['_resumoTrabalho']['data']['depositos_tratamento']['Eliminados'] += $imovel->depositos_eliminados;

                foreach ($imovel->visitaImovelDepositos as $deposito) {

                    /* depositos_inspecionados */
                    $descricaoDeposito = $deposito->tipoDeposito->sigla;
                    if (!isset($data['_resumoTrabalho']['data']['depositos_inspecionados'][$descricaoDeposito])) {
                        $data['_resumoTrabalho']['data']['depositos_inspecionados'][$descricaoDeposito] = 0;
                    }
                    $data['_resumoTrabalho']['data']['depositos_inspecionados'][$descricaoDeposito] += 1;
                    $data['_resumoTrabalho']['data']['depositos_inspecionados']['Total'] += 1;

                }

                foreach ($imovel->visitaImovelTratamentos as $tratamento) {

                    /* imoveis */
                    if ($tratamento->focal_larvicida_tipo != null) {
                        $data['_resumoTrabalho']['data']['imoveis']['Tratamento Focal'] += 1;

                        /* depositos_tratamento */
                        $descricaoTratamento = VisitaTipoLarvicida::getDescricao($tratamento->focal_larvicida_tipo);
                        if (!isset($data['_resumoTrabalho']['data']['depositos_tratamento'][$descricaoTratamento])) {
                            $data['_resumoTrabalho']['data']['depositos_tratamento'][$descricaoTratamento] = [
                                'Qtde. (gramas)' => 0,
                                'Qtde. dep. trat.' => 0,
                            ];
                        }
                        $data['_resumoTrabalho']['data']['depositos_tratamento'][$descricaoTratamento]['Qtde. (gramas)'] += 1;
                        $data['_resumoTrabalho']['data']['depositos_tratamento'][$descricaoTratamento]['Qtde. dep. trat.'] += 1;

                    }
                    if ($tratamento->perifocal_adulticida_tipo != null) {
                        $data['_resumoTrabalho']['data']['imoveis']['Tratamento Perifocal'] += 1;

                        /* adulticida */
                        if ($tratamento->perifocal_adulticida_qtde_cargas) {
                            $descricaoAdulticida = VisitaTipoAdulticida::getDescricao($tratamento->tipoImovel->perifocal_adulticida_tipo);
                            if (!isset($data['_resumoTrabalho']['data']['adulticida'][$descricaoAdulticida])) {
                                $data['_resumoTrabalho']['data']['adulticida'][$descricaoAdulticida] = 0;
                            }
                            $data['_resumoTrabalho']['data']['adulticida'][$descricaoAdulticida] += $tratamento->perifocal_adulticida_qtde_cargas;
                        }
                    }

                }
            }

        }

        return $data;
    }
}
