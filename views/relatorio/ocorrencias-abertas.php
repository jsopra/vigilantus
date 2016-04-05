<?php
use app\widgets\GridView;
use yii\helpers\Html;

$this->title = 'Ocorrências Abertas';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?php

echo GridView::widget([
    'dataProvider' => $model->dataProvider,
    'columns' => [
        [
            'attribute' => 'hash_acesso_publico',
            'options' => [
                'width' => '5%',
            ],
        ],
        [
            'header' => 'Qtde. Dias<br />em aberto',
            'options' => [
                'width' => '5%',
            ],
                'attribute' => 'qtde_dias_aberto',
                'value' => function ($model, $index, $widget) {
                    return $model->qtde_dias_em_aberto;
                },
        ],
        [
            'header' => 'Data Hora',
            'format' => 'raw',
            'options' => [
                'width' => '20%',
            ],
            'value' => function ($model, $index, $widget) {
            $linha = [];
            foreach ($model->getOcorrenciaHistoricos()->all() as $averiguacao) {
                $linha[] = $averiguacao->getFormattedAttribute('data_hora');
            }
            return implode('<br>', $linha);
            }
        ],
        [
            'header' => 'Nome Agente',
            'value' => function ($model, $index, $widget) {
            $linha = [];
            foreach ($model->getOcorrenciaHistoricos()->all() as $averiguacao) {
                if($averiguacao->agente){
                $linha[] = $averiguacao->agente->nome;
                }
            }
            return implode('<br>', $linha);
            }
        ],
        [
            'attribute' => 'ocorrencia_tipo_problema_id',
            'value' => function ($model, $index, $widget) {
                if($model->ocorrenciaTipoProblema){
                return $model->ocorrenciaTipoProblema->nome;
                }
            },
            /*'options' => [
                'width' => '10%',
            ],*/
        ],
    ],
]);
