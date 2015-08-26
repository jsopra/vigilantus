<?php

use app\models\EquipeAgente;
use app\widgets\GridView;
use yii\helpers\Html;

$setor = $parentObject;

$this->title = 'Setor "' . $setor->nome . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

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
        'exportable' => false,
        'buttons' => [
            'create' => Html::a(
                'Cadastrar Usuário',
                Yii::$app->urlManager->createUrl(['setor-usuario/create', 'parentID' => $setor->id]),
                [
                    'class' => 'btn btn-flat success',
                    'data-role' => 'create',
                ]
            ),
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            'login',
            [
                'class' => 'app\components\DependentCRUDActionColumn',
                'template' => '{delete}',
                'parentID' => $setor->id,
                'options' => ['class' => 'vigilantus-grid-buttons-ud'],
            ],
        ],
    ]); ?>

</div>
