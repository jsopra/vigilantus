<?php

use yii\helpers\Html;
use app\widgets\GridView;
use yii\helpers\Url;

$this->title = 'Casos de Doenças';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caso-doenca-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => false,
            'batch' => function() {
                return Html::a(
                    'Importar Arquivo de Casos',
                    Url::to(['batch']),
                    [
                        'class' => 'btn btn-flat default',
                    ]
                );
            }
        ],
		'columns' => [
			'id',
            [
                'attribute' => 'data_cadastro',
                'filter' => Html::input('date', 'CasoDoencaSearch[data_cadastro]', $searchModel->data_cadastro, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->data_cadastro;
                }
            ],
            [
                'attribute' => 'bairro_quarteirao_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return Html::encode($model->bairroQuarteirao->numero_sequencia);
                },
                'options' => ['style' => 'width: 10%']
            ],
            [
                'header' => 'Bairro',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return Html::encode($model->bairroQuarteirao->bairro->nome);
                },
                'options' => ['style' => 'width: 10%']
            ],
            [
                'attribute' => 'data_sintomas',
                'filter' => Html::input('date', 'CasoDoencaSearch[data_sintomas]', $searchModel->data_sintomas, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->data_sintomas;
                }
            ],
            'doenca_id',
            'nome_paciente',
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{delete}',
            ],
		],
	]); ?>

</div>
