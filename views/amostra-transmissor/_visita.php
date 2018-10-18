<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\web\View;
use yii\widgets\DetailView;
use Yii\helpers\Url;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'semana_epidemiologica_visita_id',
        'visita_atividade_id',
        'rua_id',
        'quarteirao_id',
        'logradouro',
        'numero',
        'sequencia',
        'complemento',
        'tipo_imovel_id',
        'hora_entrada',
        'visita_tipo',
        'pendencia',
        'depositos_eliminados',
        'numero_amostra_inicial',
        'numero_amostra_final',
        'quantidade_tubitos',
    ]

]) ?>