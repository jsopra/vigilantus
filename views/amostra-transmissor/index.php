<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\DepositoTipo;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AmostraTransmissorSearch $searchModel
 */

$this->title = 'Amostras de Transmissores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amostra-transmissor-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
            [
                'attribute' => 'foco',
                'value' => function ($model, $index, $widget) {
                   return $model->foco ? 'Sim' : ($model->foco === false ? 'Não' : 'Pendente');
                },
                'filter' => [false => 'Não', true => 'Sim'],
            ],
            'numero_amostra',
            [
                'attribute' => 'data_coleta',
                'filter' => Html::input('date', 'AmostraTransmissorSearch[data_coleta]', $searchModel->data_coleta, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->data_coleta;
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
                'class' => 'app\extensions\grid\ModalColumn',
                'iconClass' => 'icon-search opacity50',
                'modalId' => 'visita-detalhes',
                'modalAjaxContent' => function ($model, $index, $widget) {
                    return Url::toRoute(['amostra-transmissor/visita', 'id' => $model->id]);
                },
                'requestType' => 'GET',
                'header' => 'Visita',
                'linkTitle' => 'Detalhes da visita',
                'value' => function ($model, $index, $widget) {
                    return $model->visita_id ? 'Ver detalhes' : 'N/D';
                },
                'hideLinkExpression' => function ($model, $index, $widget) {
                    return  $model->visita_id === null;
                },
                'options' => [
                    'width' => '10%',
                ]
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
                'template' => '{ver}',
                'buttons' => [
                    'ver' => function ($url, $model, $key) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Tecnico Laboratorial')){
                            return null;
                        }
                        return Html::a(
                            $model->foco !== null ? '<i class="table-view"></i>' : '<i class="glyphicon glyphicon-check"></i>',
                            \yii\helpers\Url::to(['amostra-transmissor/view', 'id' => $model->id]),
                            ['title' => 'Análise Laboratorial']
                        );
                    },
                ],
            ],
		],
	]); ?>

</div>