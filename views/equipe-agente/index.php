<?php

use app\models\EquipeAgente;
use app\widgets\GridView;
use yii\helpers\Html;

$equipe = $parentObject;

$this->title = 'Agentes da Equipe"' . $equipe->nome . '"';
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
                'Cadastrar Agente na Equipe',
                Yii::$app->urlManager->createUrl(['equipe-agente/create', 'parentID' => $equipe->id]),
                [
                    'class' => 'btn btn-flat success',
                    'data-role' => 'create',
                ]
            ),
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'codigo',
            'nome',
            'ativo:boolean',
            [
                'class' => 'app\components\DependentCRUDActionColumn',
                'template' => '{update} {delete}',
                'parentID' => Html::encode($equipe->id),
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
        ],
    ]); ?>

</div>
