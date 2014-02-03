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
                    'width' => '10%',
                ]
            ],
            [
                'attribute' => 'seq',
                'options' => [
                    'width' => '7%',
                ]
            ], 
            [
                'attribute' => 'folha',
                'options' => [
                    'width' => '7%',
                ]
            ], 
            [
                'attribute' => 'mes',
                'options' => [
                    'width' => '7%',
                ]
            ], 
            [
                'attribute' => 'ano',
                'options' => [
                    'width' => '9%',
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
