<?php

use app\models\Municipio;
use app\models\Usuario;
use app\widgets\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ImovelCondicaoSearch $searchModel
 */

$this->title = 'Condições de Imóveis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imovel-condicao-index" data-role="modal-grid">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Condição de Imóvel',
                    Yii::$app->urlManager->createUrl('imovel-condicao/create'),
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
            [
                'attribute' => 'exibe_nome',
                'format' => 'boolean',
                'filter' => [1 => 'Sim', 0 => 'Não'],
            ],
            [
                'attribute' => 'inserido_por',
                'filter' => Usuario::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->inseridoPor ? $model->inseridoPor->nome : null;
                }
            ],
            [
                'attribute' => 'data_cadastro',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->formatted_data_cadastro;
                }
            ],
            [
                'attribute' => 'atualizado_por',
                'filter' => Usuario::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->atualizadoPor ? $model->atualizadoPor->nome : null;
                }
            ],
            [
                'attribute' => 'data_atualizacao',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->formatted_data_atualizacao;
                }
            ],
            [
                'class' => 'app\components\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
