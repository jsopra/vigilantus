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
            'attribute' => 'condicao_imovel_id',
            'value' => function ($model, $index, $widget) {
                return $model->condicaoImovel ? $model->condicaoImovel->nome : null;
            }
        ],
        [
            'attribute' => 'area_de_foco',
            'value' => function ($model, $index, $widget) {
                return $model->area_de_foco ? 'Sim' : 'Não';
            }
        ],
        'quantidade',
    ]
]);