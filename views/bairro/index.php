<?php

use app\models\BairroTipo;
use app\models\Municipio;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroSearch $searchModel
 */
$this->title = 'Bairros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Cadastrar Bairro', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'municipio_id',
                'visible' => Yii::$app->user->checkAccess('Root'),
                'filter' => Municipio::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->municipio ? $model->municipio->nome : null;
                }
            ],
            'nome',
            [
                'attribute' => 'bairro_tipo_id',
                'filter' => BairroTipo::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->tipo ? $model->tipo->nome : null;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
