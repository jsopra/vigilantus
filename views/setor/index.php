<?php

use yii\helpers\Html;
use app\widgets\GridView;

$this->title = 'Setores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setor-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'exportable' => false,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Setor',
                    Yii::$app->urlManager->createUrl('setor/create'),
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
            [
                'header' => 'Usuários',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeUsuarios . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['setor-usuario/index', 'parentID' => $model->id]),
                        ['title' => 'Gerenciar Usuários ' . $model->nome]
                    );

                    return Html::tag('p', $link, ['class' => 'text-center no-margin']);
                },
            ],
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',

            ],
		],
	]); ?>

</div>
