<?php
use app\models\ImovelCondicao;
use app\models\ImovelTipo;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => null,
    'columns' => [
        [
            'attribute' => 'imovel_tipo_id',
            'value' => function ($model, $index, $widget) {
                return $model->imovelTipo ? $model->imovelTipo->nome : null;
            }
        ],
        [
            'attribute' => 'imovel_lira',
            'value' => function ($model, $index, $widget) {
                return $model->imovel_lira ? 'Sim' : 'Não';
            }
        ],
        'quantidade',
    ]
]);