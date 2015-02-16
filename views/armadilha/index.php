<?php
use app\widgets\GridView;
use yii\helpers\Html;

$this->title = 'Armadilhas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
