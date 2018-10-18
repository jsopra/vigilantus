<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\web\View;
use yii\widgets\DetailView;
use Yii\helpers\Url;
use app\models\VisitaAtividade;
use app\models\VisitaTipo;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'visita_atividade_id',
            'filter' => false,
            'value' => $model->visita_atividade_id ? VisitaAtividade::getDescricao($model->visita_atividade_id) : null,
        ],
        [
            'attribute' => 'quarteirao_id',
            'filter' => false,
            'value' => $model->quarteirao_id ? $model->quarteirao->numero_quarteirao : null,
        ],
        [
            'header' => 'Bairro',
            'filter' => false,
            'value' => $model->quarteirao_id ? $model->quarteirao->bairro->nome : null,
        ],
        'logradouro',
        'numero',
        'sequencia',
        'complemento',
        [
            'attribute' => 'tipo_imovel_id',
            'filter' => false,
            'value' => $model->tipo_imovel_id ? $model->tipoImovel->nome : null,
        ],
        'hora_entrada',
        [
            'attribute' => 'visita_tipo',
            'filter' => false,
            'value' => $model->visita_tipo ? VisitaTipo::getDescricao($model->visita_tipo) : null,
        ],
        'pendencia',
        'depositos_eliminados',
        'numero_amostra_inicial',
        'numero_amostra_final',
        'quantidade_tubitos',
    ]

]) ?>