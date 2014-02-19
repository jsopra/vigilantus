<?php

use app\models\Municipio;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ImovelTipoSearch $searchModel
 */
$this->title = 'Tipos de Imóvel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imovel-tipo-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Cadastrar Tipo de Imóvel', ['create'], ['class' => 'btn btn-flat success', 'data-role' => 'create']) ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'nome',
            'sigla',
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
