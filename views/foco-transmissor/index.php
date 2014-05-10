<?php

use yii\helpers\Html;
use app\models\BairroQuarteirao;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\FocoTransmissorSearch $searchModel
 */

$this->title = 'Focos de Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foco-transmissor-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Foco de Transmissor',
                    Yii::$app->urlManager->createUrl('foco-transmissor/create'),
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
                'header' => 'Bairro',
                'attribute' => 'quarteirao.bairro.nome',
            ],
                /*
            [
                'attribute' => 'quarteirao_id',
                'filter' => BairroQuarteirao::listData('numero_sequencia', 'id', 'bairro', 'nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->quarteirao->numero_sequencia;
                }
            ],
                 * 
                 */
            [
                'attribute' => 'tipo_imovel_id',
                'filter' => ImovelTipo::find()->ativo()->listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->tipoImovel->sigla ? $model->tipoImovel->sigla : $model->tipoImovel->nome;
                }
            ],
            [
                'attribute' => 'tipo_deposito_id',
                'filter' => DepositoTipo::listData('descricao'),
                'value' => function ($model, $index, $widget) {
                    return $model->tipoDeposito->sigla ? $model->tipoDeposito->sigla : $model->tipoDeposito->descricao;
                }
            ],
            [
                'attribute' => 'especie_transmissor_id',
                'filter' => EspecieTransmissor::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->especieTransmissor->nome;
                }
            ],
			//'data_cadastro',
			//'data_atualizacao',
            [
                'attribute' => 'data_entrada',
                'format' => 'date',
                'filter' => false,
            ],
            [
                'attribute' => 'data_exame',
                'format' => 'date',
                'filter' => false,
            ],
            [
                'attribute' => 'data_coleta',
                'format' => 'date',
                'filter' => false,
            ],
			'imovel_id',
			'quantidade_forma_aquatica',
			'quantidade_forma_adulta',
			'quantidade_ovos',

			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
