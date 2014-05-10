<?php

use yii\helpers\Html;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;
use app\widgets\GridView;

$this->title = 'Focos de Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foco-transmissor-index"">

	<h1><?= Html::encode($this->title) ?></h1>

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
                'attribute' => 'imovel.bairroQuarteirao.bairro.nome',
            ],
            [
                'header' => 'Tipo de Imóvel',
                'attribute' => 'imovel.imovelTipo.nome',
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
