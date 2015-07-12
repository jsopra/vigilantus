<h1>Indicadores de Ocorrências</h1>

<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'Por Mês',
            'url' => ['/ocorrencia/indicador/ocorrencias-mes'],
            'options' => ['id' => 'evolucao-mes']
        ],
        [
            'label' => 'Por Status',
            'url' => ['/ocorrencia/indicador/ocorrencias-status'],
            'options' => ['id' => 'indicadores-gerais']
        ],
        [
            'label' => 'Por Problema',
            'url' => ['/ocorrencia/indicador/ocorrencias-problema'],
            'options' => ['id' => 'ocorrencias-tipo']
        ],
    ],
]);
?>
