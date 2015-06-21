<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SocialHashtagSearch $searchModel
 */

$this->title = 'Termos de Monitoramento de Redes Sociais';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-hashtag-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Termo',
                    Yii::$app->urlManager->createUrl('ocorrencia/social-hashtag/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            }
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'termo',
                'value' => function ($model, $index, $widget) {
                    return '#' . $model->termo;
                },
            ],
			'ativo:boolean',
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
