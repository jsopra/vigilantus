<?php
use yii\helpers\Html;
use app\widgets\GridView;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
use app\models\Usuario;
?>

<br />

<div class="table-responsive">
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'exportable' => $isExportable,
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
