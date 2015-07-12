<h1>Indicadores de Focos</h1>

<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'Resumo por Ano',
            'url' => ['indicador/resumo-focos'],
            'options' => ['id' => 'resumo-ano']
        ],

        [
            'label' => 'Evolução por Mês',
            'url' => ['indicador/evolucao-focos'],
            'options' => ['id' => 'evolucao-mes']
        ],
        [
            'label' => 'Por Bairros',
            'url' => ['indicador/focos-bairro'],
            'options' => ['id' => 'por-bairro']
        ],
        [
            'label' => 'Por Tipo de Depósito',
            'url' => ['indicador/focos-tipo-deposito'],
            'options' => ['id' => 'por-deposito']
        ],
    ],
]);
?>
