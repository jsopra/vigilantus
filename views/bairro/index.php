<?php

use app\models\BairroCategoria;
use app\models\Municipio;
use app\widgets\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroSearch $searchModel
 */
$this->title = 'Bairros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Bairro',
                    Yii::$app->urlManager->createUrl('bairro/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            }
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'nome',
            [
                'attribute' => 'bairro_categoria_id',
                'filter' => BairroCategoria::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->categoria ? $model->categoria->nome : null;
                }
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
