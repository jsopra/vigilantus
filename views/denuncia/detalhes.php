<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Detalhes de Denúncia #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Detalhes';
?>
<div class="denuncia-detalhes">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar às denúncias',
        Yii::$app->urlManager->createUrl('denuncia/index'),
        [
            'class' => 'btn btn-link',
        ]
    );
    ?>

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Objeto da denúncia',
                'content' => $this->render('detalhe/_detalheObjetoDenuncia', ['model' => $model]),
                'active' => true
            ],
            [
                'label' => 'Dados do denunciante',
                'content' => $this->render('detalhe/_detalheDadosDenunciante', ['model' => $model]),
                'active' => false
            ],
            [
                'label' => 'Histórico',
                'content' => $this->render('detalhe/_detalheHistorico', ['model' => $model, 'dataProvider' => $dataProvider]),
                'active' => false
            ],
            [
                'label' => 'Mapa',
                'content' => $this->render('detalhe/_detalheMapa', ['model' => $model]),
                'active' => false
            ],
        ]
    ]);
    ?>
</div>