<?php

use app\models\Municipio;
use app\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\EspecieTransmissorSearch $searchModel
 */

$this->title = 'Espécies de Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Espécie de Transmissor',
                    Url::to(['create']),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            },
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            'qtde_metros_area_foco',
            'qtde_dias_permanencia_foco',
            [
                'format' => 'raw',
                'attribute' => 'cor_foco_no_mapa',
                'value' => function ($model, $index, $widget) {
                    return Html::tag('div', '&nbsp;', ['style' => 'width: 20px; margin: 0 auto; background-color: ' . $model->cor. ';']);
                },
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
