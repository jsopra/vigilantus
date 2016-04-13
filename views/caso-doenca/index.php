<?php

use yii\helpers\Html;
use app\widgets\GridView;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CasoDoencaSearch $searchModel
 */

$this->title = 'Casos Doenças';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caso-doenca-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Caso Doenca',
                    Yii::$app->urlManager->createUrl('caso-doenca/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
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
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'data_cadastro',
            'bairro_quarteirao_id',
            'nome_paciente',
            'data_sintomas',
            'doenca_id',
            //'cliente_id',
            //'inserido_por',
			//'atualizado_por',
			//'data_atualizacao',
			//'coordenadas_area',
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{delete}',
            ],
		],
	]); ?>

</div>
