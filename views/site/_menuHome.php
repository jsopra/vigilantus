<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\helpers\models\MunicipioHelper;
?>

<h1>
    <?php if($municipio->brasao) : ?>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <?php endif; ?>

    <?= Html::encode('PM de ' . $municipio->nome . '/' . $municipio->sigla_estado) ?>
</h1>

<h2><?= Html::encode($this->title) ?></h2>

<br />

<?php
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
