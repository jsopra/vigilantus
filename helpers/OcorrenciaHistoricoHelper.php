<?php
namespace app\helpers;

use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaHistoricoTipo;

class OcorrenciaHistoricoHelper
{
    /**
     * HTML do badge do tipo do histórico para exibir na timeline.
     *
     * @param OcorrenciaHistorico $historico
     * @return string HTML da badge
     */
    public static function badge(OcorrenciaHistorico $historico)
    {
        if (isset(self::badges()[$historico->tipo])) {
            return self::badges()[$historico->tipo];
        }
        return '';
    }

    /**
     * Descrição do tipo do histórico para exibir na timeline.
     *
     * @param OcorrenciaHistorico $historico
     * @return string descrição
     */
    public static function descricao(OcorrenciaHistorico $historico)
    {
        if (isset(self::descricoes()[$historico->tipo])) {
            return self::descricoes()[$historico->tipo];
        }
        return '';
    }

    /**
     * Badges usados na timeline do histórico de uma ocorrência.
     * @return string[] as tags HTMLs das badges de cada tipo de histórico.
     */
    protected static function badges()
    {
        return [
            OcorrenciaHistoricoTipo::INCLUSAO => '<div class="timeline-badge"><i class="glyphicon glyphicon-check"></i></div>',
            OcorrenciaHistoricoTipo::APROVACAO => '<div class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>',
            OcorrenciaHistoricoTipo::REPROVACAO => '<div class="timeline-badge error"><i class="glyphicon glyphicon-remove"></i></div>',
            OcorrenciaHistoricoTipo::INFORMACAO => '<div class="timeline-badge info"><i class="glyphicon glyphicon-info-sign"></i></div>',
            OcorrenciaHistoricoTipo::AVERIGUACAO => '<div class="timeline-badge warning"><i class="glyphicon glyphicon-eye-open"></i></div>',
            OcorrenciaHistoricoTipo::ACAO_TOMADA => '<div class="timeline-badge warning"><i class="glyphicon glyphicon-alert"></i></div>',
             OcorrenciaHistoricoTipo::AVALIADA => '<div class="timeline-badge success"><i class="glyphicon glyphicon-star"></i></div>',
        ];
    }

    /**
     * Descrições dos tipos de históricos que aparecem na timeline.
     * @return string[] as descrições
     */
    protected static function descricoes()
    {
        return [
            OcorrenciaHistoricoTipo::INCLUSAO => 'A ocorrência foi registrada com sucesso e aguarda avaliação da Prefeitura.',
            OcorrenciaHistoricoTipo::APROVACAO => 'A ocorrência foi aprovada e encaminhada para o setor responsável.',
            OcorrenciaHistoricoTipo::REPROVACAO => 'Infelizmente a ocorrência foi reprovada.',
            OcorrenciaHistoricoTipo::INFORMACAO => 'Houve uma atualização no status de sua ocorrência.',
            OcorrenciaHistoricoTipo::AVERIGUACAO => 'A ocorrência foi enviada para averiguação pelo setor responsável.',
            OcorrenciaHistoricoTipo::ACAO_TOMADA => 'Uma ação tomada para resolver a ocorrência. O município agradece pela sua colaboração!',
             OcorrenciaHistoricoTipo::AVALIADA => 'A ocorrência foi avalidada pelo solicitante',
        ];
    }
}
