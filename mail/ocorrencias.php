<?php
use app\helpers\models\MunicipioHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Configuracao;
use app\models\Ocorrencia;

$diaAnterior = date('Y-m-d',strtotime("-1 days"));

$qtdeDiasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, $cliente->id);
$qtdeDiasVermelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, $cliente->id);

?>

<div style="text-align: center;">
    <?php //echo MunicipioHelper::getBrasaoAsImageTag($cliente->municipio, 'small'); ?>
</div>

<div style="text-align: center;">
    <p style="padding: 0; margin: 1em 0 0.3em 0;">SECRETARIA MUNICIPAL DE SAÚDE</p>
</div>

<br />

<p style="font-size: 1.3em; font-weight: bold;">Denuncias em <?= date('Y'); ?></p>

<table style="border-left: 1px solid #ccc; padding-left: .5em;">

    <tr>
        <td><strong>Total de denúncias registradas</strong>:</td>
        <td><?= $resumo->getTotalDenunciasRecebidas(); ?></td>
    </tr>

    <tr>
        <td><strong>Total de denúncias pendentes</strong>:</td>
        <td><?= $resumo->getTotalDenunciasPendentes(); ?></td>
    </tr>

    <tr>
        <td><strong>Tempo médio de atendimento</strong>:</td>
        <td><?= $resumo->getTempoAtendimentoMedio(); ?></td>
    </tr>

</table>

<br />

<p style="font-size: 1.3em; font-weight: bold;">Denuncias em <?= date('d/m/Y',strtotime("-1 days")); ?></p>
<table style="border-left: 1px solid #ccc; padding-left: .5em;">

    <tr>
        <td><strong>Denuncias abertas</strong>:</td>
        <td><?= $resumo->getTotalDenunciasAbertasDia($diaAnterior); ?></td>
    </tr>

    <tr>
        <td><strong>Denúncias fechadas</strong>:</td>
        <td><?= $resumo->getTotalDenunciasFechadasDia($diaAnterior); ?></td>
    </tr>

</table>
<br />

<p style="font-size: 1.3em; font-weight: bold;">Denuncias pendentes</p>
<table style="border-left: 1px solid #ccc; padding-left: .5em;">

    <tr>
        <td><strong>Denuncias abertas até 8 dias</strong>:</td>
        <td><?= Ocorrencia::find()->aberta()->anteriorA($qtdeDiasVerde)->count(); ?></td>
    </tr>

    <tr>
        <td><strong>Denúncias abertas entre 8 e 15 dias</strong>:</td>
        <td><?= Ocorrencia::find()->aberta()->entre($qtdeDiasVerde, $qtdeDiasVermelho)->count(); ?></td>
    </tr>

    <tr>
        <td><strong>Denúncias abertas há mais de 15 dias</strong>:</td>
        <td><?= Ocorrencia::find()->aberta()->posteriorA($qtdeDiasVermelho)->count(); ?></td>
    </tr>

</table>

<p style="font-size: 1.3em; font-weight: bold;">Links relacionados</p>
<ul>
    <li>
        <?= Html::a(Html::encode("Acesse o dashboard de ocorrências"), Url::to(['/site/resumo-ocorrencias'], true)); ?>
    </li>

    <li>
        <?= Html::a(Html::encode("Acesse indicadores de ocorrências por mês"), Url::to(['/ocorrencia/indicador/ocorrencias-mes'], true)); ?>
    </li>

    <li>
        <?= Html::a(Html::encode("Acesse indicadores de ocorrências por situação"), Url::to(['/ocorrencia/indicador/ocorrencias-status'], true)); ?>
    </li>

    <li>
        <?= Html::a(Html::encode("Acesse indicadores de ocorrências por tipo de problema"), Url::to(['/ocorrencia/indicador/ocorrencias-problema'], true)); ?>
    </li>
</ul>
