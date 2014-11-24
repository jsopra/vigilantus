<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\Municipio;
use app\helpers\models\ClienteHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ClienteSearch $searchModel
 */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Cliente',
                    Yii::$app->urlManager->createUrl('cliente/create'),
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
                'attribute' => 'municipio_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->municipio ? $model->municipio->nome : null;
                }
            ],
            [
                'header' => 'Módulos',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                
                    $img = Html::tag('i', '', ['class' => 'glyphicon glyphicon-link']);
                
                    $link = Html::a(
                        'Gerenciar (' . $model->quantidadeModulos . ') &nbsp;' . $img,
                        Yii::$app->urlManager->createUrl(['cliente-modulo/index', 'parentID' => $model->id]),
                        ['title' => 'Gerenciar Módulos do Cliente ' . $model->municipio->nome]
                    );
                
                    return Html::tag('p', $link, ['class' => 'text-center no-margin']); 
                },
            ],
            'rotulo',
			[
                'attribute' => 'data_cadastro',
                'filter' => false,
                'options' => [
                    'width' => '35%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_cadastro');
                },
            ], 
            [   
                'class' => 'app\extensions\grid\ModalColumn',
                'iconClass' => 'icon-search opacity50',
                'modalId' => 'dados-contato-detalhes',
                'modalContent' => function ($model, $index, $widget) {
                    return ClienteHelper::getDadosContato($model);
                },
                'requestType' => 'GET',
                'header' => 'Dados de contato',
                'linkTitle' => 'Ver dados de contato',
                'value' => function ($model, $index, $widget) {
                    return 'Ver contato';
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
	]); ?>

</div>
