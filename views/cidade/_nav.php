<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'Focos',
            'url' => ['cidade/index', 'id' => $municipio->id],
            'options' => ['id' => 'focos']
        ],
        /*
        [
            'label' => 'Denúncias',
             'url' => ['cidade/denuncias', 'id' => $municipio->id],
            'options' => ['id' => 'denuncias']
        ],
        */
    ],
]);
?>
<br />