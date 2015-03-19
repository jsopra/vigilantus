<?php

use app\models\BairroCategoria;
use app\widgets\GridView;
use yii\helpers\Html;
use app\helpers\MapBoxAPIHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroSearch $searchModel
 */
$this->title = 'Bairros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-index">

    <h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Bairro',
                    Yii::$app->urlManager->createUrl('bairro/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-create-bairro',
                    ]
                );
            },
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            [
                'attribute' => 'bairro_categoria_id',
                'filter' => BairroCategoria::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->categoria ? $model->categoria->nome : null;
                }
            ],
            [
                'header' => 'Quarteirões',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);

                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeQuarteiroes . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['bairro-quarteirao/index', 'parentID' => $model->id]),
                        ['title' => 'Gerenciar Quarteirões do Bairro ' . $model->nome]
                    );

                    return Html::tag('p', $link, ['class' => 'text-center no-margin']);
                },
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

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
                        intro: "Este é o cadastro de bairros e quarteirões."
                    },
                    {
                        element: "#stepguide-create-bairro",
                        intro: "Você deve começar cadastrando um bairro, identificando seu nome e definindo um espaço geográfico para ele"
                    },

                    {
                        element: "thead",
                        intro: "Uma vez cadastrado o bairro, ele aparecerá na tabela abaixo. Bastará clicar em \"Gerenciar\" na coluna Quarteirões para proceder, da mesma forma, o cadastro e geolocalização dos quarteirões do bairro"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
