<?php

use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\Usuario;
use app\widgets\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroTipoSearch $searchModel
 */

$this->title = 'Boletim de Reconhecimento Geográfico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Preencher novo Boletim',
                    Yii::$app->urlManager->createUrl('ficha-rg/create'),
                    [
                        'class' => 'btn btn-flat success',
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
                'attribute' => 'bairro_quarteirao_id',
                'value' => function ($model, $index, $widget) {
                    return $model->quarteirao ? $model->quarteirao->numero_quarteirao : null;
                },
                'options' => [
                    'width' => '20%',
                ]
            ],
            [
                'attribute' => 'seq',
                'options' => [
                    'width' => '10%',
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
                'attribute' => 'folha',
                'options' => [
                    'width' => '10%',
                ]
            ],
            [   
                'class' => 'app\extensions\grid\FModalColumn',
                'iconClass' => 'icon-search opacity50',
                'modalId' => 'fechamento-detalhes',
                'modalAjaxContent' => function ($model, $index, $widget) {
                    return Html::url(array('ficha-rg/fechamento', 'id' => $model->id));
                },
                'requestType' => 'GET',
                'header' => 'Qtde. Imóveis',
                'value' => function ($model, $index, $widget) {
                    return $model->quantidadeImoveis . ' (Ver fechamento)';
                },
                'options' => [
                    'width' => '15%',
                ]
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