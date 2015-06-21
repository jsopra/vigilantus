<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use app\models\Modulo;
?>

<h1 id="stepguide-title">
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
            'label' => 'Ocorrências',
            'url' => ['site/resumo-ocorrencias'],
            'options' => ['id' => 'ocorrencias'],
            'visible' => \Yii::$app->user->can('Gerente') && \Yii::$app->user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA),
        ],
        [
            'label' => 'Focos',
            'url' => ['site/resumo-focos'],
            'options' => ['id' => 'focos']
        ],
    ],
]);
?>
