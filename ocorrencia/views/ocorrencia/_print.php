<?php
use app\helpers\models\MunicipioHelper;
use app\helpers\OcorrenciaHistoricoHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use app\helpers\models\ImovelHelper;
use app\models\OcorrenciaStatus;
use app\widgets\GridView;
use app\helpers\MapHelper;
use app\models\OcorrenciaHistoricoTipo;
use perspectivain\mapbox\MapBoxAPIHelper;

MapBoxAPIHelper::registerScript($this, ['fullScreen']);

$municipio = $model->cliente->municipio;
?>

<div style="width: 80%; margin: 0 10%">

    <div style="text-align: center;">
        <p style="padding: 0; margin: 1em 0 0.3em 0;">SECRETARIA MUNICIPAL DE SAÚDE</p>
        <p style="padding: 0; margin: 0.3em 0;">DEPTO. DE VIGILÂNCIA SANITÁRIA</p>
        <p style="padding: 0; margin: 1em 0 0 0; font-weight: bold;">RESUMO DE OCORRÊNCIA</p>
        <p style="padding: 0; margin: 1em 0 0 0;">Protocolo nº <strong><?= $model->protocolo; ?></strong></p>
    </div>

    <br />

    <p style="font-size: 1.3em; font-weight: bold">Objeto da ocorrência</p>

    <dl class="dl-horizontal">
        <?php if ($model->numero_controle) : ?>
        <dt><?= Html::activeLabel($model, 'numero_controle') ?></dt>
        <dd><?= Html::encode($model->numero_controle) ?></dd>
        <?php endif; ?>

        <dt><?= Html::activeLabel($model, 'bairro_id') ?></dt>
        <dd><?= $model->bairro ? Html::encode($model->bairro->nome) : null ?></dd>

        <?php if ($model->bairro_quarteirao_id) : ?>
        <dt><?= Html::activeLabel($model, 'bairro_quarteirao_id') ?></dt>
        <dd><?= $model->bairro_quarteirao_id ? Html::encode($model->bairroQuarteirao->numero_quarteirao) : null ?></dd>
        <?php endif; ?>

        <?php if ($model->imovel_id) : ?>
        <dt><?= Html::activeLabel($model, 'imovel_id') ?></dt>
        <dd><?= $model->imovel_id ? Html::encode(ImovelHelper::getEnderecoCompleto($model->imovel)) : null ?></dd>
        <?php endif; ?>

        <dt><?= Html::activeLabel($model, 'endereco') ?></dt>
        <dd><?= Html::encode($model->endereco) ?>

        <dt><?= Html::activeLabel($model, 'tipo_imovel') ?></dt>
        <dd><?= Html::encode(\app\models\OcorrenciaTipoImovel::getDescricao($model->tipo_imovel)) ?></dd>

        <dt><?= Html::activeLabel($model, 'ocorrencia_tipo_problema_id') ?></dt>
        <dd><?= Html::encode($model->getDescricaoTipoProblema()) ?></dd>

        <dt><?= Html::activeLabel($model, 'tipo_registro') ?></dt>
        <dd><?= Html::encode(\app\models\Ocorrencia::getTiposRegistros()[$model->tipo_registro]) ?></dd>

        <?php if ($model->pontos_referencia) : ?>
        <dt><?= Html::activeLabel($model, 'pontos_referencia') ?></dt>
        <dd><?= Html::encode($model->pontos_referencia) ?></dd>
        <?php endif; ?>

        <dt><?= Html::activeLabel($model, 'mensagem') ?></dt>
        <dd><?= Html::encode($model->mensagem) ?></dd>
    </dl>

    <?php if($model->nome || $model->email || $model->telefone) : ?>

        <br />

        <p style="font-size: 1.3em; font-weight: bold">Dados do Denunciante</p>

        <dl class="dl-horizontal">

            <dt><?= Html::activeLabel($model, 'nome') ?></dt>
            <dd><?= Html::encode($model->nome) ?></dd>

            <dt><?= Html::activeLabel($model, 'email') ?></dt>
            <dd><?= Html::encode($model->email) ?></dd>

            <dt><?= Html::activeLabel($model, 'telefone') ?></dt>
            <dd><?= Html::encode($model->telefone) ?></dd>
        </dl>
    <?php endif; ?>

    <br />

    <p style="font-size: 1.3em; font-weight: bold">Histórico</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'exportable' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'data_hora',
                'header' => 'Data/Hora',
                'options' => [
                    'width' => '10%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_hora');
                },
            ],
            [
                'attribute' => 'tipo',
                'header' => 'Tipo',
                'value' => function ($model, $index, $widget) {
                    return OcorrenciaHistoricoTipo::getDescricao($model->tipo);
                }
            ],
            [
                'attribute' => 'status_antigo',
                'header' => 'Status Antigo',
                'value' => function ($model, $index, $widget) {
                    return OcorrenciaStatus::getDescricao($model->status_antigo);
                }
            ],
            [
                'attribute' => 'status_novo',
                'header' => 'Status Novo',
                'value' => function ($model, $index, $widget) {
                    return OcorrenciaStatus::getDescricao($model->status_novo);
                }
            ],
            [
                'attribute' => 'observacoes',
                'header' => 'Observações',
                'value' => function ($model, $index, $widget) {
                    return $model->observacoes;
                }
            ],
            [
                'attribute' => 'data_associada',
                'header' => 'Data Associada',
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_associada');
                }
            ],
            [
                'attribute' => 'agente_id',
                'header' => 'Agente',
                'value' => function ($model, $index, $widget) {
                    return $model->agente_id ? Html::encode($model->agente->nome) : null;
                }
            ],
            [
                'attribute' => 'usuario_id',
                'header' => 'Usuário',
                'value' => function ($model, $index, $widget) {
                    return $model->usuario_id ? Html::encode($model->usuario->nome) : null;
                }
            ],
        ],
    ]); ?>

    <?php
    $latitude = null;
    $longitude = null;

    if ($model->bairroQuarteirao || ($model->latitude !== null && $model->longitude !== null)) {

        if ($model->bairroQuarteirao) {

            $model->bairroQuarteirao->loadCoordenadas();
            $centro = $model->bairroQuarteirao->getCentro();
            $latitude = $centro[1];
            $longitude = $centro[0];

        } elseif ($model->latitude !== null && $model->longitude !== null) {

            $latitude = $model->latitude;
            $longitude = $model->longitude;
        }
    }
    ?>

    <?php if($latitude && $longitude) : ?>

        <p style="font-size: 1.3em; font-weight: bold">Mapa</p>

        <img src="http://api.tiles.mapbox.com/v3/vigilantus.kjkb4j0a/pin-s-hospital(<?= round($longitude,4); ?>,<?= round($latitude,4); ?>)/<?= round($longitude,4); ?>,<?= round($latitude,4); ?>,16/800x400.png" alt="Mapa da Ocorrência" />

    <?php endif; ?>

</div>
