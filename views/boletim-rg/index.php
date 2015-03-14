<?php

use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\Usuario;
use app\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroTipoSearch $searchModel
 */

$this->title = 'Boletim de Reconhecimento Geográfico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-index" data-role="">

	<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'exportable' => false,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Novo Boletim/Imóvel',
                    Yii::$app->urlManager->createUrl('boletim-rg/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-create-boletim-imovel',
                    ]
                );
            },
            'createFechamento' => function() {
                return Html::a(
                    'Novo Boletim/Fechamento',
                    Yii::$app->urlManager->createUrl('boletim-rg/create-fechamento'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-create-boletim-fechamento',
                    ]
                );
            },
            'batch' => function() {
                return Html::a(
                    'Importar Arquivo de Fechamento',
                    Url::to(['batch']),
                    [
                        'class' => 'btn btn-flat default',
                        'id' => 'stepguide-create-carga-fechamento',
                    ]
                );
            }
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'bairro_id',
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
            [
                'attribute' => 'bairro_quarteirao_numero',
                'header' => 'Quarteirão',
                'value' => function ($model, $index, $widget) {
                    return $model->quarteirao ? $model->quarteirao->numero_sequencia : null;
                },
                'options' => [
                    'width' => '20%',
                ]
            ],
            [
                'attribute' => 'data',
                'options' => [
                    'width' => '10%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data');
                },
            ],
            [
                'header' => 'Detalhado?',
                'value' => function ($model, $index, $widget) {
                    return $model->quantidadeImoveis > 0 ? 'Sim' : 'Não';
                },
                'options' => [
                    'width' => '10%',
                ]
            ],
            [
                'class' => 'app\extensions\grid\ModalColumn',
                'iconClass' => 'icon-search opacity50',
                'modalId' => 'fechamento-detalhes',
                'modalAjaxContent' => function ($model, $index, $widget) {
                    return Url::toRoute(array('boletim-rg/ver-fechamento', 'id' => $model->id));
                },
                'requestType' => 'GET',
                'header' => 'Qtde. Imóveis',
                'linkTitle' => 'Ver Fechamento',
                'value' => function ($model, $index, $widget) {
                    return $model->quantidadeImoveisFechamento . ' (Ver fechamento)';
                },
                'options' => [
                    'width' => '15%',
                ]
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $url = $model->quantidadeImoveis == 0 ? Url::toRoute(array('boletim-rg/update-fechamento', 'id' => $model->id)) : Url::toRoute(array('boletim-rg/update', 'id' => $model->id));
                        return  Html::a('<i class="table-edit"></i>', $url, array('title' => 'Alterar'));
                    },
                ],
            ],
        ],
    ]);
    ?>

</div>
<?php
$view = Yii::$app->getView();
$script = '
    jQuery(document).ready(function(){
        $("input[name=\'BoletimRgSearch[data]\'").datepicker().on("changeDate", function (ev) {
            $(this).datepicker("hide");
        });
    });
';
$view->registerJs($script);
?>

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

            intro.setOptions({
                steps: [
                    {
                        element: "#stepguide-title",
                        intro: "Este é o cadastro de Boletins de Reconhecimento Geográfico"
                    },
                    {
                        element: "#stepguide-create-boletim-imovel",
                        intro: "Você pode transcrever integralmente um Boletim de RG, imóvel a imóvel"
                    },
                    {
                        element: "#stepguide-create-boletim-fechamento",
                        intro: "Como também pode apenas submeter o fechamento do Boletim, com o resumo de imóveis por tipo"
                    },
                    {
                        element: "#stepguide-create-carga-fechamento",
                        intro: "Caso a quantidade de dados seja muito grande, você pode também fazer uma carga de fechamento de RG"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
