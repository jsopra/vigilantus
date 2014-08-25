<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'Mapa',
            'url' => ['relatorio/area-tratamento-mapa'],
        ],
        [
            'label' => 'Áreas de Tratamento',
            'url' => ['relatorio/area-tratamento'],
        ],
        [
            'label' => 'Focos',
            'url' => ['relatorio/area-tratamento-focos'],
        ],
    ],
]);
?>