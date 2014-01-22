<?php

use app\models\Municipio;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroTipoSearch $searchModel
 */

$this->title = 'Categorias de Bairro';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Cadastrar Categoria de Bairro', ['create'], ['class' => 'btn btn-flat success']) ?>
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
                'attribute' => 'inserido_por',
                'filter' => Usuario::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->inseridoPor ? $model->inseridoPor->nome : null;
                }
            ],
            [
                'attribute' => 'data_cadastro',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->formatted_data_cadastro;
                }
            ],
            [
                'attribute' => 'atualizado_por',
                'filter' => Usuario::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->atualizadoPor ? $model->atualizadoPor->nome : null;
                }
            ],
            [
                'attribute' => 'data_atualizacao',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->formatted_data_atualizacao;
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
