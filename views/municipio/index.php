<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\helpers\models\MunicipioHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\MunicipioSearch $searchModel
 */

$this->title = 'Municípios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="municipio-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Municipio',
                    Yii::$app->urlManager->createUrl('municipio/create'),
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
			'sigla_estado',
            [
                'header' => 'Tem coordenadas?',
                'attribute' => 'coordenadas_area',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->coordenadas_area ? 'Sim' : 'Não';
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'brasao',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return MunicipioHelper::getBrasaoAsImageTag($model, 'mini');
                },
            ],
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
