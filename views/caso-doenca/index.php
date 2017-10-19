<?php

use yii\helpers\Html;
use app\widgets\GridView;
use yii\helpers\Url;

$this->title = 'Casos de Doenças';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caso-doenca-index">

	<h1><?= Html::encode($this->title) ?></h1>

        <?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Caso de Doença',
                    Yii::$app->urlManager->createUrl('caso-doenca/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-caso-doenca',
                    ]
                );
            },
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
            'nome_paciente',
            [
                'attribute' => 'doenca_id',
                'value' => function ($model, $index, $widget) {
                    return $model->doenca ? Html::encode($model->doenca->nome) : null;
                }
            ],
            [
                'attribute' => 'bairro_id',
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? Html::encode($model->bairro->nome) : null;
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
                'attribute' => 'data_sintomas',
                'filter' => Html::input('date', 'CasoDoencaSearch[data_sintomas]', $searchModel->data_sintomas, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->data_sintomas;
                }
            ],
            [
                'attribute' => 'data_cadastro',
                'filter' => Html::input('date', 'CasoDoencaSearch[data_cadastro]', $searchModel->data_cadastro, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->data_cadastro;
                }
            ],
			[
                'header' => 'Opções',
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
