<?php
use yii\helpers\Html;
use app\widgets\GridView;
use app\models\DenunciaHistoricoTipo;
use app\models\DenunciaStatus;
use app\models\Usuario;
?>

<br />

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'exportable' => $isExportable,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'data_hora',
            'options' => [
                'width' => '10%',
            ],
            'value' => function ($model, $index, $widget) {
                return $model->getFormattedAttribute('data_hora');
            },
        ],
        [
            'attribute' => 'tipo',
            'value' => function ($model, $index, $widget) {
                return DenunciaHistoricoTipo::getDescricao($model->tipo);
            }
        ],
        [
            'attribute' => 'status_antigo',
            'value' => function ($model, $index, $widget) {
                return DenunciaStatus::getDescricao($model->status_antigo);
            }
        ],
        [
            'attribute' => 'status_novo',
            'value' => function ($model, $index, $widget) {
                return DenunciaStatus::getDescricao($model->status_novo);
            }
        ],
        'observacoes',
        [
            'attribute' => 'usuario',
            'value' => function ($model, $index, $widget) {
                return $model->usuario_id ? $model->usuario->nome : null;
            }
        ],
    ],
]); ?>
