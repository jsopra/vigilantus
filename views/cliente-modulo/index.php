<?php

use app\models\Cliente;
use app\models\Modulo;
use app\widgets\GridView;
use yii\helpers\Html;

$cliente = $parentObject;

$this->title = 'Módulos do Cliente "' . $cliente->municipio->nome . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar ao cadastro de Clientes',
        Yii::$app->urlManager->createUrl('cliente/index'),
        [
            'class' => 'btn btn-link',
        ]
    );
    ?>
	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => Html::a(
                'Adicionar Módulo para cliente',
                Yii::$app->urlManager->createUrl(['cliente-modulo/create', 'parentID' => $cliente->id]),
                [
                    'class' => 'btn btn-flat success',
                    'data-role' => 'create',
                ]
            ),
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'modulo_id',
                'value' => function ($model, $index, $widget) {
                    return $model->modulo->nome;
                },
            ],
			[
                'class' => 'app\components\DependentCRUDActionColumn',
                'template' => '{delete}',
                'parentID' => $cliente->id,
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
		],
	]); ?>

</div>
