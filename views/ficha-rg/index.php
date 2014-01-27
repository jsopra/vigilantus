<?php

use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BairroTipoSearch $searchModel
 */

$this->title = 'Boletim de Reconhecimento Geográfico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Preencher novo Boletim', ['create'], ['class' => 'btn btn-flat success']) ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'municipio_id',
                'visible' => Yii::$app->user->checkAccess('Root'),
                'filter' => Municipio::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->municipio ? $model->municipio->nome : null;
                }
            ],
            [
                'attribute' => 'bairro_id',
                'visible' => Yii::$app->user->checkAccess('Root'),
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
            [
                'attribute' => 'quarteirao_id',
                'visible' => Yii::$app->user->checkAccess('Root'),
                'filter' => BairroQuarteirao::listData('numero_quarteirao'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
            [
                'attribute' => 'seq',
                'options' => [
                    'width' => '5%',
                ]
            ], 
            [
                'attribute' => 'folha',
                'options' => [
                    'width' => '5%',
                ]
            ], 
            [
                'attribute' => 'mes',
                'options' => [
                    'width' => '5%',
                ]
            ], 
            [
                'attribute' => 'ano',
                'options' => [
                    'width' => '10%',
                ]
            ],  
            [
                'header' => 'Qtde.<br />Imóveis',
                'value' => function ($model, $index, $widget) {
                    return $model->quantidadeImoveis;
                },
                'options' => [
                    'width' => '5%',
                ]
            ],  
            [   
                'class' => 'app\extensions\grid\FModalColumn',
                'iconClass' => 'icon-time opacity50',
                'modalId' => 'fechamento-detalhes',
                'modalAjaxContent' => function ($model, $index, $widget) {
                    return Html::url(array('ficha-rg/fechamento', 'id' => $model->id));
                },
                'requestType' => 'GET',
                'header' => 'Fechamento',
                'value' => function ($model, $index, $widget) {
                    return 'Ver (modal)';
                },
                'options' => [
                    'width' => '10%',
                ]
            ],  
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
