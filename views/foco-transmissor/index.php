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

	<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>
	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Foco de Transmissor',
                    Yii::$app->urlManager->createUrl('foco-transmissor/create'),
                    [
                        'id' => 'stepguide-cadastro-focos',
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
                        'id' => 'stepguide-carga-focos',
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
                'attribute' => 'data_entrada',
                'format' => 'raw',
                'header' => 'Datas',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['data_entrada', 'data_exame', 'data_coleta'] as $item) {
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->getFormattedAttribute($item));
                    }
                    return $str;
                },
                'filter' => Html::input('date', 'FocoTransmissorSearch[data_entrada]', $searchModel->data_entrada, ['class' => 'form-control input-datepicker']),
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
                'buttons' => [
                    'ver' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="table-view"></i>',
                            \yii\helpers\Url::to(['mapa/tratamento-foco', 'TratamentoFocoMapForm[foco_id]' => $model->id]),
                            ['title' => 'Ver Foco no mapa']
                        );
                    },
                ],
                'template' => '{ver} {update} {delete}',
            ],
		],
	]); ?>

</div>

<?php
if(isset($_GET['step'])) {
    $view = Yii::$app->getView();
    $script = '
        $(document).ready(function() {

            var intro = introJs();
            intro.setOption("skipLabel", "Sair");
            intro.setOption("doneLabel", "Fechar");
            intro.setOption("nextLabel", "Próximo");
            intro.setOption("prevLabel", "Anterior");
            intro.setOption("tooltipPosition", "auto");
            intro.setOption("positionPrecedence", ["left", "right", "bottom", "top"]);

            intro.setOptions({
                steps: [
                    {
                        element: "#stepguide-title",
                        intro: "Este é o cadastro de focos"
                    },
                    {
                        element: "#stepguide-cadastro-focos",
                        intro: "Cadastre um novo foco pelo formulário"
                    },
                    {
                        element: "#stepguide-carga-focos",
                        intro: "Ou cadastre vários focos através de uma carga"
                    }
                ]
            });
            intro.start();
        })
    ';
    $view->registerJs($script);
}
