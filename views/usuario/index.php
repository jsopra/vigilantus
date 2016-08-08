<?php
use app\models\UsuarioRole;
use app\widgets\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UsuarioSearch $searchModel
 */

$this->title = 'Usuários';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Usuário',
                    Yii::$app->urlManager->createUrl('usuario/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                    ]
                );
            }
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'nome',
            'login',
            [
                'attribute' => 'usuario_role_id',
                'filter' => UsuarioRole::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->role ? Html::encode($model->role->nome) : Html::encode(null);
                }
            ],
            [
                'attribute' => 'ultimo_login',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return Html::encode($model->formatted_ultimo_login);
                }
            ],
            'email:email',
            'recebe_email_ocorrencia:boolean',
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
