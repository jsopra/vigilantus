<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'RG',
            'url' => ['site/home'],
            'options' => ['id' => 'rg']
        ],
        
        [
            'label' => 'Focos',
            'url' => ['site/resumo-focos'],
            'options' => ['id' => 'focos']
        ],
    ],
]);
?>