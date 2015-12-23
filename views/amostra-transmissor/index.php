<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\DepositoTipo;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AmostraTransmissorSearch $searchModel
 */

$this->title = 'Amostra Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amostra-transmissor-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [

			['class' => 'yii\grid\SerialColumn'],
            'numero_amostra',
            'data_criacao',
			'data_coleta',
            [
                'attribute' => 'tipo_deposito_id',
                'filter' => DepositoTipo::listData('descricao'),
                'value' => function ($model, $index, $widget) {
                    return $model->tipoDeposito->sigla ? $model->tipoDeposito->sigla : $model->tipoDeposito->descricao;
                }
            ],
            [
                'attribute' => 'quarteirao_id',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->bairroQuarteirao->numero_sequencia;
                },
                'options' => ['style' => 'width: 10%']
            ],
            [
                'format' => 'raw',
                'header' => 'Endereço',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['endereco', 'numero_casa'] as $item) {
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);
                    }
                    return $str;
                },
                'options' => ['style' => 'width: 15%;']
            ],
            [
                'format' => 'raw',
                'header' => 'Quantidades',
                'value' => function ($model, $index, $widget) {
                    $str = '';
                    foreach(['quantidade_larvas', 'quantidade_pupas'] as $item) {
                        $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);
                    }
                    return $str;
                },
                'options' => ['style' => 'width: 17%;']
            ],
			[
                'class' => 'app\components\ActionColumn',
                'buttons' => [
                    'ver' => function ($url, $model, $key) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Tecnico Laboratorial')){
                            return null;
                        }
                        return Html::a(
                            '<i class="table-view"></i>',
                            \yii\helpers\Url::to(['amostra-transmissor/view', 'id' => $model->id]),
                            ['title' => 'Análise Laboratorial']
                        );
                    },
                ],
                'template' => '{ver} {update} {delete}',
            ],
		],
	]); ?>

</div>
