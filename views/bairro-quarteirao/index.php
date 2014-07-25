<?php

use app\models\Municipio;
use app\models\Bairro;
use app\widgets\GridView;
use yii\helpers\Html;
use app\helpers\GoogleMapsAPIHelper;

$bairro = $parentObject;

$this->title = 'Quarteirões do Bairro "' . $bairro->nome . '"';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(GoogleMapsAPIHelper::getAPIUrl(false, 'drawing'), ['yii\web\JqueryAsset']);
?>
<div class="bairro-quarteirao-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar ao cadastro de Bairros',
        Yii::$app->urlManager->createUrl('bairro/index'),
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
                'Cadastrar Quarteirão de Bairro',
                Yii::$app->urlManager->createUrl(['bairro-quarteirao/create', 'parentID' => $bairro->id]),
                [
                    'class' => 'btn btn-flat success',
                    'data-role' => 'create',
                ]
            ),
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'numero_quarteirao',
			'numero_quarteirao_2',
			[
                'class' => 'app\components\DependentCRUDActionColumn',
                'template' => '{update} {delete}',
                'parentID' => $bairro->id,
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
		],
	]); ?>

</div>
