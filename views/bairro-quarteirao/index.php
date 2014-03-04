<?php

use app\models\Municipio;
use app\models\Bairro;
use app\widgets\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroQuarteiraoSearch $searchModel
 */

$this->title = 'Quarteirões de Bairros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Quarteirão de Bairro',
                    Yii::$app->urlManager->createUrl('bairro-quarteirao/create'),
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
			[
                'attribute' => 'bairro_id',
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
			'numero_quarteirao',
			'numero_quarteirao_2',
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
		],
	]); ?>

</div>
