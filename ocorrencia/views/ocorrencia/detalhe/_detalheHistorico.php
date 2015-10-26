<?php
use yii\helpers\Html;
use app\helpers\OcorrenciaHistoricoHelper;
use app\widgets\GridView;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
?>

<br />

<?php if ($isExportable) : ?>
<div class="table-responsive">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'exportable' => $isExportable,
    'exportButtonLabel' => '<i class="fa fa-download"></i> Exportar Detalhes do Histórico',
    'id' => 'grid-exportacao-historico-ocorrencia',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'data_hora',
            'options' => [
                'width' => '10%',
            ],
            'value' => function ($model, $index, $widget) {
                return $model->getFormattedAttribute('data_hora');
            },
        ],
        [
            'attribute' => 'tipo',
            'value' => function ($model, $index, $widget) {
                return OcorrenciaHistoricoTipo::getDescricao($model->tipo);
            }
        ],
        [
            'attribute' => 'status_antigo',
            'value' => function ($model, $index, $widget) {
                return OcorrenciaStatus::getDescricao($model->status_antigo);
            }
        ],
        [
            'attribute' => 'status_novo',
            'value' => function ($model, $index, $widget) {
                return OcorrenciaStatus::getDescricao($model->status_novo);
            }
        ],
        'observacoes',
        [
            'attribute' => 'data_associada',
            'value' => function ($model, $index, $widget) {
                return $model->getFormattedAttribute('data_associada');
            }
        ],
        [
            'attribute' => 'agente_id',
            'value' => function ($model, $index, $widget) {
                return $model->agente_id ? $model->agente->nome : null;
            }
        ],
        [
            'attribute' => 'usuario_id',
            'value' => function ($model, $index, $widget) {
                return $model->usuario_id ? $model->usuario->nome : null;
            }
        ],
    ],
]); ?>
</div>
<?php endif; ?>

<?php if ($historicos) : ?>
<ul class="timeline">
    <?php foreach ($historicos as $indice => $historico) : ?>
    <li<?= $indice % 2 ? ' class="timeline-inverted"' : '' ?>>
        <?= OcorrenciaHistoricoHelper::badge($historico) ?>
        <div class="timeline-panel">
            <div class="timeline-heading">
                <h4 class="timeline-title"><?= OcorrenciaStatus::getDescricao($historico->status_novo) ?></h4>
                <p>
                    <small class="text-muted">
                        <i class="glyphicon glyphicon-time"></i>
                        <time datetime="<?= $historico->data_hora ?>" title="<?= $historico->formatted_data_hora ?>">
                            <?= Yii::$app->formatter->asRelativeTime($historico->data_hora) ?>
                        </time>
                        <?php if ($historico->usuario) : ?>
                        por <?= Html::encode($historico->usuario->nome) ?>
                        <?php endif; ?>
                    </small>
                </p>
            </div>
            <div class="timeline-body">
                <p><?= Html::encode(OcorrenciaHistoricoHelper::descricao($historico)) ?></p>
                <?php if ($historico->observacoes) : ?>
                <hr>
                <p><?= Html::encode($historico->observacoes) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </li>
    <?php endforeach; ?>

    <?php if (empty($model->data_fechamento)) : ?>
    <li>
        <div class="timeline-badge warning"><i class="glyphicon glyphicon-time"></i></div>
    </li>
    <?php endif; ?>
</ul>
<?php else : ?>
    <p>Não há registro de histórico desta ocorrência.</p>
<?php endif; ?>
