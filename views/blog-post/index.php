<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BlogPostSeach $searchModel
 */

$this->title = 'Posts do Blog Vigilantus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-index" data-role="">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Post',
                    Yii::$app->urlManager->createUrl('blog-post/create'),
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
                'attribute' => 'data',
                'options' => [
                    'width' => '20%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data');
                },
            ], 
			'titulo',
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>

<?php
$view = Yii::$app->getView();
$script = '
    jQuery(document).ready(function(){
        $("input[name=\'BlogPostSearch[data]\'").datepicker().on("changeDate", function (ev) {
            $(this).datepicker("hide");
        });
    });
';
$view->registerJs($script);
?>
