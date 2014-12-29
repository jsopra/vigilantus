<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ModuloSearch $searchModel
 */

$this->title = 'Módulos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulo-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Modulo',
                    Yii::$app->urlManager->createUrl('modulo/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            }
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'nome',
			'ativo:boolean',
			[
                'attribute' => 'data_cadastro',
                'options' => [
                    'width' => '35%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_cadastro');
                },
            ], 
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
