<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\EquipeSearch $searchModel
 */

$this->title = 'Equipes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipe-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Equipe',
                    Yii::$app->urlManager->createUrl('equipe/create'),
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
                'header' => 'Agentes',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeAgentes . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['equipe-agente/index', 'parentID' => $model->id]),
                        ['title' => 'Gerenciar Agentes da Equipe ' . $model->nome]
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
