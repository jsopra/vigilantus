<?php
use app\widgets\GridView;
use yii\helpers\Html;

$this->title = 'Armadilhas';
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
                    'Cadastrar Armadilha',
                    Yii::$app->urlManager->createUrl('armadilha/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-cadastro-armadilha',
                    ]
                );
            },
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'descricao',
            [
                'attribute' => 'bairro_quarteirao_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->bairro_quarteirao_id ? $model->bairroQuarteirao->numero_quarteirao : null;
                }
            ],
            [
                'attribute' => 'bairro_quarteirao_id',
                'header' => 'Bairro',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->bairro_quarteirao_id ? $model->bairroQuarteirao->bairro->nome : null;
                }
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

            $("li.step-mapas").children("a").trigger("click");

            if(isGerente == "1") {
                intro.setOptions({
                    steps: [
                        {
                            element: "#stepguide-title",
                            intro: "Este é o cadastro de armadilhas. Nele você geolocaliza armadilhas."
                        },
                        {
                            element: "#stepguide-cadastro-armadilha",
                            intro: "Você pode cadastrar suas armadilhas clicando aqui"
                        },
                        {
                            element: "#step-mapa-armadilhas",
                            intro: "Após cadastrar algumas armadilhas, você já pode ver o mapa com todas armadilhas geolocalizadas"
                        }
                    ],
                    doneLabel: "Ir para o ḿapa",
                });

                intro.start().oncomplete(function() {
                    window.location.href = stepArmadilhasMapaUrl;
                });
            }
            else {

                intro.setOptions({
                    steps: [
                        {
                            element: "#stepguide-title",
                            intro: "Este é o cadastro de armadilhas. Nele você geolocaliza armadilhas."
                        },
                        {
                            element: "#stepguide-cadastro-armadilha",
                            intro: "Você pode cadastrar suas armadilhas clicando aqui"
                        }
                    ]
                });

                intro.start()
            }
        })
    ';
    $view->registerJs($script);
}
