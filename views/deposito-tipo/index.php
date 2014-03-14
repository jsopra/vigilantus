<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\DepositoTipo;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\DepositoTipoSearch $searchModel
 */

$this->title = 'Tipos de Depósitos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposito-tipo-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Tipo de Depósito',
                    Yii::$app->urlManager->createUrl('deposito-tipo/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            }
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'descricao',
			'sigla',
            [
                'attribute' => 'deposito_tipo_pai',
                'filter' => DepositoTipo::listData('descricao'),
                'value' => function ($model, $index, $widget) {
                    return $model->depositoTipoPai ? $model->depositoTipoPai->descricao : null;
                }
            ],

			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
