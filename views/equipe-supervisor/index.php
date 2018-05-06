<?php

use app\models\EquipeSupervisor;
use app\widgets\GridView;
use yii\helpers\Html;

$equipe = $parentObject;

$this->title = 'Supervisores da Equipe "' . $equipe->nome . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipe-agente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar ao cadastro de Equipe',
        Yii::$app->urlManager->createUrl('equipe/index'),
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
                'Cadastrar Supervisor na Equipe',
                Yii::$app->urlManager->createUrl(['equipe-supervisor/create', 'parentID' => $equipe->id]),
                [
                    'class' => 'btn btn-flat success',
                    'data-role' => 'create',
                ]
            ),
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'usuario_id',
                'header' => 'Usuário',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->usuario_id ? Html::encode($model->usuario->nome) : null;
                }
            ],
            [
                'class' => 'app\components\DependentCRUDActionColumn',
                'template' => '{update} {delete}',
                'parentID' => Html::encode($equipe->id),
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
        ],
    ]); ?>

</div>
