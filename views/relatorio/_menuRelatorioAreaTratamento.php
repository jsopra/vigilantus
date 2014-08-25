<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'Áreas de Tratamento',
            'url' => ['relatorio/area-tratamento'],
            'options' => ['id' => 'area']
        ],
        
        [
            'label' => 'Mapa',
            'url' => ['relatorio/area-tratamento-mapa'],
            'options' => ['id' => 'area-mapa']
        ],
        [
            'label' => 'Focos',
            'url' => ['relatorio/area-tratamento-focos'],
            'options' => ['id' => 'area-focos']
        ],
    ],
]);
?>