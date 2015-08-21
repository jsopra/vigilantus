<?php

use app\models\EquipeAgente;
use app\widgets\GridView;
use yii\helpers\Html;

$equipe = $parentObject;

$this->title = 'Setores"' . $equipe->nome . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="***">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar ao cadastro de Setor',
        Yii::$app->urlManager->createUrl('setor/index'),
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
                'Cadastrar Setores',
                Yii::$app->urlManager->createUrl(['setor-usuario/create', 'parentID' => $equipe->id]),
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
                'template' => '{delete}',
                'parentID' => $setor->id,
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
        ],
    ]); ?>

</div>
