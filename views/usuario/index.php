<?php

use app\models\Municipio;
use app\models\UsuarioRole;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UsuarioSearch $searchModel
 */

$this->title = 'Usuários';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Cadastrar Usuário', ['create'], ['class' => 'btn btn-flat success']) ?>
	</p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'nome',
            'login',
            [
                'attribute' => 'usuario_role_id',
                'filter' => UsuarioRole::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->role ? $model->role->nome : null;
                }
            ],
            [
                'attribute' => 'ultimo_login',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->formatted_ultimo_login;
                }
            ],
            'email:email',
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
