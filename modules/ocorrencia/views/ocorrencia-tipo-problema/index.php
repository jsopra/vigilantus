<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\OcorrenciaTipoProblemaSearch $searchModel
 */

$this->title = 'Tipo Problemas de Ocorrências';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-tipo-problema-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Tipo de Problema',
                    Yii::$app->urlManager->createUrl('ocorrencia/ocorrencia-tipo-problema/create'),
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
                'attribute' => 'ativo',
                'filter' => [1 => 'Sim', 0 => 'Não'],
                'value' => function ($model, $index, $widget) {
                    return $model->ativo ? 'Sim' : 'Não';
                }
            ],
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
