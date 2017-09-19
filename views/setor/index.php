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
			'nome',
            'padrao_ocorrencias:boolean',
            [
                'header' => 'Usuários',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeUsuarios . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['setor-usuario/index', 'parentID' => $model->id]),
                        ['title' => Html::encode('Gerenciar Usuários ') . Html::encode($model->nome)]
                    );

                    return Html::tag('p', $link, ['class' => 'text-center no-margin']);
                },
            ],
            [
                'header' => 'Tipo de Ocorrências',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeTiposOcorrencia . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['setor-tipo-ocorrencia/index', 'parentID' => $model->id]),
                        ['title' => Html::encode('Gerenciar Tipo de Ocorrências ') . Html::encode($model->nome)]
                    );

                    return Html::tag('p', $link, ['class' => 'text-center no-margin']);
                },
            ],
            [
                'header' => 'Módulos',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeModulos . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['setor-modulo/index', 'parentID' => $model->id]),
                        ['title' => 'Gerenciar Módulos do Setor ' . $model->nome]
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
