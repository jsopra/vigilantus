<?php
use app\widgets\GridView;
use app\models\OcorrenciaStatus;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => null,
    'exportable' => false,
    'columns' => [
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
                return $model->agente_id ? Html::encode($model->agente->nome) : null;
            }
        ],
        [
            'attribute' => 'usuario_id',
            'value' => function ($model, $index, $widget) {
                return $model->usuario_id ? Html::encode($model->usuario->nome) : null;
            }
        ],
    ]
]);
