<?php

use yii\helpers\Html;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;
use app\widgets\GridView;
use app\helpers\models\ImovelHelper;
use yii\helpers\Url;

$this->title = 'Focos de Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foco-transmissor-index">

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
            },
            'batch' => function() {
                return Html::a(
                    'Importar Arquivo de Focos',
                    Url::to(['batch']),
                    [
                        'class' => 'btn btn-flat default',
                    ]
                );
            }
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'bairro_quarteirao_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->bairroQuarteirao->numero_sequencia;
                },
                'options' => ['style' => 'width: 10%']
            ],
            [
                'header' => 'Bairro',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->bairroQuarteirao->bairro->nome;
                },
                'options' => ['style' => 'width: 10%']
            ],
            [
                'attribute' => 'imovel_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->imovel ? ImovelHelper::getEnderecoCompleto($model->imovel) : 'Vinculado à Quarteirão';
                },
                'options' => ['style' => 'width: 15%']
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
                'format' => 'raw',
                'header' => 'Datas',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['data_entrada', 'data_exame', 'data_coleta'] as $item) {
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->getFormattedAttribute($item));
                    }
                    return $str;
                },
                'options' => ['style' => 'width: 15%']
            ],
            [
                'format' => 'raw',
                'header' => 'Quantidades',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'] as $item) {
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);
                    }
                    return $str;
                },
                'options' => ['style' => 'width: 15%;']
            ],
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
		],
	]); ?>

</div>
