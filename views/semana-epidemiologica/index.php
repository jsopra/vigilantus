<?php

use yii\helpers\Html;
use app\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SemanaEpidemiologicaSearch $searchModel
 */

$this->title = 'Semanas Epidemiológicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amostra-transmissor-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Semana Epidemiológica',
                    Yii::$app->urlManager->createUrl('semana-epidemiologica/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-cadastro-semana-epidemiologica',
                    ]
                );
            },
        ],
		'columns' => [
            'nome',
            [
                'attribute' => 'inicio',
                'filter' => Html::input('date', 'SemanaEpidemiologicaSearch[inicio]', $searchModel->inicio, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('inicio');
                },
            ],
            [
                'attribute' => 'fim',
                'filter' => Html::input('date', 'SemanaEpidemiologicaSearch[fim]', $searchModel->fim, ['class' => 'form-control input-datepicker']),
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('fim');
                },
                
            ],
            [
                'class' => 'app\components\ActionColumn',
                'buttons' => [
                    'agendar' => function ($url, $model, $key) {
                        if (!Yii::$app->user->can('Administrador') && !Yii::$app->user->can('Supervisor')){
                            return null;
                        }
                        return Html::a(
                            //'<i class="glyphicon glyphicon-calendar"></i>',
                            '<i class="glyphicon glyphicon-user"></i>',
                            \yii\helpers\Url::to(['semana-epidemiologica/agentes', 'cicloId' => $model->id]),
                            ['title' => 'Gerenciar Visitas de Agentes']
                        );
                    },
                ],
                'template' => !Yii::$app->user->can('Supervisor') ? '{agendar} {update} {delete}' : '',
            ],
		],
	]);  ?>

</div>
