<?php

use app\models\BairroCategoria;
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
<div class="bairro-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Cadastrar Bairro', ['create'], ['class' => 'btn btn-flat success', 'data-role' => 'create']) ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'nome',
            [
                'attribute' => 'bairro_tipo_id',
                'filter' => BairroCategoria::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->tipo ? $model->tipo->nome : null;
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
