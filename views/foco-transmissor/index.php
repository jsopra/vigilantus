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
                'attribute' => 'imovel_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->imovel->getEnderecoCompleto();
                },
                'options' => ['style' => 'width: 30%']
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
                'header' => 'Examinador',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['laboratorio', 'tecnico'] as $item)
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);
                
                    return $str;
                },
                'options' => ['style' => 'width: 15%']
            ],
            [
                'format' => 'raw',
                'header' => 'Datas',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['data_entrada', 'data_exame', 'data_coleta'] as $item)
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->getFormattedAttribute($item));
                
                    return $str;
                },
                'options' => ['style' => 'width: 15%']
            ],
            [
                'format' => 'raw',
                'header' => 'Quantidades',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['quantidade_forma_adulta', 'quantidade_forma_adulta', 'quantidade_ovos'] as $item)
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);
                
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
