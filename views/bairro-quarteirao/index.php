<?php

use app\models\Municipio;
use app\models\Bairro;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroQuarteiraoSearch $searchModel
 */

$this->title = 'Quarteirões de Bairros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Cadastrar Quarteirão de Bairro', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php echo GridView::widget([
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
			[
                'attribute' => 'bairro_id',
                'visible' => Yii::$app->user->checkAccess('Root'),
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
			'numero_quarteirao',
			'numero_quarteirao_2',
			[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
		],
	]); ?>

</div>
