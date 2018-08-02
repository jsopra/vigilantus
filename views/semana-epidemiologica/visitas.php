<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\Bairro;
use app\models\VisitaStatus;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


$this->title = 'Visitas';

$this->params['breadcrumbs'][] = ['label' => 'Semanas Epidemiológicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visitas de Agentes', 'url' => ['agentes', 'cicloId' => $ciclo->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semana-epidemiologica-agendar">
    <h1><?= Html::encode($this->title) ?> para ciclo <span style="color: #797979;"><?= Html::encode($ciclo->nome) ?></span> </h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() use ($ciclo, $agente) {
                return Html::a(
                    'Agendar Visitas',
                    Yii::$app->urlManager->createUrl(['semana-epidemiologica/agendar', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            },
        ],	
		'columns' => [
            [
                'attribute' => 'agente_id',
                'header' => 'Agente',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->agente_id ? Html::encode($model->agente->nome) : null;
                }
            ],
            [
                'attribute' => 'bairro_id',
                'header' => 'Bairro',
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro_id ? Html::encode($model->bairro->nome) : null;
                }
            ],
            [
                'attribute' => 'quarteirao_id',
                'header' => 'Quarteirão',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->quarteirao_id ? Html::encode($model->quarteirao->numero_sequencia) : null;
                }
            ],
            [
                'attribute' => 'data_atividade',
                'filter' => false,
            ],
            [
                'attribute' => 'visita_status_id',
                'header' => 'Status da Visita',
                'filter' => VisitaStatus::getDescricoes(),
                'value' => function ($model, $index, $widget) {
                    return $model->visita_status_id ? Html::encode(VisitaStatus::getDescricao($model->visita_status_id)) : null;
                }
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) use ($ciclo, $agente) {
                        if ($model->visita_status_id != VisitaStatus::AGENDADA) {
                            return;
                        }
                        
		                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['semana-epidemiologica/delete-visita', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id, 'visitaId' => $model->id]), [
		                            'title' => Yii::t('app', 'Excluir Visita'),
		                ]);
		            }
                ],
            ],
		],
	]);  ?>
</div>
