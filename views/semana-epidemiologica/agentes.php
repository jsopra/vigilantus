<?php

use yii\helpers\Html;
use app\models\Bairro;
use app\models\Equipe;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\widgets\GridView;


$this->title = 'Visitas de Agentes';
$this->params['breadcrumbs'][] = ['label' => 'Semanas Epidemiológicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semana-epidemiologica-agendar">
    <h1><?= Html::encode($this->title) ?> para ciclo <span style="color: #797979;"><?= Html::encode($ciclo->nome) ?></span></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [],
		'columns' => [
            [
                'attribute' => 'equipe_id',
                'header' => 'Equipe',
                'filter' => Equipe::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->equipe_id ? Html::encode($model->equipe->nome) : null;
                }
            ],
            'nome',
            [
                //'attribute' => 'usuario_id',
                'header' => 'Visitas Agendadas',
                'value' => function ($model, $index, $widgets) use ($ciclo) {
                    return $model->getVisitasAgendadas($ciclo);
                }
            ],
            [
                //'attribute' => 'usuario_id',
                'header' => 'Visitas Executadas',
                'value' => function ($model, $index, $widget) use ($ciclo) {
                    return $model->getVisitasRealizadas($ciclo);
                }
            ],
            [
                'class' => 'app\components\ActionColumn',
                'buttons' => [
                    'agendar' => function ($url, $model, $key) use ($ciclo) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Supervisor')){
                            return null;
                        }
                        return Html::a(
                            '<i class="glyphicon glyphicon-calendar"></i>',
                            \yii\helpers\Url::to(['semana-epidemiologica/visitas', 'cicloId' => $ciclo->id, 'agenteId' => $model->id]),
                            ['title' => 'Gerenciar Visitas do Agente']
                        );
                    },
                    'mapa' => function ($url, $model, $key) use ($ciclo) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Supervisor')){
                            return null;
                        }

                        if ($model->getVisitasAgendadas($ciclo) == 0){
                            return null;
                        }

                        return '&nbsp;' . Html::a(
                            '<i class="icon-fa fa-map-marker"></i>',
                            \yii\helpers\Url::to(['semana-epidemiologica/mapa', 'cicloId' => $ciclo->id, 'agenteId' => $model->id]),
                            ['title' => 'Ver Mapa de Visitas do Agente']
                        );
                    },
                    'resumo' => function ($url, $model, $key) use ($ciclo) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Supervisor')){
                            return null;
                        }

                        if ($model->getVisitasAgendadas($ciclo) == 0){
                            return null;
                        }

                        return '&nbsp;' . Html::a(
                            '<i class="icon-fa fa-file-o"></i>',
                            \yii\helpers\Url::to(['semana-epidemiologica/resumo', 'cicloId' => $ciclo->id, 'agenteId' => $model->id]),
                            ['title' => 'Ver Resumo do Trabalho de Campo']
                        );
                    },
                ],
                'template' => Yii::$app->user->can('Administrador') || Yii::$app->user->can('Supervisor') ? '{agendar} {resumo} {mapa}' : '{mapa}',
            ],
		],
	]);  ?>
</div>
